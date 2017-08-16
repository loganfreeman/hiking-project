@extends('layouts.default')

@section('title')
{{ $event->title }}
@stop

@section('top')
<div class="page-header">
<h1>{{ $event->title }}</h1>
</div>

<div class="actions action-bar">
@auth('user')
<a class="icons-sm" id="signup" data-id="{{ $event->id }}">
  <i class="fa fa-1 {{ $event->isSignupedbyme($user) ? 'fa-thumbs-o-down' : 'fa-thumbs-o-up' }}" aria-hidden="true"></i>
</a>
<strong>  {{ $event->isSignupedbyme($user) ? 'You\'re going' : 'You\'re not going' }} </strong>

@endauth

<div class="fb-share-button" data-href="{!! URL::route('events.show', array('events' => $event->id)) !!}" data-layout="button" data-size="small" data-mobile-iframe="false"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">Share</a></div>
</div>
@stop

@section('content')
@auth('edit')
    <div class="well clearfix">
        <div class="hidden-xs">
            <div class="col-xs-6">
                <p>
                    <strong>Event Owner:</strong> {!! $event->owner !!}
                </p>
                <a class="btn btn-info" href="{!! URL::route('events.edit', array('events' => $event->id)) !!}"><i class="fa fa-pencil-square-o"></i> Edit Event</a> <a class="btn btn-danger" href="#delete_event" data-toggle="modal" data-target="#delete_event"><i class="fa fa-times"></i> Delete Event</a>
            </div>
            <div class="col-xs-6">
                <div class="pull-right">
                    <p>
                        <em>Event Created: {!! html_ago($event->created_at) !!}</em>
                    </p>
                    <p>
                        <em>Last Updated: {!! html_ago($event->updated_at) !!}</em>
                    </p>
                </div>
            </div>
        </div>
        <div class="visible-xs">
            <div class="col-xs-12">
                <p>
                    <strong>Event Owner:</strong> {!! $event->owner !!}
                </p>
                <p>
                    <strong>Event Created:</strong> {!! html_ago($event->created_at) !!}
                </p>
                <p>
                    <strong>Last Updated:</strong> {!! html_ago($event->updated_at) !!}
                </p>
                <a class="btn btn-info" href="{!! URL::route('events.edit', array('events' => $event->id)) !!}"><i class="fa fa-pencil-square-o"></i> Edit Event</a> <a class="btn btn-danger" href="#delete_event" data-toggle="modal" data-target="#delete_event"><i class="fa fa-times"></i> Delete Event</a>
            </div>
        </div>
    </div>
    <hr>
@endauth
<div class="well clearfix">
    <div class="hidden-xs">
        <div class="col-xs-6">
            <p class="lead">Date: {!! $event->date->format(Config::get('date.php_display_format')) !!}</p>
        </div>
        <div class="col-xs-6">
            <div class="pull-right">
                <p class="lead">Location: {!! $event->location !!}</p>
            </div>
        </div>
    </div>
    <div class="visible-xs">
        <div class="col-xs-12">
            <p class="lead">Date: {!! $event->date->format(Config::get('date.php_display_format')) !!}</p>
            <p class="lead">Location: {!! $event->location !!}</p>
        </div>
    </div>
    <div class="col-xs-12">
        <hr>
        {!! str_replace('<p>', '<p class="lead">', $event->content) !!}
    </div>
</div>

<div class="clearfix">
  <strong>{{ $event->registered_users_count }} user{{ $event->registered_users_count > 1 ? 's' : '' }} are going </strong>
  <br>
  <ul class="list-inline">
  @foreach ($event->registered_users as $user)
    <li class="list-inline-item">{{ $user }}</li>
  @endforeach
  </ul>
  <br>
</div>
@stop

@section('bottom')
@auth('edit')
@include('events.delete')
@endauth
@stop

@section('js')
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
<script>
function updateSignup(signupBtn, signedup)
{
  var signupBtnText = signupBtn.next('strong');
  var icon = signupBtn.children('i');
  icon.toggleClass('fa-thumbs-o-up');
  icon.toggleClass('fa-thumbs-o-down');
  if(signedup){
    signupBtnText.text('You\'re going');
  }else{
    signupBtnText.text('You\'re not going');
  }
}
$(document).ready(function() {
  $('#signup').click(function() {
    var signupBtn = $(this);
    var eventId = $(this).data('id');
    $.ajax({
      url: '/event/signup',
      type: 'POST',
      dataType: 'JSON',
      data: {
        id: eventId
      },
      success: function(response) {
        if(response.hasOwnProperty('deleted_at')) {
          updateSignup(signupBtn, false);
        }else {
          updateSignup(signupBtn, true);
        }
      },
      error: function(response) {
        console.log(response);
      }
    })
  });
})
</script>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.10";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
@stop
