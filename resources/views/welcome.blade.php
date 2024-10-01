<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Cuisine connectée</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/cc.png" rel="icon">
  <link href="assets/img/cc.png" rel="apple-touch-icon">
  {{-- <img src="assets/img/cc.png" class="img-fluid animated" alt=""> --}}
<!-- Update Bootstrap CSS -->
<link rel="stylesheet" href="https://4b39-196-117-77-207.ngrok-free.app/assets/vendor/bootstrap/css/bootstrap.min.css">

<!-- Update Your Main CSS -->
<link rel="stylesheet" href="https://4b39-196-117-77-207.ngrok-free.app/assets/css/main.css">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Yummy
  * Template URL: https://bootstrapmade.com/yummy-bootstrap-restaurant-website-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">
  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">

        <a href="{{ route('welcome') }}" class="logo d-flex align-items-center me-auto me-xl-0">
            <h1 class="sitename">Cuisine connectée</h1>
            <span>.</span>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="{{ route('welcome') }}" class="active">Home</a></li>
                <li><a href="{{ route('food.create') }}">Add plat</a></li>
                <li><a href="{{ route('orders', Auth::user()->id) }}">Orders</a></li>
                <li><a href="{{ route('menu', Auth::user()->id) }}">your plats</a></li>
                
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        @if (Auth::check())
            <!-- User is logged in -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
                @method('POST')
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            <a href='{{ route('setting') }}' style="color: green;">Setting</a>

        @else
            <!-- User is not logged in -->
            <a class="btn-getstarted" href="{{ route('login.form') }}">Login</a>
            <a class="btn-getstarted" href="{{ route('register.form') }}">Register</a>
        @endif

    </div>
</header>


  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section light-background">

      <div class="container bg-white">
        <div class="row gy-4 justify-content-center justify-content-lg-between">
            <div class="col-lg-5 order-2 order-lg-1 d-flex flex-column justify-content-center">
                <h1 data-aos="fade-up">Votre cuisine sera votre atelier<br> et votre passion votre plat</h1>
                <p data-aos="fade-up" data-aos-delay="100">Avec notre solution de site web, et vos plats, grandissons ensemble.</p>
                <div class="d-flex" data-aos="fade-up" data-aos-delay="200">
                  <a href="{{ route('food.create') }}" class="btn-get-started">Le pouvoir de vos plats</a>
                </div>
            </div>
            <div class="col-lg-5 order-1 order-lg-2 hero-img" data-aos="zoom-out">
                <img src="assets/img/cc.png" class="img-fluid animated" alt="">
            </div>
        </div>
    </div>
    

    </section><!-- /Hero Section -->

  

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
