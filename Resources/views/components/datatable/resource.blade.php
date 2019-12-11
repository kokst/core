{{--
    @component(
        'vendor.kokst.core.components.datatable.resource', [
            'resource' => 'resource',
            'collection' => $collection,
            'id' => true,
            'title' => true,
            'extrafields' => [
                'description' => ['header' => 'Description', 'sort' => true],
            ],
            'activity' => true,
            'actions' => true,
            'actionlist' => [
                'view' => ['header' => 'View', 'route' => 'resource.edit', 'icon' => 'eye'],
            ],
            'basic' => false,
            'filters' => false,
            'year' => $year,
            'years' => $years,
            'roles' => false,
        ]
    )

        @slot('cardtitle')
            @lang('module::index.title')
        @endslot

    @endcomponent
--}}

<div class="row row-cards row-deck">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    {{ $cardtitle }}
                </h3>
                <div class="card-options">
                    @if($collection->first())
                        <input id="search" type="text" autocomplete="off" class="form-control form-control ml-2" placeholder="@lang('vendor/kokst/core/components/datatable/index.search')">
                    @endif
                </div>
            </div>
            <div id="dimmer" class="card-body mt-5 mb-5">
                <div class="dimmer active">
                    <div class="loader"></div>
                        <div class="dimmer-content">
                    </div>
                </div>
            </div>
            <div id="table" class="table-responsive" style="display:none;">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            @if($id)
                                <th class="{{ $filters ? 'w-1 no-sort no-filter' : 'w-1 no-filter'}}">@lang('vendor/kokst/core/components/datatable/index.id')</th>
                            @endif

                            @if($title)
                                <th class="{{ $filters ? 'no-sort no-filter' : ''}}">@lang('vendor/kokst/core/components/datatable/index.title')</th>
                            @endif

                            @foreach($extrafields as $extrafield)
                                <th {{ ($filters === true && isset($extrafield['filter']) && $extrafield['filter'] === true) || $extrafield['sort'] == false ? 'class=no-sort' : 'class=no-filter' }} style="{{ isset($extrafield['hidden']) && $extrafield['hidden'] ? 'display: none;' : ''}}">
                                    {{ $extrafield['header'] }}
                                </th>
                            @endforeach

                            @if($roles)
                                <th class="{{ $filters ? 'no-sort' : 'no-filter'}}">@lang('vendor/kokst/core/components/datatable/index.roles')</th>
                            @endif

                            @if($activity)
                                <th class="{{ $filters ? 'no-sort no-filter' : ''}}">@lang('vendor/kokst/core/components/datatable/index.activity')</th>
                            @endif

                            @if($actions)
                                <th class="no-sort"></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($collection as $model)
                            <tr>
                                @if($id)
                                    <td>
                                        <span class="text-muted">
                                            {{ $model->id }}
                                        </span>
                                    </td>
                                @endif

                                @if($title)
                                    <td>
                                        @if($basic)
                                            <a href="{{ route($resource . '.edit', ['id'=> $model->id, 'year' => $year ?? null]) }}" class="text-inherit">
                                        @else
                                            <a href="{{ route($resource . '.show', ['id'=> $model->id, 'year' => $year ?? null]) }}" class="text-inherit">
                                        @endif
                                            {{ $model->title }}
                                        </a>
                                    </td>
                                @endif

                                @foreach($extrafields as $extrafield => $options)
                                    <td style="{{ isset($options['hidden']) && $options['hidden'] ? 'display: none;' : ''}}">
                                        <div>
                                            @if(isset($options['escape']) && $options['escape'] === false)
                                                {!! $model->$extrafield !!}
                                            @else
                                                {{ $model->$extrafield }}
                                            @endif
                                        </div>
                                    </td>
                                @endforeach

                                @if($roles)
                                    <td>
                                        <div>
                                            {{ $model->roles->pluck('name')->implode(', ') }}
                                        </div>
                                    </td>
                                @endif

                                @if($activity)
                                    <td data-order="{{ $model->activityData }}">
                                        <div class="small text-muted">
                                            {{ $model->activityLabel }}
                                        </div>
                                        <div>
                                            {{ $model->activityValue }}
                                        </div>
                                    </td>
                                @endif

                                @if($actions)
                                    @if($basic)
                                        <td class="w-1"><a href="{{ route($resource . '.edit', [Str::snake(Str::camel($resource)) => $model->id, 'year' => $year ?? null]) }}" class="icon"><i class="fe fe-edit"></i></a></td>
                                    @else
                                        <td class="text-center">
                                            <div class="item-action dropdown">
                                                <a href="javascript:void(0)" data-toggle="dropdown" class="icon"><i class="fe fe-more-vertical"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                @if($actionlist)
                                                    @foreach($actionlist as $action => $options)
                                                        <a href="{{ route($options['route'], [Str::snake(Str::camel($resource)) => $model->id]) }}" class="dropdown-item"><i class="dropdown-icon fe fe-{{ $options['icon'] }}"></i> {{ $options['header'] }} </a>
                                                    @endforeach
                                                @else
                                                    <a href="{{ route($resource . '.show', [Str::snake(Str::camel($resource)) => $model->id, 'year' => $year ?? null]) }}" class="dropdown-item"><i class="dropdown-icon fe fe-eye"></i> @lang('vendor/kokst/core/components/datatable/index.view') </a>
                                                    <a href="{{ route($resource . '.edit', [Str::snake(Str::camel($resource)) => $model->id, 'year' => $year ?? null]) }}" class="dropdown-item"><i class="dropdown-icon fe fe-edit-2"></i> @lang('vendor/kokst/core/components/datatable/index.edit') </a>
                                                    <div class="dropdown-divider"></div>
                                                    <form method="POST" action="/{{ $resource }}/{{ $model->id }}">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="dropdown-item"><i class="dropdown-icon fe fe-trash"></i> @lang('vendor/kokst/core/components/datatable/index.delete')</button>
                                                    </form>
                                                @endif
                                                </div>
                                            </div>
                                        </td>
                                        @endif
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="create">
                    @if(isset($year))
                        <a href="{{ route($resource . '.create', ['year' => $year]) }}" class="btn btn-primary btn"><i class="fe fe-plus"></i></a>
                    @else
                        <a href="{{ route($resource . '.create') }}" class="btn btn-primary btn"><i class="fe fe-plus"></i></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
    .dataTables_wrapper .table {
        border-top: none;
    }

    .dropdown {
        position: initial;
    }

    .create {
        margin: 1rem 1.5rem;
    }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('admin/assets/plugins/datatables/plugin.js') }}"></script>
    <script type="text/javascript">
        var intViewportHeight = window.innerHeight;
        var intViewportWidth = window.innerWidth;

        var tableLenght;
        var contentBaseSize;
        var rowHeight = 67.2;
        var paginationType = 'simple';

        if (intViewportWidth >= 0  ) { contentBaseSize = 393; }
        if (intViewportWidth >= 252) { contentBaseSize = 355; }
        if (intViewportWidth >= 304) { contentBaseSize = 317; }
        if (intViewportWidth >= 527) { contentBaseSize = 279; }
        if (intViewportWidth >= 768) { contentBaseSize = 303; }
        if (intViewportWidth >= 992) { contentBaseSize = 358.63; paginationType = 'simple_numbers'; }

        tableLenght = Math.floor((intViewportHeight - contentBaseSize ) / rowHeight);

        if (tableLenght < 1) { tableLenght = 1; }

        var table;
        require(['datatables', 'jquery'], function(datatable, $) {
            table = $('.datatable').DataTable({
                "info": false,
                "lengthChange": false,
                "pageLength": tableLenght,
                "dom": 'lrtip',
                "stateSave": true,
                "stateDuration": 60 * 60 * 24,
                "pagingType": paginationType,

                @if($filters)
                "order": [[ 0, "desc" ]],
                @endif

                "columnDefs": [{
                    "targets": 'no-sort',
                    "orderable": false,
                }],

                "language": {
                    "zeroRecords": "@lang('vendor/kokst/core/components/datatable/index.zero')",
                    "paginate": {
                        "first": "@lang('vendor/kokst/core/components/datatable/index.first')",
                        "last": "@lang('vendor/kokst/core/components/datatable/index.last')",
                        "next": '<i class="fe fe-arrow-right"></i>',
                        "previous": '<i class="fe fe-arrow-left"></i>'
                    },
                },

                preDrawCallback: function (settings) {
                    api = new $.fn.dataTable.Api(settings);
                    var pagination = $(this)
                        .closest('.dataTables_wrapper')
                        .find('.dataTables_paginate');
                    pagination.toggle(api.page.info().pages > 1);
                },

                drawCallback: function (settings) {
                    api = new $.fn.dataTable.Api(settings);

                    if (api.page.info().pages <= 1 && api.page.info().end <= 1) {
                        $('#table th').each(function() {
                            $(this).removeClass('sorting sorting_asc sorting_desc');
                            $(this).addClass('sorting_disabled');
                            $(this).off();
                        });
                    }

                    @if($filters)
                    $('#table th').each(function() {
                        $(this).removeClass('sorting sorting_asc sorting_desc');
                        $(this).addClass('sorting_disabled');
                        $(this).off();
                    });
                    @endif
                },

                @if($filters)
                initComplete: function () {
                    $('#table thead tr').clone(true).appendTo( '#table thead' );

                    this.api().columns().every( function () {
                        var column = this;
                        var select = $('<select class="form-control column-filter"><option placeholder="" value=""></option></select>')
                            .appendTo( $(column.header()).empty() )
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                val = val.replace('<div>', "").replace('<\\/div>', "").replace(/(\r\n|\n|\r)/gm, "").trim();

                                column
                                    .search( val ? val : '', true, false )
                                    .draw();
                            });

                        var found = false;
                        column.data().unique().sort().each( function ( d, j ) {
                            var dEscaped = $.fn.dataTable.util.escapeRegex(d)
                            dEscaped = dEscaped.replace('<div>', "").replace('<\\/div>', "").replace(/(\r\n|\n|\r)/gm, "").trim()

                            var selected = '';
                            if (dEscaped === column.search()) {
                                found = true;
                                selected = 'selected="selected"';
                            }

                            select.append('<option value="'+d+'"'+selected+'>'+d+'</option>')
                        });

                        if (found === false) {
                            column.search('', true, false).draw();
                        }
                    });

                    require(['jquery', 'selectize'], function ($, selectize) {
                        $(document).ready(function () {
                            $('.column-filter').selectize({});
                        });
                    });

                    $('#table thead tr')[1].parentNode.insertBefore($('#table thead tr')[1], $('#table thead tr')[0]);

                    $('#table th').each(function() {
                        $(this).removeClass('sorting sorting_asc sorting_desc');
                        $(this).addClass('sorting_disabled');
                        $(this).off();
                    });

                    $('#table thead tr:eq(1) th').each(function() {
                        if($(this).hasClass('no-filter')) {
                            $(this).empty();
                        }
                    });
                }
                @endif
            });

            $("#search").each(function() {
                if (
                    JSON.parse(localStorage.getItem('DataTables_DataTables_Table_0_' + window.location.pathname)).search.search != "" &&
                    JSON.parse(localStorage.getItem('DataTables_DataTables_Table_0_' + window.location.pathname)).search.search != null
                ) {
                    $(this).val(JSON.parse(localStorage.getItem('DataTables_DataTables_Table_0_' + window.location.pathname)).search.search);
                }
            });

            $('#table').show();
            $('#dimmer').hide();
            $("#search").focus();

            $('#search').on('keyup', function () {
                table.search(this.value).draw();
            });
        });
    </script>
@endpush
