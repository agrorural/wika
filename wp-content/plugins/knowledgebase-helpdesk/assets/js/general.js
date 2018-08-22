jQuery(document).ready(function($){
	
	//UpvoteCount
    $(".kbx-like-btn").on("click", function(event){
		
		event.preventDefault();

        var data_id = $(this).attr("data-article-id");

        var currentElement = $(this);

        var elementId = 'kbx-like-pid-' + data_id;

        $.post(ajaxurl, {            
            action: 'kbx_post_like_action', 
            post_id: data_id,
                
        }, function(data) {
            var json = $.parseJSON(data);
            if( json.vote_status == 'success' )
            {
                $('#' +elementId+ ' .kbx-like-counter').html(json.votes);
                $('#' +elementId+ ' .kbx-like-counter').css("color", "green");
            }
        });
       
    });

});

jQuery(document).ready(function($){

    $('a[href="#kbx-aq-modal"]').click(function(event) {
      event.preventDefault();
      $(this).modal({
        fadeDuration: 250
      });
    });

});