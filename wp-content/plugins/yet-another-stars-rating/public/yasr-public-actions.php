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

/***** Adding javascript and css *****/
add_action('wp_enqueue_scripts', 'yasr_add_scripts');
function yasr_add_scripts() {

    wp_enqueue_style(
        'yasrcss',
        YASR_CSS_DIR_INCLUDES . 'yasr.css',
        false,
        YASR_VERSION_NUM
    );

    //Run after default css are loaded
    do_action('yasr_add_front_script_css');

    if (YASR_CUSTOM_CSS_RULES) {
        wp_add_inline_style(
            'yasrcss',
            YASR_CUSTOM_CSS_RULES
        );
    }

    do_action('yasr_add_front_script_js');

}