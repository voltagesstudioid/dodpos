<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LogWebActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        /** @var Response $response */
        $response = $next($request);

        if (! config('activitylog.enabled')) {
            return $response;
        }

        $user = Auth::user();
        if (! $user instanceof Model) {
            return $response;
        }

        $method = strtoupper((string) $request->method());
        if (! in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return $response;
        }

        $route = $request->route();
        $routeName = $route?->getName();

        if (is_string($routeName) && str_starts_with($routeName, 'activity-log.')) {
            return $response;
        }

        $durationMs = (int) round((microtime(true) - $start) * 1000);
        $statusCode = method_exists($response, 'getStatusCode') ? (int) $response->getStatusCode() : 0;

        $event = $this->eventName($method, $routeName);

        $subject = null;
        if ($route) {
            foreach ($route->parameters() as $p) {
                if (is_object($p) && method_exists($p, 'getKey')) {
                    $subject = $p;
                    break;
                }
            }
        }

        $description = ($routeName ? $routeName : $method.' '.$request->path());
        $outcome = $statusCode >= 400 ? 'error' : 'success';

        $properties = [
            'request' => [
                'method' => $method,
                'path' => '/'.$request->path(),
                'route' => $routeName,
                'params' => $this->safeParams($route?->parameters() ?? []),
                'input' => $this->safeInput($request->except([
                    'password',
                    'password_confirmation',
                    'current_password',
                    'token',
                    '_token',
                    'selfie_data',
                ])),
            ],
            'response' => [
                'status_code' => $statusCode,
                'duration_ms' => $durationMs,
                'outcome' => $outcome,
            ],
        ];

        try {
            activity('web')
                ->causedBy($user)
                ->when($subject, fn ($a) => $a->performedOn($subject))
                ->event($event)
                ->withProperties($properties)
                ->log($description);
        } catch (\Throwable) {
            return $response;
        }

        return $response;
    }

    private function eventName(string $method, ?string $routeName): string
    {
        if ($method === 'DELETE') {
            return 'deleted';
        }
        if (in_array($method, ['PUT', 'PATCH'], true)) {
            return 'updated';
        }

        $name = strtolower((string) ($routeName ?? ''));
        if ($name === '') {
            return 'performed';
        }

        $performedHints = [
            'generate',
            'sync',
            'lock',
            'unlock',
            'approve',
            'reject',
            'submit',
            'close',
            'import',
            'export',
            'restore',
            'backup',
            'prune',
        ];

        foreach ($performedHints as $h) {
            if (Str::contains($name, $h)) {
                return 'performed';
            }
        }

        return 'created';
    }

    private function safeParams(array $params): array
    {
        $out = [];
        foreach ($params as $k => $v) {
            if (is_object($v) && method_exists($v, 'getKey')) {
                $out[$k] = $v->getKey();

                continue;
            }
            if (is_scalar($v) || $v === null) {
                $out[$k] = $this->truncate((string) ($v ?? ''), 200);
            }
        }

        return $out;
    }

    private function safeInput(array $input): array
    {
        $out = [];
        foreach ($input as $k => $v) {
            if (is_array($v)) {
                $out[$k] = '[array]';

                continue;
            }
            if (is_object($v)) {
                $out[$k] = '[object]';

                continue;
            }
            if ($v === null) {
                $out[$k] = null;

                continue;
            }
            if (is_bool($v) || is_int($v) || is_float($v)) {
                $out[$k] = $v;

                continue;
            }
            if (is_string($v)) {
                $out[$k] = $this->truncate($v, 500);
            }
        }

        return $out;
    }

    private function truncate(string $value, int $max): string
    {
        $value = trim($value);
        if (mb_strlen($value) <= $max) {
            return $value;
        }

        return mb_substr($value, 0, $max).'…';
    }
}
