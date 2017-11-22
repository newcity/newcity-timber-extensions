<?php
/**
 * NewCity Custom Plugin Features
 *
 *
 * @since             0.1.0
 * @package           NewCity_Timber
 *
 * @wordpress-plugin
 * Plugin Name:       NewCity Timber Extensions
 * Plugin URI: https://github.com/newcity/newcity-timber-extensions
 * Description:       Tools to extend Timber's default tools
 * Version:           0.2.3-dev
 * Author:            NewCity  <geeks@insidenewcity.com>
 * Author URI:        http://insidenewcity.com
 * License:           NONE
 */


 // If this file is called directly, abort.
 if ( ! defined( 'WPINC' ) ) {
     die;
 }
 
require_once( dirname( __FILE__ ) . '/class-timber-settings.php');
require_once( dirname( __FILE__ ) . '/class-twig-tools.php');

function init_nc_timber_extensions() {
    if ( class_exists( 'TimberPost' ) ) {
        require_once( dirname( __FILE__ ) . '/class-timber-extensions.php');
        // Custom hook for other plugins wanting to use these classes
        do_action( 'nc_timber_classes_loaded');
    }
}

function init_nc_timber_settings() {
    if ( class_exists( 'TimberPost' ) ) {
        $twig_filters = new NC_Twig_Tools();
        $timber_settings = new NC_TimberSettings();

    }
}


add_action( 'plugins_loaded', 'init_nc_timber_extensions' );
add_action( 'plugins_loaded', 'init_nc_timber_settings' );
