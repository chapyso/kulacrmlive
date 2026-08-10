<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH.'third_party/MX/Loader.php';

class MY_Loader extends MX_Loader {

    /**
     * Helper delegation for has_permission() within view files where $this refers to loader instance
     */
    public function has_permission($permission_name) {
        if (function_exists('has_permission')) {
            return has_permission($permission_name);
        }
        $CI =& get_instance();
        if (method_exists($CI, 'has_permission')) {
            return $CI->has_permission($permission_name);
        }
        return true;
    }

    /**
     * Delegate undefined method calls on MY_Loader to CI instance if available
     */
    public function __call($method, $params) {
        $CI =& get_instance();
        if (method_exists($CI, $method)) {
            return call_user_func_array(array($CI, $method), $params);
        }
        if (function_exists($method)) {
            return call_user_func_array($method, $params);
        }
        throw new \BadMethodCallException("Undefined method MY_Loader::{$method}()");
    }

    /**
     * Load a service class (HMVC modules/services/ or application/services/)
     */
    public function service($service, $params = NULL, $object_name = NULL) {
        if (empty($service)) return $this;

        if (is_array($service)) {
            foreach ($service as $class) {
                $this->service($class, $params);
            }
            return $this;
        }

        $module = '';
        if (($last_slash = strrpos($service, '/')) !== FALSE) {
            $module = substr($service, 0, $last_slash);
            $service_name = substr($service, $last_slash + 1);
        } else {
            $service_name = $service;
        }

        $class_name = ucfirst($service_name);
        if (empty($object_name)) {
            $object_name = strtolower($service_name);
        }

        $CI =& get_instance();
        if (isset($CI->$object_name)) {
            return $this;
        }

        $locations = array();
        if (!empty($module)) {
            $locations[] = APPPATH . 'modules/' . $module . '/services/' . $class_name . '.php';
            $locations[] = APPPATH . 'modules/' . $module . '/services/' . strtolower($service_name) . '.php';
        }
        $locations[] = APPPATH . 'services/' . $class_name . '.php';

        $found = false;
        foreach ($locations as $file) {
            if (file_exists($file)) {
                require_once($file);
                $found = true;
                break;
            }
        }

        if ($found && class_exists($class_name)) {
            $CI->$object_name = new $class_name($params);
            return $this;
        }

        // Fallback to library loader if file not matched in services folder
        return $this->library($service, $params, $object_name);
    }
}
