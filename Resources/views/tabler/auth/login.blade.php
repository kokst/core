@extends('core::tabler.layouts.auth')
@section('content')
    {!! Form::open(['url' => url(config('tabler.url.post-login', 'login')), 'method' => 'POST', 'class' => 'card']) !!}
    <div class="card-body p-6">
        <div class="card-title">@lang('core::login.title')</div>
        <div class="form-group">
            {!! Form::label('email', trans('core::login.email'), ['class' => 'form-label']) !!}
            {!! Form::email('email', old('email'), ['placeholder' => trans('core::login.email-placeholder'), 'class' => 'form-control', 'autofocus' => true]) !!}
        </div>
        <div class="form-group">
            <label class="form-label" for="password">
                @lang('core::login.password')
                <a href="{!! url(config('tabler.urls.forgot', 'password/reset')) !!}" class="float-right small">@lang('core::login.forgot')</a>
            </label>
            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => trans('core::login.password-placeholder')]) !!}
        </div>
        <div class="form-group">
            <label class="custom-control custom-checkbox">
                {!! Form::checkbox('remember', null, false, ['class' => 'custom-control-input']) !!}
                <span class="custom-control-label">@lang('core::login.remeber-me')</span>
            </label>
        </div>
        <div class="form-footer">
            <button type="submit" class="btn btn-primary btn-block">@lang('core::login.signin')</button>
        </div>
    </div>
    {!! Form::close() !!}
    <div class="text-center text-muted">
        @lang('core::login.no-account') <a href="{!! url(config('tabler.url.register', 'register')) !!}">@lang('core::login.register')</a>
    </div>
@stop