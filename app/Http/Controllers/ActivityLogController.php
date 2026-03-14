<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Activity::with('causer')->latest();

        // Filter by User / Causer
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        // Filter by Event Type (created, updated, deleted)
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filter by Date Range
        if ($request->filled('date_start')) {
            $query->whereDate('created_at', '>=', $request->date_start);
        }
        if ($request->filled('date_end')) {
            $query->whereDate('created_at', '<=', $request->date_end);
        }

        $logs = $query->paginate(20)->withQueryString();

        $users = User::orderBy('name')->get();

        return view('pengaturan.log.index', compact('logs', 'users'));
    }

    public function prune(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'event' => 'nullable|in:created,updated,deleted',
            'date_start' => 'nullable|date',
            'date_end' => 'required|date',
        ]);

        $query = Activity::query();

        if (! empty($validated['user_id'] ?? null)) {
            $query->where('causer_id', $validated['user_id']);
        }

        if (! empty($validated['event'] ?? null)) {
            $query->where('event', $validated['event']);
        }

        if (! empty($validated['date_start'] ?? null)) {
            $query->whereDate('created_at', '>=', $validated['date_start']);
        }

        $query->whereDate('created_at', '<=', $validated['date_end']);

        $deleted = (int) $query->delete();

        return redirect()
            ->route('activity-log.index', [
                'user_id' => $validated['user_id'] ?? null,
                'event' => $validated['event'] ?? null,
                'date_start' => $validated['date_start'] ?? null,
                'date_end' => $validated['date_end'],
            ])
            ->with('success', "Log berhasil dihapus: {$deleted} baris.");
    }
}
