@extends('tabler.layouts.main')

@section('title')
    {{ $header }}
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
            'actionlist' => isset($actionlist) ? $actionlist : [],
            'basic' => (isset($basic) && $basic === true) ? true : false,
            'year' => (isset($year)) ? $year : null,
            'roles' => (isset($roles) && $roles === true) ? true : false,
            'filters' => (isset($filters) && $filters === true) ? true : false,
        ]
    )

        @slot('cardtitle')
            {{ $header }}
        @endslot

    @endcomponent
@stop
