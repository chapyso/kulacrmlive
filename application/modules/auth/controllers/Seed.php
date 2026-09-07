<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Seed Controller - Disabled for Security
 * Direct web access has been disabled to prevent unauthorized database modifications and credential exposure.
 */
class Seed extends MX_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        show_404();
    }

    public function superadmin() {
        show_404();
    }

    public function test_home_500() {
        show_404();
    }
}
