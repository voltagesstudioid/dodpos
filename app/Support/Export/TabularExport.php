<?php

namespace App\Support\Export;

use Symfony\Component\HttpFoundation\StreamedResponse;

class TabularExport
{
    /**
     * Stream a CSV file.
     *
     * @param string $filename
     * @param array $headers
     * @param iterable $rows
     * @return StreamedResponse
     */
    public static function streamCsv(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    /**
     * Stream an XLSX file (Fallback to tab-separated values disguised as xls/xlsx for simplicity).
     *
     * @param string $filename
     * @param array $headers
     * @param iterable $rows
     * @return StreamedResponse
     */
    public static function streamXlsx(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        // Simple fallback if no Excel package is installed. Excel opens TSV easily.
        $filename = str_replace('.xlsx', '.xls', $filename);
        
        return response()->streamDownload(function () use ($headers, $rows) {
            $file = fopen('php://output', 'w');
            fputs($file, implode("\t", $headers) . "\n");
            foreach ($rows as $row) {
                // Ensure array values are scalar before imploding
                $safeRow = array_map(fn($col) => is_scalar($col) ? $col : '', $row);
                fputs($file, implode("\t", $safeRow) . "\n");
            }
            fclose($file);
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }
}
