<li @php post_class() @endphp>
  <a class="feed-content" href="{{ get_permalink() }}">
    <figure></figure>
    <div class="feed-body">
      <h5 class="mt-0 mb-1">{{ get_the_title() }}</h5>
    </div>
  </a>
  {{-- <time class="updated"><i class="far fa-clock"></i> @include('partials/entry-meta')</time> --}}
</li>