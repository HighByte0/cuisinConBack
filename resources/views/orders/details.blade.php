<!DOCTYPE html>
<html lang="en">

<head>
    <title>Order Details Map</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />

    <!-- Custom CSS -->
    <style>
        #map {
            height: 300px; /* Ensure this is set to a visible height */
            width: 100%;
        }
    </style>
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
                    <li><a href="{{ route('welcome') }}" >Home</a></li>
                    <li><a href="{{ route('food.create') }}">Add plat</a></li>
                    <li><a href="{{ route('orders', Auth::user()->id) }}"class="active">Orders</a></li>
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
    

    <table class="table">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Food Title</th>
                <th>image</th>
                <th>Quantity</th>
                <th>Order Status</th>
                <th>Payment Status</th>
                <th>Note</th>
                <th>proccessing</th>
                <th>Delivery Address</th>
                <th>Food Details</th>
                <th>View Map</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orderDetails as $detail)
                <tr>
                    <td>{{ $detail->f_name }}</td>
                    
                    <td> @if(isset($detail->food_details))
                        @php
                            $foodDetails = json_decode($detail->food_details);
                        @endphp
                        <p><strong></strong> {{ $foodDetails->name  ?? 'N/A' }}</p>
                     
                    @else
                        No food image available
                    @endif</td>
                    <td>
                        @if(isset($detail->food_details))
                            @php
                                $foodDetails = json_decode($detail->food_details);
                            @endphp
                            <p><img src="{{ asset('uploads/' . $foodDetails->img) }}"  alt="" style="height: 100px"></p>
                          
                         
                        @else
                            No food title available
                        @endif
                    </td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ $detail->order_status }}</td>
                    <td>{{ $detail->payment_status }}</td>
                    <td>{{ $detail->order_note }}</td>
                    <td>
                        <form action="{{ route('orders.updateStatus', $detail->order_id) }}" method="POST">
                            @csrf
                            <select name="order_status" class="form-control">
                                <option value="pending" {{ $detail->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="accepted" {{ $detail->order_status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="processing" {{ $detail->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="handover" {{ $detail->order_status == 'handover' ? 'selected' : '' }}>Ready for Handover</option>
                                <option value="picked_up" {{ $detail->order_status == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                            </select>
                            <button type="submit" class="btn btn-primary mt-2">Update Status and send mail</button>
                        </form>
                    </td>
                    <td>
                        <p><strong>Address:</strong> {{ $detail->delivery_address->address }}</p>
                        <p><strong>Latitude:</strong> {{ $detail->delivery_address->latitude }}</p>
                        <p><strong>Longitude:</strong> {{ $detail->delivery_address->longitude }}</p>
                    </td>
                    <td>
                        @if(isset($detail->food_details))
                            @php
                                $foodDetails = json_decode($detail->food_details);
                            @endphp
                            <p><strong>Food Address:</strong> {{ $foodDetails->location  ?? 'N/A' }}</p>
                            <p><strong>Latitude:</strong> {{ $foodDetails->lat ?? 'N/A' }}</p>
                            <p><strong>Longitude:</strong> {{ $foodDetails->lon ?? 'N/A' }}</p>
                        @else
                            No food details available
                        @endif
                    </td>
                    <td>
                        <!-- Button to Open the Map Modal -->
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#mapModal{{ $detail->order_id }}">
                            View Map
                        </button>

                        <!-- Map Modal -->
                        <div class="modal fade" id="mapModal{{ $detail->order_id }}" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel{{ $detail->order_id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="mapModalLabel{{ $detail->order_id }}">Order and Food Location</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="map{{ $detail->order_id }}" style="height: 400px; width: 100%;"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Leaflet JS -->
                        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                $('#mapModal{{ $detail->order_id }}').on('shown.bs.modal', function () {
                                    var mapId = 'map{{ $detail->order_id }}';
                                    var orderLat = {{ $detail->delivery_address->latitude }};
                                    var orderLon = {{ $detail->delivery_address->longitude }};
                                    var foodLat = {{ $foodDetails->lat ?? 'null' }};
                                    var foodLon = {{ $foodDetails->lon ?? 'null' }};

                                    var map = L.map(mapId).setView([orderLat, orderLon], 13);

                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        attribution: '© OpenStreetMap contributors'
                                    }).addTo(map);

                                    L.marker([orderLat, orderLon])
                                        .addTo(map)
                                        .bindPopup('<b>Order Location</b>')
                                        .openPopup();

                                    if (foodLat !== 'null' && foodLon !== 'null') {
                                        L.marker([foodLat, foodLon])
                                            .addTo(map)
                                            .bindPopup('<b>Food Location</b>');

                                        // Draw a line between the order location and the food location
                                        var latlngs = [
                                            [orderLat, orderLon],
                                            [foodLat, foodLon]
                                        ];
                                        L.polyline(latlngs, {color: 'blue'}).addTo(map);
                                    }
                                });
                            });
                        </script>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
