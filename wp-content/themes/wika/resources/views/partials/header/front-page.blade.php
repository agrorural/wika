<header class="banner">
  <div class="header-wrapper" data-vide-bg="http://104.236.16.244/knowledgepress/wp-content/uploads/sites/3/2017/10/discussing_features" data-vide-options="posterType: none">
    <div style="position: absolute; z-index: -1; top: 0px; left: 0px; bottom: 0px; right: 0px; overflow: hidden; background-size: cover; background-color: transparent; background-repeat: no-repeat; background-position: 50% 50%; background-image: none;">
      <video autoplay="" loop="" muted="" style="margin: auto; position: absolute; z-index: -1; top: 50%; left: 50%; transform: translate(-50%, -50%); visibility: visible; opacity: 1; width: auto; height: auto;">
        <source src="http://104.236.16.244/knowledgepress/wp-content/uploads/sites/3/2017/10/discussing_features.mp4" type="video/mp4">
        <source src="http://104.236.16.244/knowledgepress/wp-content/uploads/sites/3/2017/10/discussing_features.webm" type="video/webm">
        <source src="http://104.236.16.244/knowledgepress/wp-content/uploads/sites/3/2017/10/discussing_features.ogv" type="video/ogg">
      </video>
    </div>
    <div class="container">
      <section class="branding">
        <a class="brand" href="{{ home_url('/') }}">
          <svg id="logo" version="1.1" height="40" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                viewBox="0 0 480 134" style="enable-background:new 0 0 480 134;" xml:space="preserve">
            <g id="glass">
              <path id="glassBase" class="st0" d="M469.5,127.2L469.5,127.2c-4.1,4.1-10.8,4.1-14.9,0l-23.9-23.8c-4.1-4.1-4.1-10.8,0-14.9v0
                c4.1-4.1,10.8-4.1,14.9,0l23.9,23.8C473.6,116.4,473.6,123.1,469.5,127.2z"/>
              <circle id="glassOuter" class="st0" cx="394.4" cy="50.5" r="47.2"/>
              <circle id="glassInner" class="st1" cx="392.5" cy="46.7" r="38.3"/>
            </g>
            <g id="name">
              <g id="text">
                <path class="st0" d="M124,18.3l-34,97.2h-9.6L63.8,55l-16.3,60.5h-9.6L4,18.3h12l25.9,74.2l17.8-65.5H68l17.9,65.5l25.9-74.2H124z
                  "/>
                <path class="st0" d="M142.6,17.5c4.2,0,7.6,3.4,7.6,7.6c0,4.2-3.4,7.4-7.6,7.4c-4.2,0-7.4-3.2-7.4-7.4
                  C135.1,20.9,138.4,17.5,142.6,17.5z M136.9,42.4h11.6v73.1h-11.6V42.4z"/>
                <path class="st0" d="M185.4,82.9c-1.6-1.9-3.2-2.3-4.6-1.1l-2.3,2v31.6h-11.6V18.3h11.6v50.9l30-26.8H226l-34.6,30.2h0.1
                  c0.8,0.2,3.7,2.2,6,5.2l28.4,37.7h-15.8L185.4,82.9z"/>
                <path class="st0" d="M231.4,92.9c0-13,13-22.6,27.1-22.6h7.8c3.4,0,7.7-1.8,7.7-5.4c0-5.5-5.9-10.9-14.3-10.9
                  c-5.4,0-10.1,2.2-16.1,10.2l-9.4-7c5.8-9.6,16.4-14.9,25.3-14.9c13.9,0,25.9,10.1,25.9,22.6v28c0,12.8-12.8,22.6-27,22.6
                  C244.2,115.5,231.4,105.7,231.4,92.9z M243.1,92.8c0,5.6,6.7,11,15.4,11c8.8,0,15.4-5.5,15.4-11v-13c0,0-1,2-15.4,1.9
                  C249.8,81.7,243.1,87.1,243.1,92.8z"/>
                <path class="st0" d="M308.6,99.9c4.8,0,8.6,3.8,8.6,8.6s-3.8,8.5-8.6,8.5s-8.5-3.7-8.5-8.5S303.8,99.9,308.6,99.9z"/>
              </g>
            </g>
          </svg>
        </a>
        <nav class="nav-primary">
          @if (has_nav_menu('primary_navigation'))
            {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']) !!}
          @endif
        </nav>
      </section>
      <section class="masshead">
        <h1 class="cover-heading">Wika es el portal de documentación de sistemas informáticos de AGRO RURAL</h1>
        <p class="lead">Aquí encontrarás ayuda tecnológica para utilizar mejor los recursos de la institución.</p>
        
        @include('partials.ajax-search')
      </section>
    </div>
  </div>
</header>