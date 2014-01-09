<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require 'constants.php';

$route[MODULE_NAME.'/admin/categories(:any)'] = 'admin_categories$1';
$route[MODULE_NAME.'/admin/faqs(:any)'] = 'admin_faqs$1';
