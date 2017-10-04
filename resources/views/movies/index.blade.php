@inject('image', 'Tmdb\Helper\ImageHelper')

@extends('layouts.default')

@section('title')
{{ 'Movies' }}
@stop
@section('content')
  @foreach ($movies as $movie)
      {!! $image->getHtml($movie->getPosterImage(), 'w154', 260, 420) !!}
  @endforeach
@stop
