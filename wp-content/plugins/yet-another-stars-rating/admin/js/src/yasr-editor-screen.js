// executes this when the DOM is ready
document.addEventListener('DOMContentLoaded', function(event) {

    //check if is gutenberg editor
    let yasrIsGutenbergEditor = document.body.classList.contains('block-editor-page');

    if(yasrIsGutenbergEditor !== true) {
        //show overall rating in the metabox
        yasrPrintMetaBoxOverall();

        //run shortcode creator
        shortcodeCreator();
    }

    //always show snippet or multi set
    yasrPrintMetaBoxBelowEditor();

}); //end document ready

document.getElementById('yasr-metabox-below-editor-select-schema').addEventListener('change',
    function() {
        let selectedItemtype = this.value;
        yasrSwitchItemTypeDiv(selectedItemtype);
    }
);


/**
 * Print the stars for top right metabox
 *
 * @return void;
 */
function yasrPrintMetaBoxOverall() {

    //Convert string to number
    let overallRating = parseFloat(document.getElementById('yasr-overall-rating-value').value);

    raterJs({
        starSize: 32,
        step: 0.1,
        showToolTip: false,
        rating: overallRating,
        readOnly: false,
        element: document.getElementById("yasr-rater-overall"),
        rateCallback: function rateCallback(rating, done) {

            rating = rating.toFixed(1);
            rating = parseFloat(rating);

            //update hidden field
            document.getElementById('yasr-overall-rating-value').value = rating;

            this.setRating(rating);

            let yasrOverallString = 'You\'ve rated';

            document.getElementById('yasr_overall_text').textContent = yasrOverallString + ' ' + rating;

            done();
        }
    });

}

/**
 * Print metabox below editor
 * At the page load, show Schema.org option
 */
function yasrPrintMetaBoxBelowEditor () {
    // When click on main tab hide multi set content
    jQuery('#yasr-metabox-below-editor-structured-data-tab').on("click", function (e) {

        //prevent click on link jump to the top
        e.preventDefault();

        jQuery('.yasr-nav-tab').removeClass('nav-tab-active');
        jQuery('#yasr-metabox-below-editor-structured-data-tab').addClass('nav-tab-active');

        jQuery('.yasr-metabox-below-editor-content').hide();
        jQuery('#yasr-metabox-below-editor-structured-data').show();

    });

    //When click on multi set tab hide snippet content
    jQuery('#yasr-metabox-below-editor-multiset-tab').on("click", function (e) {

        //prevent click on link jump to the top
        e.preventDefault();

        jQuery('.yasr-nav-tab').removeClass('nav-tab-active');
        jQuery('#yasr-metabox-below-editor-multiset-tab').addClass('nav-tab-active');

        jQuery('.yasr-metabox-below-editor-content').hide();
        jQuery('#yasr-metabox-below-editor-multiset').show();

    });

    let selectedItemtype = document.getElementById('yasr-metabox-below-editor-select-schema').value;

    if(document.getElementById('yasr-editor-multiset-container') !== null) {
        yasrAdminMultiSet();
    }

    yasrSwitchItemTypeDiv(selectedItemtype);
}

function yasrSwitchItemTypeDiv (itemType) {
    if(itemType === 'Product') {
        //show main div
        document.getElementById('yasr-metabox-info-snippet-container').style.display = '';
        //show product
        document.getElementById('yasr-metabox-info-snippet-container-product').style.display = '';

        //hide all child divs
        document.getElementById('yasr-metabox-info-snippet-container-localbusiness').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-recipe').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-software').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-book').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-movie').style.display = 'none';

    }
    else if(itemType === 'LocalBusiness') {
        //show main div
        document.getElementById('yasr-metabox-info-snippet-container').style.display = '';
        //show localbusiness
        document.getElementById('yasr-metabox-info-snippet-container-localbusiness').style.display = '';
        //hide all child
        document.getElementById('yasr-metabox-info-snippet-container-product').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-recipe').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-software').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-book').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-movie').style.display = 'none';

    }
    else if(itemType === 'Recipe') {
        //show main div
        document.getElementById('yasr-metabox-info-snippet-container').style.display = '';
        //show recipe
        document.getElementById('yasr-metabox-info-snippet-container-recipe').style.display = '';
        //hide all child
        document.getElementById('yasr-metabox-info-snippet-container-localbusiness').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-product').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-software').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-book').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-movie').style.display = 'none';
    }
    else if(itemType === 'SoftwareApplication') {
        //show main div
        document.getElementById('yasr-metabox-info-snippet-container').style.display = '';
        //show Software Application
        document.getElementById('yasr-metabox-info-snippet-container-software').style.display = '';

        //hide all childs
        document.getElementById('yasr-metabox-info-snippet-container-recipe').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-localbusiness').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-product').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-book').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-movie').style.display = 'none';
    }
    else if(itemType === 'Book') {
        //show main div
        document.getElementById('yasr-metabox-info-snippet-container').style.display = '';
        //show Book
        document.getElementById('yasr-metabox-info-snippet-container-book').style.display = '';

        //hide all childs
        document.getElementById('yasr-metabox-info-snippet-container-recipe').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-localbusiness').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-product').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-software').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-movie').style.display = 'none';

    }

    else if(itemType === 'Movie') {
        //show main div
        document.getElementById('yasr-metabox-info-snippet-container').style.display = '';
        //show Book
        document.getElementById('yasr-metabox-info-snippet-container-movie').style.display = '';

        //hide all childs
        document.getElementById('yasr-metabox-info-snippet-container-recipe').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-localbusiness').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-product').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-software').style.display = 'none';
        document.getElementById('yasr-metabox-info-snippet-container-book').style.display = 'none';

    }

    else {
        document.getElementById('yasr-metabox-info-snippet-container').style.display = 'none';
    }
}

