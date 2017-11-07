<?php

/**
 *
 * Tools for use with Twig
 *
 * @since      0.1.0
 *
 * @package    NewCity_Timber
 * @author     Jesse Janowiak <jesse@insidenewcity.com>
 */

class NC_Twig_Tools {

    function __construct() {
        add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
    }

    function add_to_twig( $twig ) {
		/* this is where you can add your own fuctions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter( new Twig_SimpleFilter( 'quoteswap', array( $this, 'swap_quotes' ) ) );
		$twig->addFilter( new Twig_SimpleFilter( 'print_r', array( $this, 'print_r_custom' ) ) );
		return $twig;
    }
    
    // Filter to replace default print_r
    public function print_r_custom($arr, $pre = true ) {
        if ( function_exists( 'print_r_custom' ) ) {
            return print_r_custom( $arr, $pre );
        }

        if ( $pre ) {
            return '<pre>' . print_r($arr, true) . '</pre>';
        }
        return print_r($arr, true);
    }


    // Replace double curly quotes with single curly quotes or vice versa.
    // Primarily for nesting quotes.
    function swap_quotes($string, $mode = 'd_to_s')
    {
        if ( function_exists( 'swap_quotes' ) ) {
            return swap_quotes( $string, $mode );
        }

        if ($mode == 's_to_d') {
            return str_replace('’', '”', str_replace('‘', '“', $string));
        }
    
        return str_replace('”', '’', str_replace('“', '‘', $string));
    }


}