( function( api ) {

	// Extends our custom "sparklestore" section.
	api.sectionConstructor['sparklestore'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

	api.sectionConstructor['sparkle-store-upgrade-section'] = api.Section.extend({

        // No events for this type of section.
        attachEvents: function () {},

        // Always make the section active.
        isContextuallyActive: function () {
            return true;
        }
    });

} )( wp.customize );
