@extends('tabler.layouts.main')

@section('title')
    {{ $header }}

    @if(isset($years))
        <year-switcher :year="{{ json_encode($year) }}" :years="{{ json_encode($years) }}" :resource="{{ json_encode('device') }}"></year-switcher>
        <select class="custom-select w-auto" style="padding:0.5rem 0.75rem 0.5rem 0.75rem;">
            <option value="0000">New</option>
            @foreach($years as $option)
                <option value="{{ $option }}" @if($option === $year)selected=""@endif>{{ $option }}</option>
            @endforeach
        </select>
    @endif
@stop

@section('content')
    @component(
        'vendor.kokst.core.components.datatable.resource', [
            'resource' => $resource,
            'collection' => $collection,
            'id' => (isset($id) && $id === false) ? false : true,
            'title' => (isset($title) && $title === false) ? false : true,
            'extrafields' => isset($extrafields) ? $extrafields : [],
            'activity' => (isset($activity) && $activity === false) ? false : true,
            'actions' => (isset($actions) && $actions === false) ? false : true,
            'basic' => (isset($basic) && $basic === true) ? true : false,
            'year' => (isset($year)) ? $year : null,
            'roles' => (isset($roles) && $roles === true) ? true : false,
        ]
    )

        @slot('cardtitle')
            {{ $header }}
        @endslot

    @endcomponent
@stop
