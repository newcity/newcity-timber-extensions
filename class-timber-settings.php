<?php

/**
 *
 * Settings for Timber in NewCity projects
 *
 * @since      0.1.0
 *
 * @package    NewCity_Timber
 * @author     Jesse Janowiak <jesse@insidenewcity.com>
 */

class NC_TimberSettings {

    public function __construct() {
        add_filter( 'timber/cache/location', array( $this, 'custom_change_twig_cache_dir' ) );
        add_action( 'after_setup_theme', array( $this, 'set_templates_directory' ) );
        $this->enable_timber_cache();
    }

    /**
    * Change Timber's cache folder.
    * We want to use wp-content/uploads/timber-cache
    */
    public function custom_change_twig_cache_dir() {
        return WP_CONTENT_DIR . '/uploads/timber-cache';
    }

    public function enable_timber_cache() {
        Timber::$cache = true;
    }


    // Finds all twig files located in $template_dirs directories, including
    // subdirectories
	function set_templates_directory() {
        $basepath = get_stylesheet_directory() . '/';
        $template_dirs = array(
            'templates',
            'partials',
        );

        $path_list = array();

        foreach ($template_dirs as $subdir) {
            $subdirs = get_subdirectories($basepath . $subdir, $basepath);
            if ($subdirs) {
                $path_list = array_merge($path_list, $subdirs);
            }
        }

        Timber::$dirname = $path_list;
    }
}