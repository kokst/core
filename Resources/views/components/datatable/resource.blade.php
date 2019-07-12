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
            'basic' => false,
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
                                <th class="w-1">@lang('vendor/kokst/core/components/datatable/index.id')</th>
                            @endif

                            @if($title)
                                <th>@lang('vendor/kokst/core/components/datatable/index.title')</th>
                            @endif

                            @foreach($extrafields as $extrafield)
                                <th {{ $extrafield['sort'] == false ? 'class=no-sort' : '' }}>
                                    {{ $extrafield['header'] }}
                                </th>
                            @endforeach

                            @if($activity)
                                <th>@lang('vendor/kokst/core/components/datatable/index.activity')</th>
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
                                            <a href="{{ route($resource . '.edit', ['id'=> $model->id]) }}" class="text-inherit">
                                        @else
                                            <a href="{{ route($resource . '.show', ['id'=> $model->id]) }}" class="text-inherit">
                                        @endif
                                            {{ $model->title }}
                                        </a>
                                    </td>
                                @endif

                                @foreach($extrafields as $extrafield => $options)
                                    <td>
                                        <div>
                                            {{ $model->$extrafield }}
                                        </div>
                                    </td>
                                @endforeach

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
                                        <td class="w-1"><a href="{{ route($resource . '.edit', ['id'=> $model->id]) }}" class="icon"><i class="fe fe-edit"></i></a></td>
                                    @else
                                        <td class="text-center">
                                            <div class="item-action dropdown">
                                                <a href="javascript:void(0)" data-toggle="dropdown" class="icon"><i class="fe fe-more-vertical"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route($resource . '.show', ['id'=> $model->id]) }}" class="dropdown-item"><i class="dropdown-icon fe fe-eye"></i> @lang('vendor/kokst/core/components/datatable/index.view') </a>
                                                <a href="{{ route($resource . '.edit', ['id'=> $model->id]) }}" class="dropdown-item"><i class="dropdown-icon fe fe-edit-2"></i> @lang('vendor/kokst/core/components/datatable/index.edit') </a>
                                                <div class="dropdown-divider"></div>
                                                <form method="POST" action="/{{ $resource }}/{{ $model->id }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="dropdown-item"><i class="dropdown-icon fe fe-trash"></i> @lang('vendor/kokst/core/components/datatable/index.delete')</button>
                                                </form>
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
                    <a href="{{ route($resource . '.create') }}" class="btn btn-primary btn"><i class="fe fe-plus"></i></a>
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
                }
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
