jQuery(document).ready(function($){

    /**
     * Repeater Fields
    */
	function sparklestore_refresh_repeater_values(){
		$(".sparklestore-repeater-field-control-wrap").each(function(){			
			var values = []; 
			var $this = $(this);			
			$this.find(".sparklestore-repeater-field-control").each(function(){
			var valueToPush = {};
			$(this).find('[data-name]').each(function(){
				var dataName = $(this).attr('data-name');
				var dataValue = $(this).val();
				valueToPush[dataName] = dataValue;
			});
			values.push(valueToPush);
			});
			$this.next('.sparklestore-repeater-collector').val(JSON.stringify(values)).trigger('change');
		});
	}

    $('#customize-theme-controls').on('click','.sparklestore-repeater-field-title',function(){
        $(this).next().slideToggle();
        $(this).closest('.sparklestore-repeater-field-control').toggleClass('expanded');
    });
    $('#customize-theme-controls').on('click', '.sparklestore-repeater-field-close', function(){
    	$(this).closest('.sparklestore-repeater-fields').slideUp();;
    	$(this).closest('.sparklestore-repeater-field-control').toggleClass('expanded');
    });
    $("body").on("click",'.sparklestore-add-control-field', function(){
		var $this = $(this).parent();
		if(typeof $this != 'undefined') {
            var field = $this.find(".sparklestore-repeater-field-control:first").clone();
            if(typeof field != 'undefined'){                
                field.find("input[type='text'][data-name]").each(function(){
                	var defaultValue = $(this).attr('data-default');
                	$(this).val(defaultValue);
                });

                field.find("textarea[data-name]").each(function(){
                	var defaultValue = $(this).attr('data-default');
                	$(this).val(defaultValue);
                });

                field.find("select[data-name]").each(function(){
                	var defaultValue = $(this).attr('data-default');
                	$(this).val(defaultValue);
                });

                field.find(".sparklestore-icon-list").each(function(){
                    var defaultValue = $(this).next('input[data-name]').attr('data-default');
                    $(this).next('input[data-name]').val(defaultValue);
                    $(this).prev('.sparklestore-selected-icon').children('i').attr('class','').addClass(defaultValue);
                    
                    $(this).find('li').each(function(){
                        var icon_class = $(this).find('i').attr('class');
                        if(defaultValue == icon_class ){
                            $(this).addClass('icon-active');
                        }else{
                            $(this).removeClass('icon-active');
                        }
                    });
                });

				field.find('.sparklestore-fields').show();

				$this.find('.sparklestore-repeater-field-control-wrap').append(field);

                field.addClass('expanded').find('.sparklestore-repeater-fields').show(); 
                $('.accordion-section-content').animate({ scrollTop: $this.height() }, 1000);
                sparklestore_refresh_repeater_values();
            }

		}
		return false;
	 });
	
	$("#customize-theme-controls").on("click", ".sparklestore-repeater-field-remove",function(){
		if( typeof	$(this).parent() != 'undefined'){
			$(this).closest('.sparklestore-repeater-field-control').slideUp('normal', function(){
				$(this).remove();
				sparklestore_refresh_repeater_values();
			});			
		}
		return false;
	});

	$("#customize-theme-controls").on('keyup change', '[data-name]',function(){
		 sparklestore_refresh_repeater_values();
		 return false;
	});


	$('body').on('click', '.sparklestore-icon-list li', function(){
        var icon_class = $(this).find('i').attr('class');
        $(this).addClass('icon-active').siblings().removeClass('icon-active');
        $(this).parent('.sparklestore-icon-list').prev('.sparklestore-selected-icon').children('i').attr('class','').addClass(icon_class);
        $(this).parent('.sparklestore-icon-list').next('input').val(icon_class).trigger('change');
        sparklestore_refresh_repeater_values();
    });

    $('body').on('click', '.sparklestore-selected-icon', function(){
        $(this).next().slideToggle();
    });


    /**
     * Select Multiple Category
     */
    $('.customize-control-checkbox-multiple input[type="checkbox"]').on('change', function () {

            var checkbox_values = $(this).parents('.customize-control').find('input[type="checkbox"]:checked').map(
                function () {
                    return $(this).val();
                }
            ).get().join(',');

            $(this).parents('.customize-control').find('input[type="hidden"]').val(checkbox_values).trigger('change');

        }
    );
    

	/*Drag and drop to change order*/
	$(".sparklestore-repeater-field-control-wrap").sortable({
		orientation: "vertical",
		update: function( event, ui ) {
			sparklestore_refresh_repeater_values();
		}
	});

	/*
     * Switch On/Off Control
    */
    $('body').on('click', '.onoffswitch', function(){
        var $this = $(this);
        if($this.hasClass('switch-on')){
            $(this).removeClass('switch-on');
            $this.next('input').val('off').trigger('change')
        }else{
            $(this).addClass('switch-on');
            $this.next('input').val('on').trigger('change')
        }
    });



    /**
     * Radio Image control in customizer
     */
    // Use buttonset() for radio images.
    //$( '.customize-control-radio-image .buttonset' ).buttonset();

    // Handles setting the new value in the customizer.
    $( '.customize-control-radio-image input:radio' ).change(
        function() {
            // Get the name of the setting.
            var setting = $( this ).attr( 'data-customize-setting-link' );

            // Get the value of the currently-checked radio input.
            var image = $( this ).val();

            // Set the new value.
            wp.customize( setting, function( obj ) {

                obj.set( image );
            } );

            //set active border
            $(this).parent().siblings().removeClass('active');
            $(this).parent().addClass('active');
        }
    );


    /**
     * Image upload at widget
    */
    upload_media_image('.sparklestore-upload-button');
    delete_media_image('.sparklestore-delete-button');

    /**
     * Image upload at widget
     */
    $('body').on('click','.selector-labels label', function(){
        var $this = $(this);
        var value = $this.attr('data-val');
        $this.siblings().removeClass('selector-selected');
        $this.addClass('selector-selected');
        $this.closest('.selector-labels').next('input').val(value).trigger('change');
    });

});

