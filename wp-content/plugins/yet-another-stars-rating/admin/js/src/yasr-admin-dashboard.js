//Vote log
jQuery(document).ready(function () {

    //Log
    jQuery('.yasr-log-pagenum').on('click', function () {

        jQuery('#yasr-loader-log-metabox').show();

        var data = {
            action: 'yasr_change_log_page',
            pagenum: jQuery(this).val(),
            totalpages: jQuery('#yasr-log-total-pages').data('yasr-log-total-pages')

        };

        jQuery.post(ajaxurl, data, function (response) {
            jQuery('#yasr-loader-log-metabox').hide();
            jQuery('#yasr-log-container').html(response);
        });

    });

    jQuery(document).ajaxComplete(function (event, xhr, settings) {
        var isYasrAjaxCall = true;

        if(typeof settings.data !== 'undefined') {
            //check if the ajax call is done by yasr with action yasr_change_log_page
            isYasrAjaxCall = settings.data.search("action=yasr_change_log_page");
        }

        if (isYasrAjaxCall !== -1) {
            jQuery('.yasr-log-pagenum').on('click', function () {
                jQuery('#yasr-loader-log-metabox').show();

                var data = {
                    action: 'yasr_change_log_page',
                    pagenum: jQuery(this).val(),
                    totalpages: jQuery('#yasr-log-total-pages').data('yasr-log-total-pages')
                };

                jQuery.post(ajaxurl, data, function (response) {
                    jQuery('#yasr-log-container').html(response); //This will hide the loader gif too
                });
            });
        }

    });

});


//Vote user log
jQuery(document).ready(function () {

    //Log
    jQuery('.yasr-user-log-page-num').on('click', function () {
        jQuery('#yasr-loader-user-log-metabox').show();
        var data = {
            action: 'yasr_change_user_log_page',
            pagenum: jQuery(this).val(),
            totalpages: jQuery('#yasr-user-log-total-pages').data('yasr-log-total-pages')

        };
        jQuery.post(ajaxurl, data, function (response) {
            jQuery('#yasr-loader-log-metabox').hide();
            jQuery('#yasr-user-log-container').html(response);
        });
    });

    jQuery(document).ajaxComplete(function (event, xhr, settings) {
        var isYasrAjaxCall = true;

        if (typeof settings.data === 'undefined') {
            return;
        }

        //check if the ajax call is done by yasr with action yasr_change_log_page
        isYasrAjaxCall = settings.data.search("action=yasr_change_user_log_page");

        if (isYasrAjaxCall !== -1) {
            jQuery('.yasr-user-log-page-num').on('click', function () {
                jQuery('#yasr-loader-user-log-metabox').show();

                var data = {
                    action: 'yasr_change_user_log_page',
                    pagenum: jQuery(this).val(),
                    totalpages: jQuery('#yasr-user-log-total-pages').data('yasr-log-total-pages')
                };

                jQuery.post(ajaxurl, data, function (response) {
                    jQuery('#yasr-user-log-container').html(response); //This will hide the loader gif too
                });
            });

        }

    });

});