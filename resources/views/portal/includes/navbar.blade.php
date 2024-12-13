<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
    <div class="container">

        <!-- Hamburger button for smaller screens -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02"
            aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="toggler-icon">
                <i class="fas fa-bars"></i>
            </span>
        </button>

        <!-- Navbar items -->
        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">

                <!-- Home Page Link -->
                <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('index') }}">होमपेज</a>
                </li>

                <!-- Category Links -->
                @foreach($categories as $category)
                <li class="nav-item @if(request()->is('category/' . $category->slug . '/' . $category->id)) active @endif">
                    <a class="nav-link" href="{{ route('category.render',['slug' => urlencode($category->slug), 'id' => $category->id]) }}" onclick="markNavItemActive(this)">{{ $category->title }}</a>
                </li>
                @endforeach
            </ul>

            <!-- Search bar -->
            <div class="search-container">
                <form action="{{ route('post.search') }}" method="GET">
                    @csrf
                    <div class="search-button">
                        <input type="text" name="input" placeholder="Search..." />
                        <button class="btn btn-outline-primary my-2 my-sm-0" type="submit"> <i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</nav>
