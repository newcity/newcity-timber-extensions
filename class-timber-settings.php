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
        // add_filter( 'timber/cache/location', array( $this, 'custom_change_twig_cache_dir' ) );
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
        $parent_basepath = get_template_directory() . '/';
        $current_basepath = get_stylesheet_directory() . '/';
        $template_dirs = array(
            'templates',
            'partials',
            'twig-components'
        );

        $path_list = array();

        foreach ($template_dirs as $subdir) {
            $subdirs = $this->get_subdirectories($current_basepath . $subdir, $current_basepath);
            if ($subdirs) {
                $path_list = array_merge($path_list, $subdirs);
            }
        }

        if ( $parent_basepath !== $current_basepath ) {
            foreach ($template_dirs as $subdir) {
                $subdirs = $this->get_subdirectories($parent_basepath . $subdir, $parent_basepath);
                if ($subdirs) {
                    $path_list = array_merge($path_list, $subdirs);
                }
            }
        }

        Timber::$dirname = $path_list;
    }

    public function get_subdirectories($pathstring, $root)
    {
        // Utility function to get subdirectories of a given set of directories as an array, stripped of the root path (used to generate template locations)

        if ( function_exists( 'get_subdirectories' ) ) {
            return get_subdirectories( $pathstring, $root );
        }

        $path = realpath($pathstring);
    
        if (file_exists($path)) {
            $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);

            $found_paths = array();

            foreach ($objects as $name => $object) {
                if ($object->getFilename() == '.') {
                    $path_parts = explode($root, $object->getPath(), 2);

                    unset($path_parts[0]);
                    $path_stripped = implode('', $path_parts);

                    $found_paths[] = $path_stripped;
                }
            }

            return $found_paths;
        }
        return false;
    }
}