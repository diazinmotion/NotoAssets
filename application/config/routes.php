<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller']    = 'home';
$route['404_override']          = '';
$route['translate_uri_dashes']  = FALSE;

// APP SPECIFIC ROUTES
$route['logout']                    = 'auth/logout';
$route['assets/laptop/edit/(:any)'] = 'assets/laptop/create/$1';
$route['licenses/edit/(:any)']      = 'licenses/create/$1';
