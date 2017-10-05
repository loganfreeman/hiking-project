@extends('layouts.default')

@section('title')
  @if(!empty(app('request')->input('srch-term')))
    {{ app('request')->input('srch-term') }}
  @elseif (!empty(app('request')->input('category')))
    {{ app('request')->input('category') }}
  @elseif (!empty(app('request')->input('favorited')))
    My favorited posts
  @else
    博客
  @endif
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

@section('top')
<div class="top">

</div>
@stop

@section('content')
<div class="my-toolbar">
    <div class="left-action-col">
      <form class="navbar-form" role="search" action="/search/posts" method="get">
          <div class="input-group add-on">
            <input class="form-control" placeholder="Search" name="srch-term" id="srch-term" type="text">
            <div class="input-group-btn">
              <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
          </div>
        </form>
    </div>
</div>

<div class="grid">
@foreach($posts as $post)
<div class="grid-item">
    <div class="card">
      @if($post->hasImage())
        <img src="{{ URL::to($post->imagePath()) }}" />
      @endif


      <div class="card-block">
        <h2 class="card-title">{!! $post->title !!}</h2>
        <p class="card-text">
            <strong>{!! $post->summary !!}</strong>
        </p>
        <p class="card-text">
          @foreach ($post->categories as $category)
              <span class="badge">{!! $category['name'] !!}</span>
          @endforeach
        </p>

        <ul class="list-group">
          <a class="btn btn-success btn-secondary" href="{!! URL::route('blog.posts.show', array('posts' => $post->id)) !!}" data-toggle="tooltip" title="Show Post"><i class="fa fa-file-text" > Show Post</i></a>
          @auth('blog')
            <a class="btn btn-info btn-secondary" href="{!! URL::route('blog.posts.edit', array('posts' => $post->id)) !!}" data-toggle="tooltip" title="Edit Post"><i class="fa fa-pencil-square-o" > Edit Post</i></a>
            <strong data-toggle="tooltip" title="Delete Post!"><a class="btn btn-danger btn-secondary" href="#delete_post_{!! $post->id !!}" data-toggle="modal" data-target="#delete_post_{!! $post->id !!}" ><i class="fa fa-times" > Delete Post</i></a></strong>
          @endauth
        </ul>

        <div class="actions action-bar">
          @auth('user')
          <a class="icons-sm"><i class="fa fa-thumbs-o-up fa-1" aria-hidden="true" data-id="{{ $post->id }}"></i></a>
          @endauth
          <span><strong class="likesCount">{!! $post->likesCount() !!} likes</strong></span>
          @auth('user')
          <a class="icons-sm"><i class="fa fa-bookmark fa-1" aria-hidden="true" data-id="{{ $post->id }}"></i></a>
          <i class="fa fa-heart fa-1 {!! $post->isFavoritedByMe($user) ? "" : "hidden" !!}" aria-hidden="true" data-toggle="tooltip" title="favored by me"></i>
          @endauth
          <div class="fb-share-button" data-href="{!! URL::route('blog.posts.show', array('posts' => $post->id)) !!}" data-layout="button" data-size="small" data-mobile-iframe="false"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">Share</a></div>

        </div>
      </div>


  </div>
</div>
@endforeach
</div>

@if (isset($links))
{!! $links !!}
@endif
@stop

@section('right-side')
<div class="right-side">
  <ul class="list-group borderless">
    @auth('blog')
      <li class="list-group-item">
        <a class="btn btn-warning" href="{!! URL::route('blog.posts.create') !!}"><i class="fa fa-book"></i> New Post</a>
      </li>
    @endauth
    <li class="list-group-item">
      <form id="choose-category" action="/blog/posts">
        <div class="btn-group">
          <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Choose a category <span class="caret"></span>
          </button>
          <input type="hidden" name="category" id="category"></input>
          <ul class="dropdown-menu">
            <li><a href="#" category="">None</a></li>
            @foreach ($categories as $category)
                <li><a href="#" category="{!! $category['name'] !!}">{!! $category['name'] !!}</a></li>
            @endforeach
          </ul>
        </div>
      </form>
    </li>
    @auth('user')
    <li class="list-group-item">
      <a class="btn btn-warning" href="{!! URL::route('blog.posts.index', ["favorited" => 1]) !!}"><i class="fa fa-heart"></i> My Favorite</a>
    </li>
    @endauth
  </ul>
</div>
@stop

@section('left-side')
  <div class="ads">

  </div>
@stop

@section('bottom')
@auth('blog')
    @include('posts.deletes')
@endauth
@stop

@section('js')
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"></script>
<script>
function increaseLikeCount(element) {
  var x = element.text();
  var count = +x.replace(/\D/g,'') + 1;
  element.text(count + " likes");
}
function decreaseLikeCount(element) {
  var x = element.text();
  var count = +x.replace(/\D/g,'') - 1;
  element.text(count + " likes");
}
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
$(document).ready(function() {
  $('.grid').masonry({
    itemSelector: '.grid-item',
    columnWidth: 260,
    gutter: 10
  });
  $('.dropdown-toggle').dropdown();
  $(".dropdown-menu li a").click(function(){
    var category = $(this).attr('category');
    $('#category').val(category);
    $('#choose-category')[0].submit();
  });
  $('i.fa-bookmark').click(function() {
    var heartElement = $('.fa-heart', this.closest('.actions'));
    var postId = $(this).data('id');
    $.ajax({
      url: '/post/favorite',
      type: 'POST',
      dataType: 'JSON',
      data: {
        id: postId
      },
      success: function(response) {
        heartElement.toggleClass('hidden');
      },
      error: function(response) {

      }
    })
  });
  $('i.fa-thumbs-o-up').click(function() {
    var countElement = $('.likesCount', this.closest('.actions'));
    var postId = $(this).data('id');
    $.ajax({
      url: '/post/like',
      type: 'POST',
      dataType: 'JSON',
      data: {
        id: postId
      },
      success: function(response) {
        if(response.hasOwnProperty('deleted_at')) {
          decreaseLikeCount(countElement);
        }else {
          increaseLikeCount(countElement);
        }
      },
      error: function(response) {

      }
    })
  });
});
</script>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.10";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
@stop
