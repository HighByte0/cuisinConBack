<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    {{-- <link href="{{ mix('css/custom.css') }}" rel="stylesheet"> --}}
</head>
<body>
    <div class="container-fluid p-0">
        <p>Customer ID: {{ $customerId }}</p>
        <div class="card px-4 w-100">
            <img src="{{ asset('storage/images/Visa.jpg') }}" alt="">
            <p class="h8 py-3">Payment Details</p>
            <form action="{{ route('payment.process') }}" method="POST">
                <div class="row gx-3">
                    <div class="col-12 mb-3">
                        <label for="personName" class="text">Person Name</label>
                        <input id="personName" class="form-control" type="text" placeholder="Name" value="Barry Allen">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="cardNumber" class="text">Card Number</label>
                        <input id="cardNumber" class="form-control" type="text" placeholder="1234 5678 435678">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="expiry" class="text">Expiry</label>
                        <input id="expiry" class="form-control" type="text" placeholder="MM/YYYY">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="cvv" class="text">CVV/CVC</label>
                        <input id="cvv" class="form-control" type="password" placeholder="***">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">
                            <span>Pay $243</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

            </form>
            
           
        </div>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
