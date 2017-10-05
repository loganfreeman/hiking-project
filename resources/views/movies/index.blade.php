@inject('image', 'Tmdb\Helper\ImageHelper')

@extends('layouts.default')

@section('title')
{{ 'Movies' }}
@stop

@section('custom-css')
<style>
.grid-item { width: 260px; }
.card {
  width: 260px;
  display:inline-block;
  margin-bottom:20px;
  padding-bottom:10px;
  vertical-align:top
}
</style>
@stop

@section('content')
<div class="grid">
  @foreach ($movies as $movie)
    <div class="grid-item">
      <div class="card">
        {!! $image->getHtml($movie->getPosterImage(), 'w154', 260, 420) !!}
        <div class="card-block">
          <h4 class="card-title">{!! $movie->getTitle() !!}</h4>
          <p class="card-text">{!! $movie->getOverview() !!}</p>
          <a href="#" class="btn btn-primary">Go to homepage</a>
        </div>
      </div>
    </div>
  @endforeach
</div>
@stop

@section('js')
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"></script>
<script>
  $(document).ready(function() {
    $('.grid').masonry({
      itemSelector: '.grid-item',
      columnWidth: 260,
      gutter: 10
    });
  })
</script>
@stop
