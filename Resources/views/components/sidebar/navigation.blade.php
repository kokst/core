@if (count($items) > 0)
    <div>
        <div class="list-group list-group-transparent mb-0">
            @foreach($items as $item => $data)
                <a href="{{ $data['route'] }}" class="list-group-item list-group-item-action d-flex align-items-center {{ in_array(Route::current()->uri, $data['active']) ? 'active' : ''}}">
                    <span class="icon mr-3"><i class="fe fe-{{ $data['icon'] }}"></i></span> {{ $item }}
                </a>
            @endforeach
        </div>
    </div>
@endif
