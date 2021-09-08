<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller']    = 'home';
$route['404_override']          = '';
$route['translate_uri_dashes']  = FALSE;

// APP SPECIFIC ROUTES
$route['logout']                = 'auth/logout';
$route['kiosk/(:any)/(:any)']   = 'kiosk/index/$1/$2';
$route['register/(:any)']       = 'kiosk/register/$1';
$route['display/(:any)/(:num)'] = 'display/index/$1/$2';
