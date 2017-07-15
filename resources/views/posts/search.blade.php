@extends('layouts.default')

@section('title')
  {{ Request::input('srch-term') }}
@stop
