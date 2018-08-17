jQuery( document ).ready( function($){
	
	
	/*
	 * Search suggest functions
	 */
	
	var deviceHeight = screen.height;
	var minDeviceHeight = $( '.bpress-search-suggest' ).data( 'minscreen' );
	var isTouchDevice = (( 'ontouchstart' in window ) || ( navigator.MaxTouchPoints > 0 ) || ( navigator.msMaxTouchPoints > 0 ));
	var searchSuggestEnabled = $( '.bpress-search-suggest' ).length;
	var skipSearchSuggestions = isTouchDevice && ( deviceHeight < minDeviceHeight );
	var oldSearchInputValue = '';
	var timer;

	/*
	 * If there is a search suggest element declare all functionalities
	 */
	if( searchSuggestEnabled ){
		var suggetionsContainer = $('.bpress-search-suggest');
		var selection = -1;

		function delay( callback, ms ){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		};
		
		$( '.bpress-search-field').on( 'keyup', function( event ){
			//Prevent search suggestions on touch devices
			if( skipSearchSuggestions || ! event.keyCode ) return;

			event.preventDefault();

			switch( event.keyCode ){
				case 13: //Enter
				case 38: //Up
				case 40: //Down
				case 91: //Left window key
					return;
				case 27: //Esc
					suggetionsContainer.hide();
					selection = -1;
					updateSelection();
					break;
				default:
					var searchTerm = sanitize_terms( $( this ).val() );
					if( searchTerm == oldSearchInputValue ) return;
					oldSearchInputValue = searchTerm;

					if( searchTerm && searchTerm.length > 2 ){
						var product = $( this ).data( 'product' );
						basepressGetSuggestions( searchTerm, product );
					}
					else{
						$( '.bpress-search-suggest' ).html( '' ).hide();
					}

			}
		});



		/*
		 * Hide search results if clicked outside
		 */
		$( document ).mouseup( function (e){
			//Prevent search suggestions on touch devices
			if( skipSearchSuggestions ) return;
			
			// if the target of the click isn't the container nor a descendant of the container
			if( !suggetionsContainer.is( e.target ) && suggetionsContainer.has( e.target ).length === 0 ){
				selection = -1;
				updateSelection();
				suggetionsContainer.hide();
			}
		});
		
		/*
		 * Reopen search suggestions on click.
		 */
		$( '.bpress-search-field' ).click( function(){
			//Prevent search suggestions on touch devices
			if( skipSearchSuggestions ) return;
			
			var searchTerm = sanitize_terms( $( this ).val() );
			if( searchTerm && searchTerm.length > 2 && $( '.bpress-search-suggest' ).html() ){
				suggetionsContainer.show();
				return;
			}
			$( this ).keyup();
		});
		
		
		/*
		 * Handle key interaction with search results
		 */
		$( '.bpress-search' ).keydown( function( event ){
			//Prevent search suggestions on touch devices
			if( skipSearchSuggestions ) return;
			
			if( event.which != 38 && event.which != 40 && event.which != 13 ){
				return;
			}
			event.preventDefault();
			
			var lastItem = $( '.bpress-search-suggest li' ).length - 1;
			switch( event.which ){
				case 38: //Up
					selection = (selection - 1) < -1 ? selection = -1 : selection -=1;
					updateSelection();
					break;
				
				case 40: //Down
					selection = (selection + 1) > lastItem ? selection = lastItem : selection += 1;
					updateSelection();	
					break;
				
				case 13: //Enter
					if( selection != -1 ){
						var link = $( '.bpress-search-suggest li' ).eq( selection ).find( 'a' );
						link[0].click();
						break;
					}
					$( '.bpress-search-form' ).submit();
					break;
			}
			
		});
		
		/*
		 *	Submit search form if suggest more is clicked
		 */
		$( '.bpress-search-suggest' ).on( 'click', '.bpress-search-suggest-more span', function(){
			$( '.bpress-search-form' ).submit();
		});
	}//End if
	
	/*
	 * Update selection on search suggestion
	 */
	function updateSelection(){
			var els = $( '.bpress-search-suggest li' );
			els.removeClass( 'selected' );
			if( selection != -1 ){
				els.eq( selection ).addClass( 'selected' );	
			}
			
		}

	/*
	 * Sanitize the terms removing shorter than 2 char and duplicates
	 */
	function sanitize_terms( terms ){
		return terms;//TODO: choose if remove this function or not
		if( !terms ){
			return;
		}

		terms = terms.split( ' ' );
		var strippedTerms = [];
		if( $.isArray( terms )){
			$.each( terms, function( index, value ){
				if( value.length > 2 && $.inArray( value, strippedTerms ) === -1){
					strippedTerms.push( value);
				}
			});
			strippedTerms = strippedTerms.join( ' ' );
		}
		return strippedTerms || '';
	}
	
	/*
	 * Get suggestions via Ajax
	 */
	function basepressGetSuggestions( searchTerm, product ){

			$( '.bpress-search-form' ).addClass( 'searching' );

			var lang = $( '.bpress-search-form input[name="lang"]' ).val();

			$.ajax( {
				type: 'GET',
				url: basepress_vars.ajax_url,
				data: {
					action: 'basepress_smart_search',
					terms: searchTerm,
					product: product,
					lang: lang
				},
				success: function( response ){
					if( response ){
						suggetionsContainer.html( response ).show();
					}
					else{
						suggetionsContainer.html( '' ).hide();
					}
				},
				complete: function(){
					$( '.bpress-search-form' ).removeClass( 'searching' );
					delay(function(){
						logSearch( searchTerm, product );
					}, 	1000 );
				}
			} );
		}

		function logSearch( searchTerm, product ){
		  var foundPosts = suggetionsContainer.children( 'ul' ).children( 'li' ).length;
			$.ajax( {
				type: 'GET',
				url: basepress_vars.ajax_url,
				data: {
					action: 'basepress_log_ajax_search',
					terms: searchTerm,
					product: product,
					found_posts: foundPosts
				},
				success: function( response ){

				},
				complete: function(){

				}
			} );
		}
	
	
	
/* Premium Code Stripped by Freemius */


	//Count post views
	if( basepress_vars.postID ){
		$.ajax( {
			type: 'POST',
			url: basepress_vars.ajax_url,
			data: {
				action: 'basepress_update_views',
				postID: basepress_vars.postID,
				productID: basepress_vars.productID,
			}
		});
	}
});// End jQuery