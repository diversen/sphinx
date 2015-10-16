<?php

use diversen\lang;



/**
 * installer file for content module
 *
 * @package    content
 */
//$content_show_latest = get_module_ini('content_show_latest');

$_INSTALL['MAIN_MENU_ITEM'] = array (
    'title' => lang::translate('Search', null, array ('no_translate' => true)),
    'url' => '/sphinx/index',
    'module_name' => 'sphinx',
    'parent' => 0,
    'weight' => 2,
);

$_INSTALL['IS_SHELL'] = "1";



$_INSTALL['VERSION'] = 1.71;
$_INSTALL['PUBLIC_CLONE_URL'] = 'git://github.com/diversen/sphinx.git';
$_INSTALL['PRIVATE_CLONE_URL'] = 'git@github.com:diversen/sphinx.git';
