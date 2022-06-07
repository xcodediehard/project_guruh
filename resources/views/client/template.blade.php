<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    @include('client.components.header')
    @stack("style")
</head>

<body id="page-top text-dark">
    @include("client.components.navigation")
    @yield("content")
    @include('client.components.footer')
    @stack("scripts")
</body>

</html>