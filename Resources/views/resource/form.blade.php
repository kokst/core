@extends('tabler.layouts.main')

@section('title')
    {{ $header }}
@stop

@section('options')
    @if(isset($years))
        <year-switcher :year="{{ json_encode($year) }}"
                       :years="{{ json_encode($years) }}"
                       :resource="{{ json_encode($resource) }}"
                       :disable-year-create-route={{ json_encode($disableYearCreateRoute ?? false) }}>
        </year-switcher>
    @endif
@stop

@section('content')
    @component('vendor.kokst.core.components.form.form', [
        'resource' => $resource,
        'type' => $type,
        'model' => isset($model) ? $model : null,
        'namespace' => isset($namespace) ? $namespace : null,
        'softdelete' => (isset($softdelete) && $softdelete === false) ? false : true,
        'fields' => isset($fields) ? $fields : [],
        'basic' => (isset($basic) && $basic === true) ? true : false,
        'year' => (isset($year)) ? $year : null,
    ])
    @endcomponent
@stop
