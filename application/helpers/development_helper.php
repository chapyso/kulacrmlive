<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
function x_debug($data)
{
    echo '<pre>';
    print_r($data);
    echo "<br>";
    exit();
}

function x_call()
{
    $driverInstanse = &get_instance();
    echo '<pre>';
    print_r($driverInstanse->input->post());
    echo "<br>";
    exit();
}




function number_format_currency($num, $point)
{
    $formatNumber = number_format($num, $point, '.', ',');
    return $formatNumber;
}
