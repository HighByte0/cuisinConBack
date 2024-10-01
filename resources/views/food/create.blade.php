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
                    <li><a href="{{ route('welcome') }}">Home</a></li>
                    <li><a href="{{ route('food.create') }}"  class="active">Add plat</a></li>
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
    <div class="container mt-5">
        <h2>What Are You Cooking Today, Great Chef?</h2>
        <form action="{{ route('food.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
        
            <!-- Name field -->
            <div class="form-group @error('name') has-error @enderror">
                <label for="name">Food Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter food name" value="{{ old('name') }}" required>
                @error('name')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        
            <!-- Price field -->
            <div class="form-group @error('price') has-error @enderror">
                <label for="price">Price</label>
                <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" step="0.01" value="{{ old('price') }}" required>
                @error('price')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        
            <!-- Description field -->
            <div class="form-group @error('description') has-error @enderror">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" placeholder="Enter food description">{{ old('description') }}</textarea>
                @error('description')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        
    
        
            <!-- Type field -->
            <div class="form-group @error('type_id') has-error @enderror">
                <label for="type_id">Food Type</label>
                <select class="form-control" id="type_id" name="type_id" required>
                    @if(Auth::user()->auto_entrepreneur_number)
                        <option value="2">auto_entrepreneur</option>
                        <option value="3">particulier section</option>
                    @else
                        <option value="3">particulier section</option>
                    @endif
                </select>
                @error('type_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        
            <!-- Location selection with map -->
            <div class="form-group @error('location') has-error @enderror">
                <label for="location">Location (Automatically Filled)</label>
                <div id="map" style="height: 300px;"></div>
                <input type="text" class="form-control" id="location" name="location" placeholder="Latitude, Longitude" readonly>
                <input type="hidden" id="lat" name="lat">
                <input type="hidden" id="lon" name="lon">
                
                @error('location')
                    <small class="form-text text-danger">{{ $message }}></small>
                @else
                    <small class="form-text text-muted">Click on the map to select a location.</small>
                @enderror
            </div>
        
            <!-- Image upload field -->
            <div class="form-group @error('img') has-error @enderror">
                <label for="img">Food Image</label>
                <input type="file" class="form-control-file" id="img" name="img" required>
                @error('img')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        
            <!-- Confirmation checkbox -->
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="confirmation" required>
                <label class="form-check-label" for="confirmation">I confirm that the details provided are accurate.</label>
            </div>
        
            <!-- Submit button -->
            <button type="submit" class="btn btn-primary mt-3">Add Food</button>
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
    var map = L.map('map').setView([33.5861588, -7.619049], 13); // Maarif, Casablanca coordinates

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Marker for selected location
    var marker = L.marker([0, 0]).addTo(map);

    // Function to update the map and form fields
    function updateLocation(lat, lon, displayName) {
        marker.setLatLng([lat, lon]);
        map.setView([lat, lon], 13);
        document.getElementById('lat').value = lat;
        document.getElementById('lon').value = lon;
        document.getElementById('location').value = displayName;
    }

    // Handle map click event
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lon = e.latlng.lng;

        // Use reverse geocoding to get the display name
        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`)
            .then(response => response.json())
            .then(data => {
                var displayName = data.display_name;
                updateLocation(lat, lon, displayName);
            })
            .catch(error => {
                console.error('Error getting location name:', error);
                updateLocation(lat, lon, lat + ', ' + lon);
            });
    });

    // Function to get the user's current location
    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;

                // Use reverse geocoding to get the display name
                fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`)
                    .then(response => response.json())
                    .then(data => {
                        var displayName = data.display_name;
                        updateLocation(lat, lon, displayName);
                    })
                    .catch(error => {
                        console.error('Error getting location name:', error);
                        updateLocation(lat, lon, lat + ', ' + lon);
                    });
            }, function(error) {
                console.error('Error getting location:', error);
                // Optional: Provide a fallback location or handle error gracefully
                updateLocation(33.5604894,-7.539288, "Baida, Casablanca");
            });
        } else {
            console.error('Geolocation is not supported by this browser.');
            // Optional: Provide a fallback location or handle error gracefully
            updateLocation(33.5604894,-7.539288, "Maarif, Casablanca");
        }
    }

    // Get the current location on page load
    getCurrentLocation();
</script>

</body>
</html>
