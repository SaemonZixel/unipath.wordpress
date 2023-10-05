<?php
/*
Plugin Name: UniPath
Plugin URI: http://github.com/SaemonZixel/unipath.wordpress/
Description: UniPath for WordPress 
Version: 2.4rc4
Author: Saemon Zixel
Author URI: http://github.com/SaemonZixel/
*/

defined('ABSPATH') or die('unipath.wordpress');

define('PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PLUGIN_URL', plugins_url('', __FILE__));

add_action('plugins_loaded', 'unipath_plugin_initialize_unipath_library');
add_action('admin_init', 'unipath_plugin_admin_init_action');
add_action('admin_menu', 'unipath_plugin_admin_menu_action');

// Добавляет пункт "UniPath" в меню "Инструменты" в Админке
function unipath_plugin_admin_menu_action() {
    add_submenu_page('tools.php', 'UniPath', 'UniPath Tester', 'manage_options', 'unipath_plugin', 'unipath_plugin_echo', PLUGIN_URL.'/unipath-icon-16.png', '33.0099');
}

// содержимое плагина (его HTML)
function unipath_plugin_echo() {
	if (!current_user_can('manage_options')) {
		return;
	}
	
    echo '<div class="wrap" style="margin-bottom:-25px">';
    echo '<h2>UniPath Tester</h2>';
    // data:text/plain;base64,RXZhbHV0aW5nLi4u - Evaluting...
    echo '<div class="wp-filter query-box" style="margin-bottom:0"><form id="unipath-evalution-form" action="/wp-admin/tools.php?page=unipath_plugin" method="get" target="iframe-for-evalution-result" style="padding: 12px 0">
		<label for="query-box-input" class="screen-reader-text">UniPath expression:</label>
		<textarea name="unipath_expression" id="query-box-input" style="width:86%; float:left;margin-right:3px">/wpdb/dbh/wp_posts[post_status=`publish`]</textarea>
		<input type="submit" value="Evalute" class="button" id="search-submit" onclick="document.getElementById(\'iframe-for-evalution-result\').src=\'data:text/plain;charset=UTF-8;base64,TG9hZGluZy4uLg==\';setTimeout(function(){document.getElementById(\'unipath-evalution-form\').submit()},20);return false;" style="margin-bottom: 3px;">
		<input style="display:none" type="submit" value="RAW" class="button" id="search-submit" onclick="document.getElementById(\'iframe-for-evalution-result\').src=\'data:text/plain;charset=UTF-8;base64,TG9hZGluZy4uLg==\';setTimeout(function(){document.getElementById(\'unipath-evalution-form\').submit()},20);return false;">
		<input type="hidden" value="unipath_plugin" name="page">
		<div class="wp-clearfix"></div>
	</form></div>';
		
    echo '<div class="wp-filter" style="margin-bottom:0px;height:80%"><iframe id="iframe-for-evalution-result" name="iframe-for-evalution-result" src="javascript:void(0)" frameborder="0" style="min-height:500px;width:100%;padding-top:12px;padding-bottom:12px;"></iframe></div>
    </div>';

}

// вызывается перед рендерингом админки
function unipath_plugin_admin_init_action() {
	
	if (!current_user_can('manage_options')) {
		return;
	}
	
	if(array_key_exists('unipath_expression', $_GET)) {

		error_reporting(E_ALL & ~E_STRICT);
		ini_set('display_errors', 1);
		
// 		require_once PLUGIN_DIR.'unipath.php';
		
		// WordPress add slashes manualy in wp-settings.php:add_magic_quotes()
		$unipath = wp_unslash($_REQUEST['unipath_expression']);
		
 		header('Content-Type: text/html; charset=UTF-8');
		echo '<!doctype html><html>
			<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<title>'.htmlspecialchars($unipath).'</title></head>
			<body><xmp style="white-space:pre-wrap">';
	
// 		$GLOBALS['unipath_debug'] = true;
		var_dump(uni($unipath));
// 		$GLOBALS['unipath_debug'] = false;
		
		echo '</xmp></body></html>';
		exit;
	}

//     echo '<div class="wrap" style="white-space:pre-wrap">';
//     var_dump($_GET);
//     echo '</div>';
	
   	return;
}

// подключим UniPath и создадим "wp"
function unipath_plugin_initialize_unipath_library($arg = null) {

	if(file_exists(PLUGIN_DIR.'unipath.php/unipath-2.4.php'))
		include_once PLUGIN_DIR.'unipath.php/unipath-2.4.php';
	else
		include_once PLUGIN_DIR.'unipath.php/unipath.php';

	do_action('unipath_initialized');
}
