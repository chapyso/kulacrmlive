<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * Stream a CSV download response and exit. Safe for use directly in controllers.
 *
 * @param string $filename Base filename (without extension). A date stamp + .csv
 *                         is appended. Sanitized to a safe ASCII subset.
 * @param array  $headers  Column headers (one row of strings).
 * @param array  $rows     Array of rows; each row is an array of scalar values
 *                         or an object whose properties match $headers in order.
 */
function csv_response($filename, array $headers, array $rows)
{
    $safe = preg_replace('/[^A-Za-z0-9_\-]/', '_', (string) $filename);
    if ($safe === '') {
        $safe = 'export';
    }
    $out = $safe . '-' . date('Y-m-d') . '.csv';

    // Reset any prior output so the CSV is the only payload.
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $out . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $fh = fopen('php://output', 'w');
    // UTF-8 BOM so Excel auto-detects encoding.
    fwrite($fh, "\xEF\xBB\xBF");

    fputcsv($fh, $headers);
    foreach ($rows as $row) {
        if (is_object($row)) {
            $row = (array) $row;
        }
        fputcsv($fh, array_map(function ($v) {
            return is_scalar($v) || $v === null ? (string) $v : json_encode($v);
        }, array_values($row)));
    }
    fclose($fh);
    exit;
}
