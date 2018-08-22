jQuery(document).ready(function($){

	$("#kbx-query").on("keyup", function(e){

		var value = $(this).val();
		var currentInputBox = $(this);

		$(".kbx-hidden-search").val( value );

		if( value.length > 3 )
		{

			var data = {
				'action': 'kbx_search_article',
				'post_key': value,
			};

			currentInputBox.addClass('searching');

			jQuery.post(ajaxurl, data, function(response) {

				var json = $.parseJSON(response);

				if( json.status == 'true' ){
					currentInputBox.siblings('#serp-dd').css('display', 'block');
					currentInputBox.siblings('#serp-dd').children(".result").html('');
					currentInputBox.siblings('#serp-dd').children(".result").html(json.list);
				}

				if( json.status == 'false' ){
					currentInputBox.siblings('#serp-dd').css('display', 'block');
					currentInputBox.siblings('#serp-dd').children(".result").html(json.list);
				}

				currentInputBox.removeClass('searching');

			});

		}
		else
		{
			currentInputBox.siblings('#serp-dd').children(".result").html("");
			currentInputBox.siblings('#serp-dd').css('display', 'none');
			currentInputBox.removeClass('searching');
		}

	});

});

jQuery(document).ready(function($){
	
	$(".kbx-fes-trigger").on("click", function(e){

		e.preventDefault();

		$(this).toggleClass("open");
		$(".kbx-fes-widget-main").toggleClass("visible");

	});

	$(".kbx-fes-widget-main .close-it").on("click", function(e){

		e.preventDefault();

		$(".kbx-fes-trigger").toggleClass("open");
		$(".kbx-fes-widget-main").toggleClass("visible");

	});

	$(".kbx-fes-search-form-submit").on("click", function(e){

		e.preventDefault();

		var value = $(".kbx-fes-search-form-input").val();

		getFewSearchResult( value );
		
	});

	$(".kbx-fes-search-form-input").on("keyup", function(e){

		e.preventDefault();

		var value = $(this).val();

		if( value.length > 3 ){
			getFewSearchResult( value );
		}

	});

	function getFewSearchResult(value)
	{
		var searchString = value;

		if( searchString.length > 3 )
		{

			var data = {
				'action': 'kbx_search_article',
				'post_key': value,
			};

			$(".search-spinner").removeClass('hidden');
			$('.search-empty').addClass("hidden");
			$('.kbx-fes-alert').addClass("hidden");

			jQuery.post(ajaxurl, data, function(response) {

				var json = $.parseJSON(response);

				if( json.status == 'true' ){
					$('.kbx-fes-search-results').css('display', 'block');
					$('.kbx-fes-search-results-ul').html('');
					$('.kbx-fes-search-results-ul').html(json.list);
				}

				if( json.status == 'false' ){
					$('.kbx-fes-search-results').css('display', 'none');
					$('.kbx-fes-search-results-ul').html('');
					$('.search-empty').removeClass("hidden");
					$('.search-empty .fes-search-terms').html(searchString);
				}

				$(".search-spinner").addClass('hidden');

			});

		}
		else
		{
			$('.kbx-fes-search-results').css('display', 'none');
			$('.kbx-fes-search-results-ul').html('');
			$('.search-empty').addClass("hidden");
			$('.kbx-fes-alert').removeClass("hidden");
			$('.kbx-fes-alert').html("");
			$('.kbx-fes-alert').html("Search string is too short!");
		}
	}
	
});