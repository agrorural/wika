<!doctype html>
<html @php language_attributes() @endphp>
  @include('partials.head')
  <body @php body_class() @endphp>
    @php do_action('get_header') @endphp
    @include('partials.header.front-page')
    <section class="content section" id="deckInfo">
      <div class="container">
          <div class="card-deck">
            <div class="card">
              <i class="fas fa-search"></i>
              <div class="card-body">
                <h5 class="card-title"><a href="{{ get_permalink( get_page_by_path( 'wiki' ) ) }}">Base de Conocimiento</a></h5>
                <p class="card-text">Base de Conocimiento de todas las aplicaciones de la Institución.</p>
              </div>
              <div class="card-footer">
                <a class="btn btn-link" href="{{ get_permalink( get_page_by_path( 'wiki' ) ) }}">Ver recurso</a>
              </div>
            </div>
            <div class="card">
                <i class="fas fa-question"></i>
              <div class="card-body">
                <h5 class="card-title"><a href="{{ get_post_type_archive_link( 'faq' ) }}">Preguntas Frecuentes</a></h5>
                <p class="card-text">Aquí verá las soluciones más simples a los problemas más comunes en TI.</p>
              </div>
              <div class="card-footer">
                <a class="btn btn-link" href="{{ get_post_type_archive_link( 'faq' ) }}">Ver recurso</a>
              </div>
            </div>
            <div class="card">
                <i class="far fa-comment-alt"></i>
              <div class="card-body">
                <h5 class="card-title"><a href="{{ get_post_type_archive_link( 'forum' ) }}">Foros de Consultas</a></h5>
                <p class="card-text">Consulte a los especialistas de UTI sobre un problema en particular</p>
              </div>
              <div class="card-footer">
                <a class="btn btn-link" href="{{ get_post_type_archive_link( 'forum' ) }}">Ver recurso</a>
              </div>
            </div>
          </div>
      </div>
    </section>

    @php
    $kb_query = App::customQuery('knowledgebase', 3);
    $faq_query = App::customQuery('faq', 3);
    $bbp_query = App::customQuery('forum', 3);
    //var_dump($bbp_query->have_posts())
    @endphp

    <section class="content section" id="feed">
      <div class="container">
        <div class="row">
          <div class="col-sm-4">
            <h3 class="widgetTitle">Últimas entradas en la Wiki</h3>
            @if ( $kb_query !== null && $kb_query->have_posts() )
              <ul class="feed">
                  @while($kb_query->have_posts()) @php $kb_query->the_post() @endphp
                  @include('partials.content-feed')
                  @endwhile
              </ul>
              @php wp_reset_postdata() @endphp
            @endif
          </div>
          <div class="col-sm-4">
            <h3 class="widgetTitle">Últimas preguntas registradas</h3>
            @if ( $faq_query !== null && $faq_query->have_posts() )
              <ul class="feed">
                  @while($faq_query->have_posts()) @php $faq_query->the_post() @endphp
                  @include('partials.content-feed')
                  @endwhile
              </ul>
              @php wp_reset_postdata() @endphp
            @endif
            
          </div>
          <div class="col-sm-4">
            <h3 class="widgetTitle">Últimas entradas en los foros</h3>
            @if ( $bbp_query !== null && $bbp_query->have_posts() )
              <ul class="feed">
                  @while($bbp_query->have_posts()) @php $bbp_query->the_post() @endphp
                  @include('partials.content-feed')
                  @endwhile
              </ul>
              @php wp_reset_postdata() @endphp
            @endif
          </div>
        </div>
      </div>
    </section>
    <section class="content section" id="feedback">
      <div class="container">
        <div class="row">
          <div class="col-sm-12 col-md-8">
            <h3 class="widgetTile">¿No encontraste lo que buscabas o necesitas ayuda?</h3>
            <p>Si no econtraste lo que buscabas, puedes enviarnos una consulta o sugerencia de información que esperas encontrar en este espacio.</p>
            <p><a href="#" class="btn btn-primary">Ingresa una sugerencia</a></p>
          </div>
        </div>
      </div>
    </section>
    @php do_action('get_footer') @endphp
    @include('partials.footer')
    @php wp_footer() @endphp
    <aside class="liveSearch" style="display:none">
        <div class="list-group">
        </div>
    </aside>
  </body>
</html>
