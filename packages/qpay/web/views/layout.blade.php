<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>QPay - The world's simplest and safest payment gateway</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="{{ asset('vendor/qpay/css/qpay-web.css') }}">
    <script src="{{ asset('vendor/qpay/js/qpay-web.js') }}"></script>
</head>
<body>
<div class="container-fluid">
    <div class="title">
        {{ $title }}
    </div>
    <div id="errorMessage" class="hidden">
    </div>
    @yield('content')
</div>
</body>
</html>