/****** Yasr Settings Page ******/

//get active Tab
let activeTab;
let tabClass = document.getElementsByClassName('nav-tab-active');

if(tabClass.length > 0){
    activeTab = document.getElementsByClassName('nav-tab-active')[0].id;
}

//-------------------General Settings Code---------------------
if (activeTab === 'general_settings') {
    let autoInsertEnabled = document.getElementById('yasr_auto_insert_switch').checked;
    let starsTitleEnabled = document.getElementById('yasr-general-options-stars-title-switch').checked;

    if (autoInsertEnabled === false) {
        jQuery('.yasr-auto-insert-options-class').prop('disabled', true);
    }

    if(starsTitleEnabled === false) {
        jQuery('.yasr-stars-title-options-class').prop('disabled', true);
    }

    //First Div, for auto insert
    document.getElementById('yasr_auto_insert_switch').addEventListener('change', function() {
        if (this.checked) {
            jQuery('.yasr-auto-insert-options-class').prop('disabled', false);
        } else {
            jQuery('.yasr-auto-insert-options-class').prop('disabled', true);
        }
    });

    //Second Div, for stars title
    document.getElementById('yasr-general-options-stars-title-switch').addEventListener('change', function() {
        if (this.checked) {
            jQuery('.yasr-stars-title-options-class').prop('disabled', false);
        } else {
            jQuery('.yasr-stars-title-options-class').prop('disabled', true);
        }
    });


    document.getElementById('yasr-settings-custom-texts').addEventListener('click', function() {
        document.getElementById('yasr-settings-custom-text-before-overall').value = 'Our Score';
        document.getElementById('yasr-settings-custom-text-before-visitor').value = 'Click to rate this post!';
        document.getElementById('yasr-settings-custom-text-after-visitor').value  = '[Total: %total_count% Average: %average%]';
        document.getElementById('yasr-settings-custom-text-rating-saved').value   = 'Rating saved!';
        document.getElementById('yasr-settings-custom-text-rating-updated').value = 'Rating updated!';
        document.getElementById('yasr-settings-custom-text-must-sign-in').value   = 'You must sign in to vote';
        document.getElementById('yasr-settings-custom-text-already-rated').value  = 'You have already voted for this article with %rating%';
    });


} //End if general settings

if (activeTab === 'style_options') {
    wp.codeEditor.initialize(
        document.getElementById('yasr_style_options_textarea'),
        yasr_cm_settings
    );

    jQuery('#yasr-color-scheme-preview-link').on('click', function () {
        jQuery('#yasr-color-scheme-preview').toggle('slow');
        return false; // prevent default click action from happening!
    });

    wp.hooks.doAction('yasrStyleOptions');

}

