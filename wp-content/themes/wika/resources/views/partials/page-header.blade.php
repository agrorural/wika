
<div class="page-header">
  <div class="container">
    @if ( function_exists( 'breadcrumb_trail' ) )
      @php
          $defaults = array(
          'container'       => 'nav',
          'before'          => '',
          'after'           => '',
          'list_tag'        => 'ol',
          'item_tag'        => 'li',
          'show_on_front'   => true,
          'network'         => false,
          'show_title'      => false,
          'show_browse'     => false,
          'labels' => array(
            'home'                => 'Inicio',
          ),
          'post_taxonomy' => array(
            'post'  => 'post_tag', // 'post' post type and 'post_tag' taxonomy
            'faqs'  => 'sistema',    // 'book' post type and 'genre' taxonomy
          ),
          'echo'            => true
        );

        breadcrumb_trail($defaults);
      @endphp
    @endif
    <h1>{!! App::title() !!}</h1>
    @include('partials.ajax-search')
  </div>
</div>

{{-- @php
$queried_object = get_queried_object();
 var_dump( is_post_type_archive() );
 var_dump( $queried_object->label );
@endphp --}}