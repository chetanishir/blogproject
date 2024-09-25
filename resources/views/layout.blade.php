<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>
 
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

        <link rel="stylesheet" href="{{ asset('/custom.css') }}">
    
    </head>
    <body class="antialiased">

         <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <a class="navbar-brand" href="/">Blog Site</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/blogs/">Blogs</a>
                </li>
                @auth

                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/blogs/create">Create Blog</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout')}}">logout</a>
                </li>
                @else
                
                <li class="nav-item">
                    <a class="nav-link" href="/login">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/register">Register</a>
                </li>
                @endauth

                
                @auth
                <li class="nav-item">
    
                    <a class="nav-link">Logged In as <b>{{ Auth::user()->name }}</b> </a>
                </li>
          @endauth
            </ul>
            
        </div>
    </nav>

     <!-- banner -->
     @if (!request()->routeIs('blogs.show') && url()->current() != url('/'))

<section id="innerBanner" class="wp-block-acf-banner-with-heading" style="background-image: url('https://images.pexels.com/photos/262508/pexels-photo-262508.jpeg');">
    <div class="innerBannerContent">
        <h1>Blogs</h1>
    </div>
</section>
@endif


<section class="main">
    <div class="container">

    
        
        <div class="row">@yield('content')</div>

        
    </div>
</section>
 <!-- /banner -->
    <!-- Main Content -->
    

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <p class="mb-0">© 2024 Your Company</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    </body>
</html>
