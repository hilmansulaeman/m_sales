<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'panel';
$route['login'] = 'panel/login';
$route['translate_uri_dashes'] = FALSE;

// ===================== NEW UI SIDEBAR ROUTES =====================

// Dashboard
$route['dashboard'] = 'dashboard/index';

// Data Entry
$route['data_entry'] = 'data_entry/index';

// Application Input - Halaman
$route['application_input/pemol']      = 'application_input/pemol';
$route['application_input/merchant']   = 'application_merchant/index';
$route['application_input/merchant_get_data'] = 'application_merchant/get_data';
$route['application_input/merchant_filter']   = 'application_merchant/filter';
$route['application_input/merchant_export']   = 'application_merchant/export';
$route['application_input/cc_reguler'] = 'application_input/cc_reguler';
$route['application_input/cc_ms']      = 'application_input/cc_ms';
$route['application_input/corporate']  = 'application_input/corporate';
$route['application_input/sc']         = 'application_input/sc';
$route['application_input/pl']         = 'application_input/pl';
$route['application_input/cc_dsr']     = 'application_input/cc_dsr';

// Application Input - AJAX endpoints (Pemol, semua dalam 1 controller)
$route['application_input/pemol_get_data']             = 'application_input/pemol_get_data';
$route['application_input/pemol_filter']               = 'application_input/pemol_filter';
$route['application_input/pemol_detail/(:any)/(:any)'] = 'application_input/pemol_detail/$1/$2';
$route['application_input/pemol_get_data_spv']         = 'application_input/pemol_get_data_spv';
$route['application_input/pemol_export']               = 'application_input/pemol_export';

// Application Check
$route['application_check/pemol']     = 'application_check/pemol';
$route['application_check/merchant']  = 'application_check/merchant';
$route['application_check/cc']        = 'application_check/cc';
$route['application_check/corporate'] = 'application_check/corporate';
$route['application_check/sc']        = 'application_check/sc';
$route['application_check/pl']        = 'application_check/pl';

// Data Decision
$route['data_decision/pemol']     = 'data_decision/pemol';
$route['data_decision/merchant']  = 'data_decision/merchant';
$route['data_decision/cc']        = 'data_decision/cc';
$route['data_decision/corporate'] = 'data_decision/corporate';
$route['data_decision/sc']        = 'data_decision/sc';
$route['data_decision/pl']        = 'data_decision/pl';
$route['data_decision/pemol_dsr'] = 'data_decision/pemol_dsr';

// Incoming - Route sidebar
$route['incoming/mobile_sales'] = 'incoming/mobile_sales';
$route['incoming/pemol']        = 'incoming/pemol';
$route['incoming/tm_cc']        = 'incoming/tm_cc';
$route['incoming/tm_sc']        = 'incoming/tm_sc';

// My Performance
$route['my_performance'] = 'my_performance/new_ui';

// Data Addendum
$route['data_addendum'] = 'data_addendum/index';

// Team Performance
$route['team_performance'] = 'team_performance/index';

// Sales Information
$route['sales_information'] = 'sales_information/index';

// Candidate Info
$route['candidate_info/candidate_details'] = 'candidate_info/candidate_details';
$route['candidate_info/approval']          = 'candidate_info/approval';
$route['candidate_info/history']           = 'candidate_info/history';

// Request to HRD
$route['request_to_hrd']          = 'request_to_hrd/index';
$route['request_to_hrd/restruct'] = 'request_to_hrd/restruct';
$route['request_to_hrd/level']    = 'request_to_hrd/level';
$route['request_to_hrd/reactive'] = 'request_to_hrd/reactive';

// Approval
$route['approval/restruct']  = 'approval/restruct';
$route['approval/reaktif']   = 'approval/reaktif';
$route['approval/promotion'] = 'approval/promotion';

// Check Postal Code
$route['check_postal_code'] = 'check_postal_code/index';

// Duplicate Check
$route['duplicate_check'] = 'duplicate_check/index';

// Monitoring
$route['monitoring'] = 'monitoring/index';

// Slip Incentive
$route['slip_incentive'] = 'slip_incentive/index';

// ===================== END NEW UI SIDEBAR ROUTES =====================

$route['404_override'] = '';
