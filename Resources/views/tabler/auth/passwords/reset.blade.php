@extends('core::tabler.layouts.auth')
@section('content')
    {!! Form::open(['url' => url(config('tabler.url.post-reset', 'password/reset')), 'method' => 'POST', 'class' => 'card']) !!}
    <div class="card-body p-6">
        <div class="card-title">@lang('core::reset.title')</div>
        <div class="form-group">
            {!! Form::label('email', trans('core::reset.email'), ['class' => 'form-label']) !!}
            {!! Form::email('email', old('email'), ['placeholder' => trans('core::reset.email-placeholder'), 'class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('password', trans('core::reset.password'), ['class' => 'form-label']) !!}
            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => trans('core::reset.password-placeholder')]) !!}
        </div>
        <div class="form-group">
            {!! Form::label('password_confirmation', trans('core::reset.password-confirmation'), ['class' => 'form-label']) !!}
            {!! Form::password('password_confirmation', ['placeholder' => trans('core::reset.password-confirmation-placeholder'), 'class' => 'form-control']) !!}
        </div>
        <div class="form-footer">
            <button type="submit" class="btn btn-primary btn-block">@lang('core::reset.send')</button>
        </div>
    </div>
    {!! Form::close() !!}
@stop