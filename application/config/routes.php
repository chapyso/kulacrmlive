<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['about'] = 'auth/about';
$route['auth/about'] = 'auth/about';
$route['landing'] = 'auth/landing';
$route['auth/landing'] = 'auth/landing';



/* End of file routes.php */
/* Location: ./application/config/routes.php */

// Mobile & Integration REST API v1 Routes
$route['api/v1/auth/login'] = 'api_v1/login';
$route['api/v1/auth/me']    = 'api_v1/me';
$route['api/v1/dashboard']  = 'api_v1/dashboard';
$route['api/v1/livestock']  = 'api_v1/livestock';
$route['api/v1/sheds']      = 'api_v1/sheds';
$route['api/v1/vaccines']   = 'api_v1/vaccines';
$route['api/v1/sales']      = 'api_v1/sales';
$route['api/v1/expenses']   = 'api_v1/expenses';
$route['api/v1/clients']    = 'api_v1/clients';
$route['api/v1/suppliers']  = 'api_v1/suppliers';
$route['api/v1/reports/summary'] = 'api_v1/reports_summary';

// Legacy Mobile REST API Routes (iOS / Android / React Native / Flutter)
$route['api/login'] = 'api/login';
$route['api/dashboard'] = 'api/dashboard';
$route['api/livestock'] = 'api/livestock';
$route['api/sheds'] = 'api/sheds';
$route['api/sales'] = 'api/sales';


// Super Admin SaaS Platform Routes
$route['superadmin'] = 'superadmin/superadmin/index';
$route['superadmin/tenants'] = 'superadmin/superadmin/tenants';
$route['superadmin/plans'] = 'superadmin/superadmin/plans';
$route['superadmin/subscriptions'] = 'superadmin/superadmin/subscriptions';
$route['superadmin/settings'] = 'superadmin/superadmin/settings';
$route['superadmin/profile'] = 'superadmin/superadmin/profile';
$route['superadmin/save_tenant'] = 'superadmin/superadmin/save_tenant';
$route['superadmin/update_tenant_subscription'] = 'superadmin/superadmin/update_tenant_subscription';
$route['superadmin/toggle_status/(:num)'] = 'superadmin/superadmin/toggle_status/$1';
$route['superadmin/impersonate/(:num)'] = 'superadmin/superadmin/impersonate/$1';
$route['superadmin/stop_impersonating'] = 'superadmin/superadmin/stop_impersonating';

// Path-Based Tenant Routes: http://localhost:8080/{slug_name}/...
$route['(:any)/login'] = 'auth/login';
$route['(:any)/about'] = 'auth/about';
$route['(:any)/logout'] = 'auth/logout';
$route['(:any)/dashboard'] = 'home/index';
$route['(:any)/livestock'] = 'livestock/index';
$route['(:any)/livestock/(:any)'] = 'livestock/$1';
$route['(:any)/shed'] = 'shed/index';
$route['(:any)/shed/(:any)'] = 'shed/$1';
$route['(:any)/vaccine'] = 'vaccine/index';
$route['(:any)/vaccine/(:any)'] = 'vaccine/$1';
$route['(:any)/food'] = 'food/index';
$route['(:any)/food/(:any)'] = 'food/$1';
$route['(:any)/product'] = 'product/index';
$route['(:any)/product/(:any)'] = 'product/$1';
$route['(:any)/sale'] = 'sale/index';
$route['(:any)/sale/(:any)'] = 'sale/$1';
$route['(:any)/client'] = 'client/index';
// Path-Based Tenant Routes for Users & Roles Management
$route['(:any)/users'] = 'users/index';
$route['(:any)/users/roles'] = 'users/roles';
$route['(:any)/users/create_role'] = 'users/create_role';
$route['(:any)/users/permission_matrix'] = 'users/permission_matrix';
$route['(:any)/users/save_permission_matrix'] = 'users/save_permission_matrix';
$route['(:any)/users/departments'] = 'users/departments';
$route['(:any)/users/add_department'] = 'users/add_department';
$route['(:any)/users/activity_logs'] = 'users/activity_logs';
$route['(:any)/users/invite'] = 'users/invite';
$route['(:any)/users/create'] = 'users/create';
$route['(:any)/users/update_status'] = 'users/update_status';
$route['(:any)/users/(:any)'] = 'users/$1';


$route['(:any)/client/(:any)'] = 'client/$1';
$route['(:any)/supplier'] = 'supplier/index';
$route['(:any)/supplier/(:any)'] = 'supplier/$1';
$route['(:any)/expense'] = 'expense/index';
$route['(:any)/expense/(:any)'] = 'expense/$1';
$route['(:any)/staff'] = 'staff/index';
$route['(:any)/staff/(:any)'] = 'staff/$1';
$route['(:any)/report'] = 'report/index';
$route['(:any)/report/(:any)'] = 'report/$1';
$route['(:any)/settings'] = 'settings/index';
$route['(:any)/settings/(:any)'] = 'settings/$1';

// General routes
$route['users'] = 'users/users/index';
$route['users/(:any)'] = 'users/users/$1';

$route['dashboard'] = 'home/index';
$route['add_livestock_type'] = "livestock/addLivestockType";
$route['insert_livestock_type'] = "livestock/insertLivestockType";
$route['update_livestock_type'] = "livestock/updateLivestockType";

