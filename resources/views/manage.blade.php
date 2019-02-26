<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="manifest" href="{{umi('manifest.json')}}">
    <link rel="stylesheet" href="{{umi('umi.css')}}">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0">
    <title>{{config('store.title')}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{umi('favicon.png')}}" type="image/x-icon">
    <script src="https://gw.alipayobjects.com/os/antv/pkg/_antv.data-set-0.9.6/dist/data-set.min.js"></script>
    <script>
        window.routerBase = "/";
        window.publicPath = "{{umi('')}}";
        window.store = {
            title: "{{config('store.title')}}",
            token: "{{ csrf_token() }}",
            api: "{{str_finish(route('store-api-v1'),'/')}}"
        }
    </script>
    <script async src="{{umi('pwacompat.min.js')}}"></script>
</head>
<body>
<noscript>Sorry, we need js to run correctly!</noscript>
<div id="root"></div>
<script src="{{umi('umi.js')}}"></script>
</body>
</html>