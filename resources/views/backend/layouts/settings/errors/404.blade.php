<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('backend/css/main.css') }}" />
    <title>404 Error</title>
    <style>
        .error-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>

<body>
    <div id="preloader">
        <div class="spinner"></div>
    </div>

    <div class="error-container">
        <div class="error-box">
            <img class="mx-auto mb-20" src="{{ asset('backend/images/error.svg') }}" alt="error img">
            <h1 class="fw-700 mb-15">Page Not Found</h1>
            <p class="text-sm mb-25">
                The page you are looking for was moved removed.
                rename or might never existed.
            </p>
            <div class="d-flex align-items-center justify-content-center gap-3">
                <a href="{{ route('admin-dashboard') }}" class="main-btn primary-btn btn-hover">Go Home</a>
                <a href="#" class="main-btn secondary-btn btn-hover">Contact Us</a>
            </div>
        </div>
    </div>

    <script src="{{ asset('backend/js/main.js') }}"></script>
</body>

</html>
