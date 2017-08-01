<form class="form-horizontal" action="{{ $form['url'] }}" method="{{ $form['method'] }}" enctype="multipart/form-data">

    {{ csrf_field() }}
    <input type="hidden" name="_method" value="{{ isset($form['_method'])? $form['_method'] : $form['method'] }}">

    <div class="form-group{!! ($errors->has('title')) ? ' has-error' : '' !!}">
        <label class="col-md-2 col-sm-3 col-xs-10 control-label" for="title">Post Title</label>
        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-10">
            <input name="title" value="{!! Request::old('title', $form['defaults']['title']) !!}" type="text" class="form-control" placeholder="Post Title">
            {!! ($errors->has('title') ? $errors->first('title') : '') !!}
        </div>
    </div>

    <div class="form-group{!! ($errors->has('summary')) ? ' has-error' : '' !!}">
        <label class="col-md-2 col-sm-3 col-xs-10 control-label" for="summary">Post Summary</label>
        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-10">
            <input name="summary" value="{!! Request::old('summary', $form['defaults']['summary']) !!}" type="text" class="form-control" placeholder="Post summary">
            {!! ($errors->has('summary') ? $errors->first('summary') : '') !!}
        </div>
    </div>

    <div class="form-group{!! ($errors->has('categories')) ? ' has-error' : '' !!}">
        <label class="col-md-2 col-sm-3 col-xs-10 control-label" for="categories">Category</label>
        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-10">
            @foreach ($categories as $category)
                <label class="checkbox-inline">
                  <input type="checkbox" name="categories[]" value="{!! $category->id !!}" {{ $category->checked ? 'checked' : '' }}>{!! $category->name !!}
                </label>
            @endforeach
            {!! ($errors->has('categories') ? $errors->first('categories') : '') !!}
        </div>
    </div>

    <div class="form-group{!! ($errors->has('body')) ? ' has-error' : '' !!}">
        <label class="col-md-2 col-sm-3 col-xs-10 control-label" for="body">Post Body</label>
        <div class="col-lg-6 col-md-8 col-sm-9 col-xs-12">
            <textarea name="body" type="text" class="form-control" data-provide="markdown" placeholder="Post Body" rows="10">{!! Request::old('body', $form['defaults']['body']) !!}</textarea>
            {!! ($errors->has('body') ? $errors->first('body') : '') !!}
        </div>
    </div>

    <div class="form-group{!! ($errors->has('image')) ? ' has-error' : '' !!}">
        <label class="col-md-2 col-sm-3 col-xs-10 control-label" for="image">Post image</label>
        <div class="col-lg-6 col-md-8 col-sm-9 col-xs-12">
            <input type="file" class="form-control" name="image" accept="image/gif, image/jpeg, image/png">
            {!! ($errors->has('image') ? $errors->first('image') : '') !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-offset-2 col-sm-offset-3 col-sm-10 col-xs-12">
            <button class="btn btn-primary" type="submit"><i class="fa fa-rocket"></i> {!! $form['button'] !!}</button>
        </div>
    </div>

</form>
