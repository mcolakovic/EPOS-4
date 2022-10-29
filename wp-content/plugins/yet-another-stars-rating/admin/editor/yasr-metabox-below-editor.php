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

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

$multi_set = YasrMultiSetData::returnMultiSetNames();

?>

<div>
    <div style="display: table">
        <a href="#" id="yasr-metabox-below-editor-structured-data-tab"
           class="nav-tab nav-tab-active yasr-nav-tab"><?php esc_html_e("Structured Data", 'yet-another-stars-rating'); ?></a>
        <?php if ($multi_set) {
            ?>
            <a href="#" id="yasr-metabox-below-editor-multiset-tab"
               class="nav-tab yasr-nav-tab"><?php esc_html_e("Multi Sets", 'yet-another-stars-rating'); ?></a>
            <?php
        }
        ?>

    </div>

    <div id="yasr-metabox-below-editor-structured-data" class="yasr-metabox-below-editor-content">
        <?php include(YASR_ABSOLUTE_PATH_ADMIN . '/editor/yasr-metabox-schema.php'); ?>
    </div>

    <?php

    //If multiset are used then add the second metabox
    if ($multi_set) {
        ?>
        <div id="yasr-metabox-below-editor-multiset" class="yasr-metabox-below-editor-content" style="display:none">
            <?php include(YASR_ABSOLUTE_PATH_ADMIN . '/editor/yasr-metabox-multiple-rating.php'); ?>
        </div>
        <?php
    }
    ?>

</div>