<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--favicon-->
        <link rel="icon" type="image/svg+xml" href="{{asset('faicon.svg')}}">
        <link rel="icon" type="image/ico" href="{{asset('favicon.ico')}}">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

         <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- css -->
        <link rel="stylesheet" href="{{asset('css/style.bundle.css')}}">
        <link rel="stylesheet" href="{{asset('css/plugins.bundle.css')}}">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia

          <script src="{{asset('js/plugins.bundle.js')}}"></script>
       <script src="{{asset('js/scripts.bundle.js')}}"></script>
    </body>
</html>
