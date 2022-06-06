<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/liaisontw/cache-dude
 * @since             1.0.0
 * @package           Cache Dude
 *
 * @wordpress-plugin
 * Plugin Name:       Cache Dude
 * Plugin URI:        https://github.com/liaisontw/cache-dude
 * Description:       Speedup Wordpress website
 * Version:           1.0.0
 * Author:            Liaison Chang
 * Author URI:        https://github.com/liaisontw/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cache-dude
 * Domain Path:       /languages
 */

// Exit If Accessed Directly
if(!defined('ABSPATH')){
    exit;
}
 

define( 'CACHE_DUDE_VERSION', '1.0.0' );


// polldude Table Name
global $wpdb;

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path(__FILE__) . '/includes/class-cache-dude.php';
global $cache_dude;



/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-poll-dude-activator.php
 */
function cache_dude_activate_init($network_wide) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cache-dude-activator.php';
	Cache_Dude_Activator::activate($network_wide);
}

### Function: Activate Plugin
register_activation_hook( __FILE__, 'cache_dude_activate_init' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cache-dude-deactivator.php
 */
/*
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cache-dude-deactivator.php';
	Plugin_Name_Deactivator::deactivate();
}
*/