/****** Yasr Metabox Multiple Rating ******/

function yasrAdminMultiSet() {

    let divContainer = document.getElementById('yasr-editor-multiset-container');
    let nMultiSet    = parseInt(divContainer.getAttribute('data-nmultiset'));
    let setId        = parseInt(divContainer.getAttribute('data-setid'));
    let postId       = parseInt(divContainer.getAttribute('data-postid'));

    yasrPrintAdminMultiSet(setId, postId, nMultiSet);

    if (nMultiSet > 1) {
        jQuery('#yasr-button-select-set').on("click", function () {

            //get the multi data
            //overwrite setID
            setId = jQuery('#select_set').val();

            jQuery("#yasr-loader-select-multi-set").show();

            yasrPrintAdminMultiSet(setId, postId, nMultiSet);

            return false; // prevent default click action from happening!
        });

    }

}

//print the table
function yasrPrintAdminMultiSet(setId, postid, nMultiSet) {

    const data_id = {
        action: 'yasr_send_id_nameset',
        set_id:  setId,
        post_id: postid
    };

    jQuery.post(ajaxurl, data_id, function (response) {
        //Hide the loader near the select only if more multiset are used
        if (nMultiSet > 1) {
            document.getElementById('yasr-loader-select-multi-set').style.display = 'none';
        }

        var yasrMultiSetValue = JSON.parse(response);

        var content = '';

        for (var i = 0; i < yasrMultiSetValue.length; i++) {
            var valueName   = yasrMultiSetValue[i]['name'];
            var valueRating = yasrMultiSetValue[i]['average_rating'];
            var valueID     = yasrMultiSetValue[i]['id'];

            content += '<tr>';
            content += '<td>' + valueName + '</td>';
            content += '<td><div class="yasr-multiset-admin" id="yasr-multiset-admin-' + valueID + '" data-rating="'
                + valueRating + '" data-multi-idfield="' + valueID + '"></div>';
            content += '<span id="yasr-loader-multi-set-field-' + valueID + '" style="display: none">';
            content += '<img src="' + yasrCommonData.loaderHtml + '" alt="yasr-loader"></span>';
            content += '</span>';
            content += '</td>';
            content += '</tr>';

            var table = document.getElementById('yasr-table-multi-set-admin');

            table.innerHTML = content;

        }

        //Show the text "Choose a vote"
        document.getElementById('yasr-multi-set-admin-choose-text').style.display = 'block';

        //Set rater for divs
        yasrSetRaterAdminMulti(setId);

        //Show shortcode
        document.getElementById('yasr-multi-set-admin-explain').style.display = 'block';

        document.getElementById('yasr-multi-set-admin-explain-with-id-readonly').innerHTML = '<strong>[yasr_multiset setid=' + setId + ']</strong>';
        document.getElementById('yasr-multi-set-admin-explain-with-id-visitor').innerHTML = '<strong>[yasr_visitor_multiset setid=' + setId + ']</strong>';

    });

    return false; // prevent default click action from happening!

}

