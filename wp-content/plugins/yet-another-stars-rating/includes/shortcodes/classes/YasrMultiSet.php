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

/**
 * Class YasrMultiSet
 */
class YasrMultiSet extends YasrShortcode {
    /**
     * @return string | bool
     */
    public function printMultiset () {

        $this->shortcode_html = '<!-- Yasr Multi Set Shortcode-->';

        $multiset_content = YasrMultiSetData::returnMultisetContent($this->post_id, $this->set_id);

        if ($multiset_content === false) {
            $string = __('No Set Found with this ID', 'yet-another-stars-rating');
            return $this->shortcode_html . $string;
        }

        $this->shortcode_html  = '<!-- Yasr Visitor Multi Set Shortcode-->';
        $this->shortcode_html .= '<table class="yasr_table_multi_set_shortcode">';
        $this->star_readonly   = 'true';

        $this->printMultisetRows($multiset_content);

        $this->shortcode_html .= "</table>";
        $this->shortcode_html .= '<!--End Yasr Multi Set Shortcode-->';

        YasrShortcode::enqueueReadonlyAndMultisetScript();

        return $this->shortcode_html;
    }


    /**
     *
     * This function insert into $this->shortcode_html the rows of both multisets (average included)
     *
     * @param $multiset_content
     * @param bool $visitor_multiset
     *
     * @return void
     *
     */
    protected function printMultisetRows($multiset_content, $visitor_multiset=false) {

        $multiset_string = 'yasr-average-multiset-';
        if ($visitor_multiset === true) {
            $multiset_string = 'yasr-visitor-multi-set-average-';
        }

        foreach ($multiset_content as $set_content) {
            $unique_id_identifier = 'yasr-multiset-' . str_shuffle(uniqid());

            $average_rating = round($set_content['average_rating'], 1);

            $html_stars = "<div class='yasr-multiset-visitors-rater'
                                id='$unique_id_identifier' 
                                data-rater-postid='$this->post_id'
                                data-rater-setid='$this->set_id'
                                data-rater-set-field-id='$set_content[id]' 
                                data-rating='$average_rating'
                                data-rater-readonly='$this->star_readonly'>
                            </div>";

            $span_container_number_of_votes = '';
            if ($visitor_multiset === true) {
                $span_container_number_of_votes = '<span class="yasr-visitor-multiset-vote-count">'
                                                  . $set_content['number_of_votes'] .
                                                  '</span>';
            }

            $this->shortcode_html .= '<tr>
                                         <td>
                                             <span class="yasr-multi-set-name-field">' . $set_content['name'] . '</span>
                                         </td>
                                         <td>'
                                     . $html_stars . $span_container_number_of_votes .
                                     '</td>
                                     </tr>';

        } //End foreach

        //If average row should be showed
        if ($this->show_average_multiset() === true) {
            //get the average of the multiset
            $multiset_average = YasrMultiSetData::returnMultiSetAverage($this->post_id, $this->set_id, $visitor_multiset);

            //print it
            $this->shortcode_html .= $this->printAverageRowMultiSet($multiset_average, $multiset_string);
        }

    }

    /**
     * This function return the html code of the average multiset
     *
     * @since 2.1.0
     *
     * @param $multiset_average
     * @param $multiset_string
     *
     * @return string
     */
    protected function printAverageRowMultiSet($multiset_average, $multiset_string) {
        $average_txt = __("Average", "yet-another-stars-rating");
        $html_average = null;

        //Show average row
        if ($this->show_average_multiset() === true) {
            $unique_id_identifier = $multiset_string . str_shuffle(uniqid());

            $html_average = "<tr>
                                <td colspan='2' class='yasr-multiset-average'>
                                    <div class='yasr-multiset-average'>
                                        <span class='yasr-multiset-average-text'>$average_txt</span>
                                        <div class='yasr-rater-stars' id='$unique_id_identifier'
                                        data-rating='$multiset_average' data-rater-readonly='true'
                                        data-rater-starsize='24'></div>
                                    </div>
                                </td>
                            </tr>";
        }

        return $html_average;
    }

    /**
     * Return true or false if average should be displayed
     *
     * @return bool
     */
    public function show_average_multiset() {
        if ( ( $this->show_average === '' && YASR_MULTI_SHOW_AVERAGE !== 'no' ) ||
             ( $this->show_average !== '' && $this->show_average !== 'no' ) ) {
            return true;
        }
        return false;
    }

}