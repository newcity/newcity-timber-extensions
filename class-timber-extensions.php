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
    var $_full_text;
    var $_get_excerpt;

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
            return $this->_ancestors[$count - 1];
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
    
    public function full_text( $post_id = null, $length = null, $exclude = null, $delimiter = ' &bull; ' ) {

        if ( ! $this->_full_text ) {

            if ( ! $post_id ) {
                $post_id = $this->ID;
            }

            $post = get_post( $post_id );

            $fulltext = $post->post_content;

            $all_fields = get_field_objects( $post_id );

            if ( ! $all_fields ) {
                $this->_full_text = $fulltext;
                return $fulltext;
            }

            foreach ( $all_fields as $field_name => $field ) {
                if ( 'text' === $field['type'] || 'textarea' === $field['type'] || 'wysiwyg' === $field['type'] ) {
                    if ( ! $exclude || ! in_array( $field['name'], $exclude ) ) {
                        $fulltext = $fulltext . $field['value'] . $delimiter;
                    }
                }

                if ( $length && str_word_count( $fulltext, 0 ) >= $length ) {
                    $fulltext = apply_filters( 'the_content', $fulltext );
                    $fulltext = str_replace( ']]>', ']]&gt;', $fulltext );
                    $this->_full_text = $fulltext;
                    return $fulltext;
                }
            }
            $this->_full_text = $fulltext;
            return $fulltext;
        }
        return $this->_full_text;

	}

	public function get_excerpt( $post_id = null, $length = 22 ) {
        if ( ! $this->_get_excerpt ) {
            $exclude = [
                'name_first',
                'name_middle',
                'name_surname',
                'credentials',
                'titles',
                'address',
                'phone',
                'contact_name',
                'email',
                'office',
                'website',
                'twitter',
            ];

            if ( ! $post_id ) {
                if ( isset( $this->excerpt ) ) {
                    $this->_get_excerpt = $this->excerpt;
                    return $this->excerpt;
                }

                $post_id = $this->ID;
            }

            $transient_excerpt = get_transient( 'excerpt_' . $post_id );

            if ( empty( $transient_excerpt ) ) {
                $custom_excerpt = get_the_excerpt( $post_id );
                if ( $custom_excerpt ) {
                    $this->_get_excerpt = $custom_excerpt;
                    return $custom_excerpt;
                }

                $generated_excerpt = wp_trim_words( $this->full_text( $post_id, 22, $exclude ), $length );

                set_transient( 'excerpt_' . $post_id, $generated_excerpt, 10 );

                $this->_get_excerpt = $generated_excerpt;
                return $generated_excerpt;
            }
            $this->_get_excerpt = $transient_excerpt;
            return $transient_excerpt;
        }

        return $this->_get_excerpt;
	}

}
