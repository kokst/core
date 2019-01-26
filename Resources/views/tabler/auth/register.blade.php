@extends('core::tabler.layouts.auth')
@section('content')
    {!! Form::open(['url' => url(config('tabler.url.post-register', 'register')), 'method' => 'POST', 'class' => 'card']) !!}
    <div class="card-body p-6">
        <div class="card-title">@lang('core::register.title')</div>
        <div class="form-group">
            {!! Form::label('name', trans('core::register.name'), ['class' => 'form-label']) !!}
            {!! Form::text('name', old('name'), ['placeholder' => trans('core::register.name-placeholder'), 'class' => 'form-control', 'autofocus' => true]) !!}
        </div>
        <div class="form-group">
            {!! Form::label('email', trans('core::register.email'), ['class' => 'form-label']) !!}
            {!! Form::email('email', old('email'), ['placeholder' => trans('core::register.email-placeholder'), 'class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('password', trans('core::register.password'), ['class' => 'form-label']) !!}
            {!! Form::password('password', ['placeholder' => trans('core::register.password-placeholder'), 'class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('password_confirmation', trans('core::register.password-confirmation'), ['class' => 'form-label']) !!}
            {!! Form::password('password_confirmation', ['placeholder' => trans('core::register.password-confirmation-placeholder'), 'class' => 'form-control']) !!}
        </div>
        <div class="form-footer">
            <button type="submit" class="btn btn-primary btn-block">@lang('core::register.singup')</button>
        </div>
    </div>
    {!! Form::close() !!}
    <div class="text-center text-muted">
        @lang('core::register.have-account') <a href="{!! url(config('tabler.url.login-url', 'login')) !!}">@lang('core::register.login')</a>
    </div>
@stop