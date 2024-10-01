<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Food Item</title>
    <!-- Link to Bootstrap CSS -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Link to Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <!-- Link to your main CSS -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
</head>
<body>
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
    <div class="container">
        <h1>Update Your Settings</h1>
    
        <form action="{{ route('update') }}" method="POST">
            @csrf
            @method('PUT')
    
            <!-- Name Field -->
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->f_name) }}" required>
            </div>
    
            <!-- Email Field -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
    
         
    
            <!-- Auto-Entrepreneur Number Field -->
            <div class="mb-3">
                <label for="auto_entrepreneur_number" class="form-label">Auto-Entrepreneur Number</label>
                <input type="text" id="auto_entrepreneur_number" name="auto_entrepreneur_number" class="form-control" value="{{ old('auto_entrepreneur_number', $user->auto_entrepreneur_number) }}">
                <small class="form-text text-muted">
                    Veuillez fournir votre numéro d'auto-entrepreneur pour vérification. Votre menu restera dans la section "Particulier" jusqu'à ce que nous vérifions votre numéro.
                </small>
            </div>
    
            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</main>

<!-- Vendor JS Files -->
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
<script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
<script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
<script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<script>
    // Initialize the map
    var map = L.map('map');

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Marker for selected location
    var marker = L.marker([0, 0]).addTo(map);

    // Function to update the map and form fields
    function updateLocation(lat, lon) {
        marker.setLatLng([lat, lon]);
        map.setView([lat, lon], 13);
        document.getElementById('lat').value = lat;
        document.getElementById('lon').value = lon;
        document.getElementById('location').value = lat + ', ' + lon;
    }

    // Handle map click event
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lon = e.latlng.lng;
        updateLocation(lat, lon);
    });

    // Function to get the user's current location
    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;
                updateLocation(lat, lon);
            }, function(error) {
                console.error('Error getting location:', error);
                // Optional: Provide a fallback location or handle error gracefully
                updateLocation(51.505, -0.09); // Default location
            });
        } else {
            console.error('Geolocation is not supported by this browser.');
            // Optional: Provide a fallback location or handle error gracefully
            updateLocation(51.505, -0.09); // Default location
        }
    }

    // Get the current location on page load
    getCurrentLocation();

</script>
</body>
</html>
