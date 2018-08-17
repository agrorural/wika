jQuery( 'window' ).ready( function(){
	
	/*
	 * Section metabox functions
	 */
	jQuery( '.basepress_product_mb' ).change( function(){

		var product = jQuery( this ).val();
		
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
					action: 'basepress_get_product_sections',
					product: product
				},
			success: function( response ){
				var sections = jQuery( '.basepress_section_mb' );
				sections.replaceWith( response );
			}
		});
	});
	
	
	/*
	 *	Update article menu_order on Section change
	 */
	jQuery( '#basepress_section' ).on( 'change', '.basepress_section_mb', function(){
		var menuOrderEl = jQuery( '#basepress_menu_order' );
		var oldSection = menuOrderEl.attr( 'data-term' );
		var newSection = jQuery( this ).val();
		var oldMenuOrder = menuOrderEl.attr( 'data-order' );
		var newMenuOrder = jQuery( this ).find( ':selected').data( 'count' );

		var menu_order = newSection != oldSection ? newMenuOrder : oldMenuOrder;
		menuOrderEl.attr( 'value', menu_order );
	});
});