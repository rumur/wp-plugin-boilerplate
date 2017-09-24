<?php
use WPApp\Plugin;
/**
 * Plugin Name:         WP Plugin Boilerplate.
 * Description:         The WP Plugin Structure based on OOP.
 * Author:              Rumur
 * Text Domain:         rumur
 * Domain Path:         /languages/
 * Version:             0.1.0
 * Requires at least:   4.0.0
 */

/**
 * Default constants.
 *
 * @since 0.1.0
 */
defined('DS') ? DS : define('DS', DIRECTORY_SEPARATOR);

/**
 * Class Autoloader.
 *
 * @since 0.1.0
 */
if ( ! class_exists('WPApp\Plugin') ) {
	require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
}
$plugin = Plugin::run( __FILE__ );
