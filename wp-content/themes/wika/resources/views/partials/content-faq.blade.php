<article @php post_class("card") @endphp>

  <header id="heading_{{ get_the_ID() }}" class="card-header">
    <button class="btn btn-block btn-link entry-title" type="button" data-toggle="collapse" data-target="#collapse_{{ get_the_ID() }}" aria-expanded="false" aria-controls="collapse_{{ get_the_ID() }}">
      {{ get_the_title() }}
    </button>
  </header>

  <div id="collapse_{{ get_the_ID() }}" class="entry-summary collapse" aria-labelledby="heading_{{ get_the_ID() }}" data-parent="#accordionSistemas">
    <div class="card-body">
      @php the_content() @endphp
    </div>
  </div>
</article>
