<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Add your CSS file -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form action="{{ route('register') }}" method="POST">
            @csrf

            <!-- First Name -->
            <div class="form-group">
                <label for="f_name">First Name</label>
                <input type="text" id="f_name" name="f_name" class="form-control" required>
            </div>

            <!-- Phone -->
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" class="form-control" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            </div>

            <h3 > Enjoy special advantages and enhanced features by registering as an auto-entrepreneur in our app</h3>

            <div class="form-group">
                <label for="password_confirmation">RC|CIE|NIF</label>
                <input type="auto_entrepreneur_number" id="auto_entrepreneur_number" name="auto_entrepreneur_number" class="form-control" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</body>
</html>
