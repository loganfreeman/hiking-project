<!DOCTYPE html>
<html lang="en-GB">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>{{ Config::get('app.name') }} - @section('title')
@show</title>
@include('partials.header')
@section('custom-css')
@show
</head>
<body>
<div id="wrap">
@navigation
<div class="row content-wrapper">
<div class="col-xs-12 col-sm-2 col-sm-push-10">
  @section('right-side')
  @show
</div>
<div class="col-xs-12 col-sm-8">
  @section('top')
  @show
  @include('partials.notifications')
  @section('content')
  @show
</div>
<div class="col-xs-12 col-sm-2 col-sm-pull-10">
  @section('left-side')
  @show
</div>
@include('partials.footer')
@section('bottom')
@show
</body>
</html>
