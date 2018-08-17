export default {
init() {
    // JavaScript to be fired on the home page
    var liveSearch = $(".liveSearch");
    var listGroup = $(".liveSearch .list-group");
    var searchContainer = $("#searchContainer");
    var backspaceTrigger =  $("#backspaceTrigger");
    var searchInput = $("#searchInput");
    var banner = $(".banner");
    var bannerHeight = banner.outerHeight();
    var searchInputWidth = searchInput.outerWidth();
    var searchInputLeft = searchInput.offset().left;
    let customPostTitle = '';
    let theHtml = '';
    var cboPostType = $("#cboPostType");

    let objectToSend = {
      action: 'ajax_omni_search',
      postType: 'any',
      txtKeyword: '',
      optPerPage: 5,
      max_num_pages: 0,
      response: [],
      bError: false,
      vMensaje: '',
      paged: 1,
      searchLink: '',
  };

    function getSpinner(type = "circle-notch", container = searchContainer) {
      var theSpinner = $('<span id="spinner"><i class="fas fa-' + type + ' fa-spin"></i></span>');
      
      container.append(theSpinner);
      
      // theSpinner.show(duration);

      container.find("#btnSearch").prop("disabled", true);
    }

    function deleteSpinner(container = searchContainer) {
      $("#spinner").remove();

      container.find("#btnSearch").prop("disabled", false);
    }
    
    // Media Query event handler
    function mediaSize() { 
      /* Set the matchMedia */
      if (window.matchMedia('(min-width: 768px)').matches) {
        if (wp.has_admin_bar === "1") {
          bannerHeight = banner.outerHeight() - 68;
        }else{
          bannerHeight = banner.outerHeight() - 100;
        }

        liveSearch.css("top", bannerHeight);
      } else {
      /* Reset for CSS changes – Still need a better way to do this! */
      if (wp.has_admin_bar) {
        bannerHeight = banner.outerHeight() + 2;
      }else{
        bannerHeight = banner.outerHeight() - 30;
      }
        liveSearch.css("top", bannerHeight);
      }
    }

    function getAside() {
      backspaceTrigger.fadeIn(200);
      
      liveSearch.fadeIn(200);
      liveSearch.css("position", "absolute");
      liveSearch.css("background", "white");

      mediaSize();
        
      searchInputWidth = searchInput.outerWidth();
      searchInputLeft = searchInput.offset().left;

      liveSearch.width(searchInputWidth);
      liveSearch.css("left", searchInputLeft);
    }

    function deleteAside() {
      backspaceTrigger.fadeOut(500);
      liveSearch.fadeOut(1000);
      //deleteSpinner();
    }

    function listResults(objectToSend) {
      $.ajax({
          url: wp.ajax_url,
          data: objectToSend,
          beforeSend: function() {
            // Preloading
            getSpinner();
          },
          complete: function() {
            // Preloading
            deleteSpinner();
          },
          success: function(response) {
            objectToSend = response;
            
            /* eslint-disable no-console */
            console.log(objectToSend);
            /* eslint-enable no-console */

            listGroup.empty();

            let errorMessage = objectToSend.vMensaje;

            let postTypeAlias = objectToSend.postType[0] === 'knowledgebase' ? 'en la wiki' :
                            objectToSend.postType[0] === 'forum' ? 'en los foros' :
                            objectToSend.postType[0] === 'faq' ? 'en las preguntas frecuentes' :
                            objectToSend.postType[0] === 'post' ? 'en las entradas' :
                            '';

            if (objectToSend.bError) {
              objectToSend.vMensaje = '';
              objectToSend.vMensaje += `
              <span id="notFoundSearch"><i class="fas fa-exclamation-triangle"></i></span>
              <p class="list-group-item list-group-item-action"><strong>${errorMessage} para <mark>${objectToSend.txtKeyword}</mark> ${postTypeAlias}</strong><br>Intente con otros parámetros de búsqueda...</p>`;
            
              listGroup.append(objectToSend.vMensaje);

          } else {
            for (var i = 0; i < objectToSend.response.length; i++) {

              objectToSend.response[i].html = '';
              customPostTitle = objectToSend.response[i].title;
              // customPostExcerpt = objectToSend.response[i].excerpt;

              objectToSend.response[i].html += '<a class="list-group-item list-group-item-action ' + objectToSend.response[i].post_class + '" href="' + objectToSend.response[i].permalink + '">';
              objectToSend.response[i].html += (objectToSend.response[i].post_type === 'knowledgebase') ? '<i class="far fa-list-alt"></i>' : (objectToSend.response[i].post_type === 'forum') ? '<i class="far fa-comment-alt"></i>' : (objectToSend.response[i].post_type === 'faq') ? '<i class="far fa-life-ring"></i>' : '<i class="far fa-bookmark"></i>';
              objectToSend.response[i].html += objectToSend.txtKeyword.length >= 1 ? (customPostTitle.replace(new RegExp("(" + objectToSend.txtKeyword.replace(/(\s+)/, "(<[^>]+>)*$1(<[^>]+>)*") + ")", "gi"), "<mark>$1</mark>")).replace(/(<mark>[^<>]*)((<[^>]+>)+)([^<>]*<\/mark>)/, "$1</mark>$2<mark>$4") : customPostTitle;
              objectToSend.response[i].html += '</a>';

              // objectToSend.response[i].html += '<time class="updated">' + objectToSend.response[i].date + '</time>';

              theHtml += objectToSend.response[i].html;
            }

            if( objectToSend.response.length > 2 ) {
              theHtml += '<a href="' + objectToSend.searchLink + '" class="list-group-item list-group-item-action active">Mostar más resultados <i class="fas fa-arrow-right"></i></a>';
            }

            listGroup.append(theHtml);
            theHtml = '';
            // console.log(objectToSend.response.length);
          }

            /* eslint-disable no-console */
            // console.log(objectToSend);
            /* eslint-enable no-console */
          },
          error: function(error) {
            /* eslint-disable no-console */
            console.log(error);
            /* eslint-disable no-console */
          },
      });
    }

    $(document).ready(function() {
      //listResults(objectToSend);

      backspaceTrigger.click(function(e){
        e.preventDefault();
        listGroup.empty();
        searchInput.val("");
      });

      searchInput.focus(function() {
        getAside();
      }).blur(function(){
        deleteAside();
      });

      searchInput.on('change keyup copy paste cut', function() {
        if(!this.value){
          listGroup.empty();
          searchInput.val("");
        }else{
          console.log("tiene info");
          if(this.value.length > 3){
            objectToSend.postType = cboPostType.val();
            objectToSend.txtKeyword = $(this).val();
            listResults(objectToSend);
          }
        }
      });

      cboPostType.change(function(){
        if( searchInput.val().length > 3 ) {
          objectToSend.postType = $(this).val();
          listResults(objectToSend);
          getAside();
        }
      });
      
      $(window).resize(function() {
        mediaSize();

        searchInputWidth = searchInput.outerWidth();
        searchInputLeft = searchInput.offset().left;
        
        liveSearch.width(searchInputWidth);
        liveSearch.css("left", searchInputLeft);
      });
    });
  },
  finalize() {
    // JavaScript to be fired on the home page, after the init JS
  },
};
