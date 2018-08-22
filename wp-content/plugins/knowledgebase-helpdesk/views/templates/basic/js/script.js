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