<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="@yield('meta-description', 'Larastro es un desarrollo de un gestor de usuarios con roles y permisos que de una parte, la parte de artisan serve es con blade y vuejs, por parte de npm, es con astro y vuejs usando los estilos de PrimeVue')">
    <meta name="author" content="@yield('meta-author', 'EP-DocDev')">
    <meta name="keywords" content="@yield('meta-keywords', 'Laravel, Astro Build, VueJS, PrimeVue, Admin Dashboard')">
    <meta name="robots" content="index, laravel, astro build, vuejs, development, admin dashboard, primevue">
    <meta name="generator" content="Laravel, Atro Build, VueJS, PrimeVue">
    {{-- <meta property="og:image" content="@yield('og-image', url('/images/John_Deere_Logo.png'))"> --}}
    <meta name="theme-color" content="#FFFFFF" />
    {{-- <link rel="shortcut icon" href="{{ url('/images/John_Deere_Logo.png') }}" /> --}}
    <title inertia>@yield('meta-title', config('app.name'))</title>
    {{-- <link id="theme-css" rel="stylesheet" type="text/css"
        href="{{ asset('node_modules/primevue/resources/themes/lara-light-indigo/theme.css') }}"> --}}
    <link id="theme-css" rel="stylesheet" type="text/css"
        href="{{ mix('node_modules/primevue/resources/themes/lara-light-indigo/theme.css') }}">


</head>

<body>
    @yield('content')
</body>

@vite(['resources/js/app.ts'])

</html>
