<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <title>{{ $setting->title }}</title>
    <link rel="stylesheet" href="{{asset('admin/plugins/select2/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/styles/new/nouislider.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/styles/new/swiper.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/styles/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/styles/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/styles/new/style.css') }}">
    <link rel="stylesheet" href="{{asset('admin/css/persian-datepicker.min.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i|Source+Sans+Pro:300,300i,400,400i,600,600i,700,700i,900,900i&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/fonts/css/fontawesome-all.min.css') }}">
    <link rel="manifest" href="_manifest.json" data-pwa-version="set_in_manifest_and_pwa_js">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url($setting->icon_site) }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url($setting->icon_site) }}">
    <link rel="icon" type="image/x-icon" href="{{ url($setting->icon_site) }}"> 

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
 
    <style>
        @font-face {
            font-family: 'Vazirmatn';
            src: url({{ asset('fonts/webfonts/Vazirmatn-Light.woff2') }}); /* IE9 Compat Modes */
            src: url({{ asset('fonts/webfonts/Vazirmatn-Light.woff2') }}) format('embedded-opentype'), /* IE6-IE8 */
            url({{ asset('fonts/webfonts/Vazirmatn-Light.woff2') }}) format('woff2'), /* Super Modern Browsers */
            url({{ asset('fonts/webfonts/Vazirmatn-Light.woff2') }}) format('woff'), /* Pretty Modern Browsers */
            url({{ asset('fonts/ttf/Vazirmatn-Light.ttf') }})  format('truetype'), /* Safari, Android, iOS */
        }
        body {
            font-size: 13px;
            font-family: "Vazirmatn" !important;
            line-height: 26px !important;
            color: #6c6c6c !important;
            background-color: #f0f0f0;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .btn {
            font-weight: normal !important;
            font-family: "Vazirmatn" !important;
        }
    </style>
    @yield('css')
</head>
