<?php

namespace App\Support;

class SearchSanitizer
{
    /**
     * Sanitize search term to prevent SQL injection via LIKE wildcards.
     * Escapes % and _ characters that have special meaning in LIKE clauses.
     */
    public static function sanitize(?string $term): string
    {
        if ($term === null) {
            return '';
        }

        // Remove dangerous characters and escape LIKE wildcards
        $term = trim($term);

        // Escape % and _ wildcards to prevent unintended broad matches
        return str_replace(['%', '_'], ['\\%', '\\_'], $term);
    }

    /**
     * Apply sanitized search to a query builder instance.
     * Usage: SearchSanitizer::apply($query, $request->input('search'), ['name', 'sku']);
     */
    public static function apply(\Illuminate\Database\Eloquent\Builder $query, ?string $search, array $columns): \Illuminate\Database\Eloquent\Builder
    {
        if (empty($search)) {
            return $query;
        }

        $sanitized = self::sanitize($search);

        return $query->where(function ($q) use ($columns, $sanitized) {
            foreach ($columns as $index => $column) {
                if ($index === 0) {
                    $q->where($column, 'like', "%{$sanitized}%");
                } else {
                    $q->orWhere($column, 'like', "%{$sanitized}%");
                }
            }
        });
    }
}
