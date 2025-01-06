@php
    $systemSetting = App\Models\SystemSetting::first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    {{-- FAVICON --}}
    <link rel="shortcut icon" type="image/x-icon"
        href="{{ isset($systemSetting->favicon) && !empty($systemSetting->favicon) ? asset($systemSetting->favicon) : asset('frontend/logo.png') }}" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            width: 100%;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        body {
            /* background-image: url('{{ asset('frontend/mockup_image.png') }}'); */ /*For Mockup Uncomment IT*/
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #3b82f6;
            text-align: center;
        }

        .Box{
            display: block; /*For Display None IT*/
        }

        .container {
            text-align: center;
        }

        h1 {
            font-size: 4rem;
            font-weight: bold;
            color: white;
        }

        .link {
            font-size: 1rem;
            margin-top: 1rem;
            color: white;
            text-decoration: none;
        }

        .author {
            text-decoration: none;
            color: white;
        }

        .author:hover {
            text-decoration: underline;
        }

        .icon {
            margin-right: 0.25rem;
            vertical-align: middle;
        }

        .text-md {
            font-size: 1rem;
        }

        .mt-4 {
            margin-top: 1rem;
        }

        .text-white {
            color: white;
        }

        .bg-blue-500 {
            background-color: #3b82f6;
        }

        .login-btn,
        .dashboard-btn {
            position: absolute;
            bottom: 20px;
            left: 20px;
            padding: 0.8em 2em;
            font-size: 1.2em;
            color: white;
            background-color: rgba(0, 0, 0, 0.7);
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .login-btn:hover,
        .dashboard-btn:hover {
            background-color: rgba(0, 0, 0, 0.9);
            transform: scale(1.05);
        }

        @media (max-width: 600px) {

            .login-btn,
            .dashboard-btn {
                padding: 0.6em 1.5em;
                font-size: 1em;
                bottom: 10px;
                left: 10px;
            }
        }
    </style>
</head>

<body class="w-screen h-screen bg-blue-500 flex justify-center items-center text-center">
    <div class="Box">
        <h1 class="text-6xl font-bold text-white">Laravel API Stater Kit.</h1>
        <p class="text-md mt-4 text-white">
            <a href="https://github.com/rhishi-kesh" class="hover:underline link" target="_blank">
                <svg class="inline-block mr-1" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M15.197 3.35462C16.8703 1.67483 19.4476 1.53865 20.9536 3.05046C22.4596 4.56228 22.3239 7.14956 20.6506 8.82935L18.2268 11.2626M10.0464 14C8.54044 12.4882 8.67609 9.90087 10.3494 8.22108L12.5 6.06212"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path
                        d="M13.9536 10C15.4596 11.5118 15.3239 14.0991 13.6506 15.7789L11.2268 18.2121L8.80299 20.6454C7.12969 22.3252 4.55237 22.4613 3.0464 20.9495C1.54043 19.4377 1.67609 16.8504 3.34939 15.1706L5.77323 12.7373"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                </svg>
                <span class="author">Rhishi Kesh Bhowmik💕</span>
            </a>
        </p>
    </div>
    <div>
        @if (Route::has('login'))
            @auth
                <a href="{{ route('admin.dashboard') }}" class="dashboard-btn">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="login-btn">
                    Log in
                </a>
            @endauth
        @endif
    </div>
</body>

</html>