//Rater for multiset
function yasrSetRaterAdminMulti(setId) {

    //update hidden field
    document.getElementById('yasr-multiset-id').value = setId;

    let yasrMultiSetAdmin = document.getElementsByClassName('yasr-multiset-admin');

    //an array with all the ratings objects
    let ratingArray = [];
    let ratingValue = false;

    for (let i = 0; i < yasrMultiSetAdmin.length; i++) {

        (function (i) {

            let htmlId = yasrMultiSetAdmin.item(i).id;
            let elem = document.getElementById(htmlId);

            let setIdField       = parseInt(elem.getAttribute('data-multi-idfield'));
            let ratingOnLoad     = parseInt(elem.getAttribute('data-rating'));

            let ratingObjectOnLoad = {
                field: setIdField,
                rating: ratingOnLoad
            };

            //creating rating array
            ratingArray.push(ratingObjectOnLoad);

            raterJs({
                starSize: 32,
                step: 0.5,
                showToolTip: false,
                readOnly: false,
                element: elem,

                rateCallback: function rateCallback(rating, done) {
                    rating = rating.toFixed(1);
                    //Be sure is a number and not a string
                    rating = parseFloat(rating);
                    this.setRating(rating); //Set the rating

                    //loop the array with existing rates
                    for (let j = 0; j < ratingArray.length; j++) {
                        //if the field of the array is the same of the rated field, get the rating
                        if(ratingArray[j].field === setIdField) {
                            //the selected rating overwrite the existing one
                            ratingArray[j].rating = rating;
                        }
                    }

                    ratingValue = JSON.stringify(ratingArray);

                    //update hidden field
                    document.getElementById('yasr-multiset-author-votes').value = ratingValue;

                    done();
                }

            });

        })(i);

    } //End for

}//End function

/****** End Yasr Metabox Multple Rating  ******/

function shortcodeCreator () {
    var data = {
        action: 'yasr_create_shortcode'
    };

    jQuery.get(ajaxurl, data, function (button_content) {
        jQuery(button_content).appendTo('body').hide();
        yasrShortcodeCreator();
    });
}

/****** Yasr Ajax Page ******/
// When click on chart hide tab-main and show tab-charts

function yasrShortcodeCreator() {

    let nMultiSet = false

    if(document.getElementById('yasr-editor-multiset-container') !== null) {
        nMultiSet = true;
    }

    const linkDoc = document.getElementById('yasr-tinypopup-link-doc');

    // When click on main tab hide tab-main and show tab-charts
    jQuery('#yasr-link-tab-main').on("click", function () {
        jQuery('.yasr-nav-tab').removeClass('nav-tab-active');
        jQuery('#yasr-link-tab-main').addClass('nav-tab-active');

        jQuery('.yasr-content-tab-tinymce').hide();
        jQuery('#yasr-content-tab-main').show();

        linkDoc.setAttribute('href', 'https://yetanotherstarsrating.com/yasr-basics-shortcode/?utm_source=wp-plugin&utm_medium=tinymce-popup&utm_campaign=yasr_editor_screen');
    });

    jQuery('#yasr-link-tab-charts').on("click", function () {

        jQuery('.yasr-nav-tab').removeClass('nav-tab-active');
        jQuery('#yasr-link-tab-charts').addClass('nav-tab-active');

        jQuery('.yasr-content-tab-tinymce').hide();
        jQuery('#yasr-content-tab-charts').show();

        linkDoc.setAttribute('href', 'https://yetanotherstarsrating.com/yasr-rankings/?utm_source=wp-plugin&utm_medium=tinymce-popup&utm_campaign=yasr_editor_screen');

    });

    // Add shortcode for overall rating
    jQuery('#yasr-overall').on("click", function () {
        jQuery('#yasr-overall-choose-size').toggle('slow');
    });

    //Add shortcode for visitors rating
    jQuery('#yasr-visitor-votes').on("click", function () {
        jQuery('#yasr-visitor-choose-size').toggle('slow');
    });

    jQuery('.yasr-tinymce-shortcode-buttons').on("click", function () {
        let shortcode = this.getAttribute('data-shortcode');

        if (tinyMCE.activeEditor == null) {
            //this is for tinymce used in text mode
            jQuery("#content").append(shortcode);
        } else {
            // inserts the shortcode into the active editor
            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
        }

        // close
        tb_remove();
        //jQuery('#yasr-tinypopup-form').dialog('close');

    });

    if (nMultiSet === true) {
        //Add shortcode for multiple set
        jQuery('#yasr-insert-multiset-select').on("click", function () {
            var setType     = jQuery("input:radio[name=yasr_tinymce_pick_set]:checked").val();
            var visitorSet  = jQuery("#yasr-allow-vote-multiset").is(':checked');
            var showAverage = jQuery("#yasr-hide-average-multiset").is(':checked');
            let shortcode;

            if (!visitorSet) {
                shortcode = '[yasr_visitor_multiset setid=';
            } else {
                shortcode = '[yasr_multiset setid=';
            }

            shortcode += setType;

            if (showAverage) {
                shortcode += ' show_average=\'no\'';
            }

            shortcode += ']';

            // inserts the shortcode into the active editor
            if (tinyMCE.activeEditor == null) {
                //this is for tinymce used in text mode
                jQuery("#content").append(shortcode);
            } else {
                // inserts the shortcode into the active editor
                tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
            }

            // close
            tb_remove();
        });

    } //End if

} //End function

/****** End YAsr Ajax page ******/