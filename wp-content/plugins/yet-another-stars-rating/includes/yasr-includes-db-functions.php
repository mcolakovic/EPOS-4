<?php
/*

Copyright 2014 Dario Curvino (email : d.curvino@tiscali.it)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit( 'You\'re not allowed to see this page' );
} // Exit if accessed directly

/**
 * Return the postmeta itemType
 *
 * @param bool|int $term_id
 *
 * @return bool|string
 */
function yasr_get_itemType($term_id=false) {
    $review_types = json_decode(YASR_SUPPORTED_SCHEMA_TYPES);

    //if term_id is not an int, use get_post_meta
    if(!is_int($term_id)) {
        $post_id = get_the_ID();
        //should be useless, just to be safe
        if (!$post_id) {
            return false;
        }
        $result = get_post_meta($post_id, 'yasr_review_type', true);
    } else {
        $result = get_term_meta($term_id, 'yasr_review_type', true);
    }

    if ($result) {
        $snippet_type = trim($result);

        //to keep compatibility with version <2.2.3
        if($snippet_type === 'Place') {
            $snippet_type = 'LocalBusiness';
        }
        //to keep compatibility with version <2.2.3
        if($snippet_type === 'Other') {
            $snippet_type = 'BlogPosting';
        }
        if (!in_array($snippet_type, $review_types, true) ) {
            $snippet_type = YASR_ITEMTYPE;
        }

    } else {
        $snippet_type = YASR_ITEMTYPE;
    }

    //to keep compatibility with version <2.2.3
    if($snippet_type === 'Place') {
        $snippet_type = 'LocalBusiness';
    }
    //to keep compatibility with version <2.2.3
    if($snippet_type === 'Other') {
        $snippet_type = 'BlogPosting';
    }

    return $snippet_type;
}