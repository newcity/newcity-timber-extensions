<?php

/**
 *
 * Timber class that adds functions to TimberPost
 *
 * @since      0.1.0
 *
 * @package    NewCity_Timber
 * @author     Jesse Janowiak <jesse@insidenewcity.com>
 */

class NC_TimberPost extends TimberPost {
    var $_newcity_info;

    public function newcity_info() {
        return 'This site was created by NewCity';
    }

}