@extends('layouts.app')

@section('content')
  @php
  $terms = get_terms( 'sistema', array(
    'hide_empty' => false,
  ) );

  if ( !empty( $terms ) && !is_wp_error( $terms ) ){ 
    echo '<div class="row">'; 

    foreach ( $terms as $term ) { 
      $term = sanitize_term( $term, 'sistema' ); 
      $term_link = get_term_link( $term, 'sistema' ); 

        echo '<div class="col-sm-3 mb-3"><div class="card"><a href="' . esc_url( $term_link ) . '">' . $term->name . '&nbsp;(' . $term->count . ')' . '</a></div></div>'; 
    } 
    echo '</div>';
  }  
  @endphp
@endsection
