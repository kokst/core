@extends('tabler.layouts.main')

@section('title')
    {{ $header }}
@stop

@section('content')
    @component('vendor.kokst.core.components.form.form', [
        'resource' => $resource,
        'type' => $type,
        'model' => isset($model) ? $model : null,
        'namespace' => isset($namespace) ? $namespace : null,
        'fields' => isset($fields) ? $fields : [],
    ])
    @endcomponent
@stop
