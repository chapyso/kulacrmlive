<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

// Resolve the configured timezone from settings, with a safe fallback.
// Cached per-request to avoid a settings query on every call.
function lsci_timezone()
{
    static $tz = null;
    if ($tz !== null) {
        return $tz;
    }
    $CI =& get_instance();
    $configured = '';
    if (isset($CI->db)) {
        if (!isset($CI->settings_model)) {
            $CI->load->model('settings/settings_model');
        }
        $row = $CI->settings_model->getSettings();
        if ($row && !empty($row->timezone)) {
            $configured = $row->timezone;
        }
    }
    if ($configured && in_array($configured, timezone_identifiers_list(), true)) {
        $tz = $configured;
    } else {
        $tz = 'Africa/Kampala';
    }
    return $tz;
}

function get_current_time()
{
    $date = new DateTime('now', new DateTimeZone(lsci_timezone()));
    return $date->format('Y-m-d H:i');
}

function get_current_date()
{
    $date = new DateTime('now', new DateTimeZone(lsci_timezone()));
    return $date->format('d-m-Y');
}

// Get Dynamic Month Name
function get_all_month()
{
    $months = array(
        '01' => 'January',
        '02' => 'February',
        '03' => 'March',
        '04' => 'April',
        '05' => 'May',
        '06' => 'June',
        '07' => 'July ',
        '08' => 'August',
        '09' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December',
    );
    return $months;
}

// Dynamic Year - current year to previous year 
function get_all_year()
{
    $now = new DateTime();
    $year = $now->format("Y");
    $allYear = [];
    for ($i = 2010; $i <= $year; $i++) {
        array_push($allYear, $i);
    }
    $reverse = array_reverse($allYear, true);
    return $reverse;
}


// Number to month name
function number_to_month_name($number)
{
    $monthNumber = $number;
    $monthName = date("F", mktime(0, 0, 0, $monthNumber, 10));
    return $monthName . ',' . ' ';
}
