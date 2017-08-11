@extends('layouts.default')

@section('title')
{{ $term or 'Search' }}
@stop

@section('custom-css')

<style>
.parent {
    display: flex;
    flex-flow:column;
    height: 100%;
    background: white;
}

.child-top {
    flex: 0 1 auto;
}

.child-bottom {
    flex: 1 1 auto;
}
</style>
@stop


@section('content')
  <div class="parent">
      <div class="child-top">
      </div>
      <div class="child-bottom">
        <form id="form_search">

          <!--form content here-->

          <div class="input-group add-on">
            <input class="form-control" placeholder="Search" name="srch-term" id="srch-term" type="text">
            <div class="input-group-btn">
              <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
          </div>

        </form>
      </div>
  </div>

  <div class="results">

    @foreach ($results as $result)
    @endforeach
  </div>
@stop

@section('js')

@stop
