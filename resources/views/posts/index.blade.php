@extends('layouts.default')

@section('title')
Blog
@stop

@section('top')
<div class="page-header">
<h1>Blog</h1>
</div>
@stop

@section('content')
<div class="my-toolbar">
    @auth('blog')
        <div class="right-action-col">
            <a class="btn btn-primary" href="{!! URL::route('blog.posts.create') !!}"><i class="fa fa-book"></i> New Post</a>
        </div>
    @endauth

    <div class="left-action-col">
      <form class="navbar-form" role="search" action="/search/posts" method="post">
          <div class="input-group add-on">
            <input class="form-control" placeholder="Search" name="srch-term" id="srch-term" type="text">
            <div class="input-group-btn">
              <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
          </div>
        </form>
    </div>

</div>
@foreach($posts as $post)
    <h2>{!! $post->title !!}</h2>
    <p>
        <strong>{!! $post->summary !!}</strong>
    </p>
    <ul class="list-group">
      <a class="btn btn-success btn-secondary" href="{!! URL::route('blog.posts.show', array('posts' => $post->id)) !!}"><i class="fa fa-file-text"></i> Show Post</a>
      @auth('blog')
        <a class="btn btn-info btn-secondary" href="{!! URL::route('blog.posts.edit', array('posts' => $post->id)) !!}"><i class="fa fa-pencil-square-o"></i> Edit Post</a>
        <a class="btn btn-danger btn-secondary" href="#delete_post_{!! $post->id !!}" data-toggle="modal" data-target="#delete_post_{!! $post->id !!}"><i class="fa fa-times"></i> Delete Post</a>
      @endauth
    </ul>

    <div class="actions">
      @auth('user')
      <a class="icons-sm"><i class="fa fa-thumbs-o-up fa-1" aria-hidden="true" data-id="{{ $post->id }}"></i></a>
      @endauth
    <strong>{!! $post->likesCount() !!} likes</strong>
    </div>

@endforeach
@if (isset($links))
{!! $links !!}
@endif
@stop

@section('bottom')
@auth('blog')
    @include('posts.deletes')
@endauth
@stop

@section('js')
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
<script>
$(document).ready(function() {
  $('i.fa-thumbs-o-up').click(function() {
    var postId = $(this).data('id');
    $.ajax({
      url: '/post/like',
      type: 'POST',
      dataType: 'JSON',
      data: {
        id: postId
      },
      success: function(response) {
        console.log(response.message);
      },
      error: function(response) {
        console.log(response);
      }
    })
  });
});
</script>
@stop
