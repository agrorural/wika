{{--
  Template Name: FAQs Template
--}}

@extends('layouts.app')

@section('content')
  {{-- @while(have_posts()) @php the_post() @endphp
    @include('partials.page-header')
    @include('partials.content-page')
  @endwhile --}}
  @php
    $terms = get_terms( 'producto', array(
      'hide_empty' => false,
    ) );
    
    if ( !empty( $terms ) && !is_wp_error( $terms ) ){ 
      echo '<div class="row">'; 

      foreach ( $terms as $term ) { 
        $term = sanitize_term( $term, 'producto' ); 
        $term_link = get_term_link( $term, 'producto' ); 

          echo '<div class="col-sm-3 mb-3"><div class="card"><a href="' . esc_url( $term_link ) . '">' . $term->name . '&nbsp;(' . $term->count . ')' . '</a></div></div>'; 
      } 
      echo '</div>';
    }  
  @endphp
  
@endsection
