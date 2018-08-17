<li class="media">
  <a href="{{ get_permalink() }}"><img class="mr-3" src="http://via.placeholder.com/48x48" alt="Generic placeholder image"></a>
  <div class="media-body">
    <h5 class="mt-0 mb-1"><a href="{{ get_permalink() }}">{{ get_the_title() }}</a></h5>
    <time class="updated"><i class="far fa-clock"></i> @include('partials/entry-meta')</time>
  </div>
</li>