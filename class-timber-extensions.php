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
    var $_breadcrumbs;
    var $_ancestors;
    var $_top_ancestor;

    public function newcity_info() {
        return 'This site was created by NewCity';
    }

    // Returns an array of ancestor post IDs,
    // OR if $pos has a value, a specific ancestor ID
    // $pos (int) = the number of steps removed from the current page
    // if $pos == 0, return the top level ancestor
    public function ancestors( $pos = false ) {
        if ( ! $this->_ancestors ) {
            if ( is_post_type_hierarchical( $this->post_type ) ) {
                $this->_ancestors = get_post_ancestors( $this->ID );
            } else {
                $this->_ancestors = false;
                return false;
            }
        }

        $count = count( $this->_ancestors );
        
        if ( $count < 1 || $pos < 0 ) {
            return false;
        }

        if ( $pos === 0 || $pos > $count ) {
            return $this->_ancestors[0];
        }

        if ( gettype( $pos ) === 'integer' ) {
            return $this->_ancestors[ $count - $pos ];
        }

        return $this->_ancestors;
    }

    public function top_ancestor() {
        if ( ! $this->_top_ancestor ) {
            $this->_top_ancestor = $this->ancestors(0);
        }
        return $this->_top_ancestor;
    }

    public function breadcrumbs() {
        if ( ! $this->_breadcrumbs ) {
            $breadcrumbs_list = array();
            if ( ! $this->ancestors() ) {
                return false;
            }

            foreach ( $this->ancestors() as $ancestor ) {
                if ( is_string( $ancestor ) ) {
                    $post_type_obj = get_post_type_object( $ancestor );
                    $name = $post_type_obj->label;
                    $id = null;
                    $link = '/' . $ancestor . '/';
                } else {
                    $name = get_the_title( $ancestor );
                    $id = $ancestor;
                    $link = get_page_link( $ancestor );
                }
                array_unshift(
                    $breadcrumbs_list, array(
                        'title' => $name,
                        'ID' => $id,
                        'url' => $link,
                    )
                );
            }

            $this->_breadcrumbs = $breadcrumbs_list;
        }
		return $this->_breadcrumbs;
	}

}