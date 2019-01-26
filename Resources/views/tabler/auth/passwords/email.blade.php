@extends('core::tabler.layouts.auth')
@section('content')
    {!! Form::open(['url' => url(config('tabler.url.post-email', 'password/email')), 'method' => 'POST', 'class' => 'card']) !!}
    <div class="card-body p-6">
        <div class="card-title">@lang('core::email.title')</div>
        <div class="form-group">
            {!! Form::label('email', trans('core::email.email'), ['class' => 'form-label']) !!}
            {!! Form::email('email', old('email'), ['placeholder' => trans('core::email.email-placeholder'), 'class' => 'form-control']) !!}
        </div>
        <div class="form-footer">
            <button type="submit" class="btn btn-primary btn-block">@lang('core::email.send')</button>
        </div>
    </div>
    {!! Form::close() !!}
    <div class="text-center text-muted">
        @lang('core::login.no-account') <a href="{!! url(config('tabler.url.register', 'register')) !!}">@lang('core::login.register')</a>
    </div>
@stop