(function ($) {
    jQuery(document).ready(function ($) {
        $('.sparkle-customizer').on( 'click', function( evt ){
            evt.preventDefault();
            section = $(this).data('section');
            if ( section ) {
                wp.customize.section( section ).focus();
            }
        });
    });
})(jQuery);


/**
 * Image upload functions
*/
var selector;

function upload_media_image(selector){
    
    // ADD IMAGE LINK
    jQuery('body').on( 'click', selector , function( event ){
    
        event.preventDefault();

        var imgContainer = jQuery(this).closest('.attachment-media-view').find( '.thumbnail-image'),
        placeholder      = jQuery(this).closest('.attachment-media-view').find( '.placeholder'),
        imgIdInput       = jQuery(this).siblings('.upload-id');

        // Create a new media frame
        frame = wp.media({
            title: 'Select or Upload Image',
            button: {
            text: 'Use Image'
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected in the media frame...
        frame.on( 'select', function() {

            // Get media attachment details from the frame state
            var attachment = frame.state().get('selection').first().toJSON();

            // Send the attachment URL to our custom image input field.
            imgContainer.html( '<img src="'+attachment.url+'" style="max-width:100%;"/>' );
            placeholder.addClass('hidden');
            imgIdInput.val( attachment.url ).trigger('change');
        });

        // Finally, open the modal on click
        frame.open();
    
    });
}

function delete_media_image(selector){
    // DELETE IMAGE LINK
    jQuery('body').on( 'click', selector, function( event ){
        event.preventDefault();
        var imgContainer = jQuery(this).closest('.attachment-media-view').find( '.thumbnail-image'),
        placeholder = jQuery(this).closest('.attachment-media-view').find( '.placeholder'),
        imgIdInput = jQuery(this).siblings('.upload-id');

        // Clear out the preview image
        imgContainer.find('img').remove();
        placeholder.removeClass('hidden');

        // Delete the image id from the hidden input
        imgIdInput.val( '' ).trigger('change');

    });
}