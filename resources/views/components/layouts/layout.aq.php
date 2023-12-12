<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="url" content="{{ resource() }}">
    <title>{{ $config["PROJECT_NAME"] }}</title>
    <link rel="icon" href="{{ resource('public/img/logo.png') }}">
    <link rel="stylesheet" href="{{ resource('public/css/style.css') }}" type="text/css">
</head>
<body>
<div class="wrapper">
    <div class="container">
        <aqua-content />
    </div>
</div>
<script src="{{ resource('public/js/core.js') }}"></script>
</body>
</html>