//--------------Multi Sets Page ------------------
if (activeTab === 'manage_multi') {
    let nMultiSet = parseInt(document.getElementById('n-multiset').value);

    jQuery('#yasr-multi-set-doc-link').on('click', function () {
        jQuery('#yasr-multi-set-doc-box').toggle("slow");
    });

    jQuery('#yasr-multi-set-doc-link-hide').on('click', function () {
        jQuery('#yasr-multi-set-doc-box').toggle("slow");
    });

    if (nMultiSet === 1) {
        var counter = jQuery("#yasr-edit-form-number-elements").attr('value');

        counter++;

        jQuery("#yasr-add-field-edit-multiset").on('click', function () {
            if (counter > 9) {
                jQuery('#yasr-element-limit').show();
                jQuery('#yasr-add-field-edit-multiset').hide();
                return false;
            }

            var newTextBoxDiv = jQuery(document.createElement('tr'));
            newTextBoxDiv.html('<td colspan="2">Element #' + counter + ' <input type="text" name="edit-multi-set-element-' + counter + '" value="" ></td>');
            newTextBoxDiv.appendTo("#yasr-table-form-edit-multi-set");
            counter++;
        });


    } //End if ($n_multi_set == 1)

    else if (nMultiSet > 1) {

        //If more then 1 set is used...
        jQuery('#yasr-button-select-set-edit-form').on("click", function () {

            var data = {
                action: 'yasr_get_multi_set',
                set_id: jQuery('#yasr_select_edit_set').val()
            };

            jQuery.post(ajaxurl, data, function (response) {
                jQuery('#yasr-multi-set-response').show();
                jQuery('#yasr-multi-set-response').html(response);
            });

            return false; // prevent default click action from happening!

        });

        jQuery(document).ajaxComplete(function () {
            var counter = jQuery("#yasr-edit-form-number-elements").attr('value');
            counter++;

            jQuery("#yasr-add-field-edit-multiset").on('click', function () {
                if (counter > 9) {
                    jQuery('#yasr-element-limit').show();
                    jQuery('#yasr-add-field-edit-multiset').hide();
                    return false;
                }
                var newTextBoxDiv = jQuery(document.createElement('tr'));
                newTextBoxDiv.html('<td colspan="2">Element #' + counter + ' <input type="text" name="edit-multi-set-element-' + counter + '" value="" ></td>');
                newTextBoxDiv.appendTo("#yasr-table-form-edit-multi-set");
                counter++;
            });

        });

    } //End if ($n_multi_set > 1)

} //end if active_tab=='manage_multi'

if (activeTab === 'migration_tools') {
    jQuery('#yasr-import-ratemypost-submit').on('click', function () {

        //show loader on click
        document.getElementById('yasr-import-ratemypost-answer').innerHTML = '<img src="'
            + yasrCommonData.loaderHtml + '"</img>';

        var nonce = document.getElementById('yasr-import-rmp-nonce').value;

        var data = {
            action: 'yasr_import_ratemypost',
            nonce: nonce
        };

        jQuery.post(ajaxurl, data, function (response) {
            response = JSON.parse(response);
            document.getElementById('yasr-import-ratemypost-answer').innerHTML = response;
        });

    });

    jQuery('#yasr-import-wppr-submit').on('click', function () {

        //show loader on click
        document.getElementById('yasr-import-wppr-answer').innerHTML = '<img src="'
            + yasrCommonData.loaderHtml + '"</img>';

        var nonce = document.getElementById('yasr-import-wppr-nonce').value;

        var data = {
            action: 'yasr_import_wppr',
            nonce: nonce
        };

        jQuery.post(ajaxurl, data, function (response) {
            //response = JSON.parse(response);
            document.getElementById('yasr-import-wppr-answer').innerHTML = response;
        });

    });

    jQuery('#yasr-import-kksr-submit').on('click', function () {

        //show loader on click
        document.getElementById('yasr-import-kksr-answer').innerHTML = '<img src="'
            + yasrCommonData.loaderHtml + '"</img>';

        var nonce = document.getElementById('yasr-import-kksr-nonce').value;

        var data = {
            action: 'yasr_import_kksr',
            nonce: nonce
        };

        jQuery.post(ajaxurl, data, function (response) {
            //response = JSON.parse(response);
            document.getElementById('yasr-import-kksr-answer').innerHTML = response;
        });

    });

    //import multi rating
    jQuery('#yasr-import-mr-submit').on('click', function () {

        //show loader on click
        document.getElementById('yasr-import-mr-answer').innerHTML = '<img src="'
            + yasrCommonData.loaderHtml + '"</img>';

        var nonce = document.getElementById('yasr-import-mr-nonce').value;

        var data = {
            action: 'yasr_import_mr',
            nonce: nonce
        };

        jQuery.post(ajaxurl, data, function (response) {
            //response = JSON.parse(response);
            document.getElementById('yasr-import-mr-answer').innerHTML = response;
        });

    });

    wp.hooks.doAction('yasr_migration_page_bottom');
}

if (activeTab === 'rankings') {
    wp.hooks.doAction('yasr_ranking_page_top');
}

/****** End Yasr Settings Page ******/