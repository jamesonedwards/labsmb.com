<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

// Public routes:
$route['default_controller'] = "home";
$route['404_override'] = '';
$route['projects/(:any)'] = 'projects/index/$1';
$route['search/do_search'] = 'search/do_search';
$route['search/do_search/(:any)'] = 'search/do_search/$1';
$route['search/(:any)'] = 'search/index/$1';

// CMS routes:
$route['admin/view_project/(:any)'] = 'admin/view_project/$1';
$route['admin/save_project'] = 'admin/save_project';
$route['admin/delete_project'] = 'admin/delete_project';
$route['admin/logout'] = 'admin/logout';
$route['admin/show_phpinfo'] = 'admin/show_phpinfo';
$route['admin/(:any)'] = 'admin/index/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */