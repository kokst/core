{{--
    @component('components.form.form', [
        'resource' => 'post',
        'type' => 'create', // or 'edit'
        'model' => $post, // if edit
        'namespace' => 'post' // optional
        'fields' => [
            'title' => ['type' => 'text', 'required' => true],
        ],
    ])
    @endcomponent
--}}

<form class="card" method="POST" action="/{{ $resource }}{{ $type === 'edit' ? "/$model->id" : '' }}">
    @csrf

    @if($type === 'edit')
        @method('PATCH')
    @endif

    <div class="card-header">
        @if ($errors->any())
            <div class="card-status card-status-left bg-red"></div>
        @endif

        <h3 class="card-title">
            @lang((isset($namespace) ? "${namespace}::" : '') . $type . '.title')
        </h3>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-12">
                @foreach($fields as $field => $options)
                    <div class="form-group">
                        <label class="form-label">@lang((isset($namespace) ? "${namespace}::" : '') . 'form.' . $field . '-placeholder')</label>
                    </div>

                    @if($options['type'] === 'text')
                        <input class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}"
                               type="text"
                               name="{{ $field }}"
                               placeholder="@lang((isset($namespace) ? "${namespace}::" : '') . 'form.' . $field . '-placeholder')"
                               value="{{ old($field, $type === 'edit' && isset($model->$field) ? $model->$field : null) }}"

                               @if ($loop->first)
                                   autofocus
                               @endif

                               @if(isset($options['required']) && $options['required'])
                                   required
                               @endif>
                    @endif

                    @if ($errors->has($field))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first($field) }}</strong>
                        </span>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="card-footer text-right">
        <div class="d-flex">
            @if($type === 'create')
                <a href="{{ route($resource . '.index') }}" class="btn btn-link">@lang((isset($namespace) ? "${namespace}::" : '') . 'form.cancel')</a>
                <button type="submit" class="btn btn-primary ml-auto">@lang((isset($namespace) ? "${namespace}::" : '') . $type . '.submit')</button>
            @endif

            @if($type === 'edit')
                <a href="{{ route($resource . '.show', ['id' => $model->id]) }}" class="btn btn-link">@lang((isset($namespace) ? "${namespace}::" : '') . 'form.cancel')</a>

                <div class="btn-list ml-auto">
                    <button type="submit" class="btn btn-primary">@lang((isset($namespace) ? "${namespace}::" : '') . $type . '.submit')</button>
                </div>
            @endif
        </div>
    </div>
</form>

@if($type === 'edit')
    <form method="POST" action="/{{ $resource }}/{{ $model->id }}">
        @csrf
        @method('DELETE')

        <div class="card card-collapsed">
            <div class="card-header">
                <h3 class="card-title">Danger Zone</h3>
                <div class="card-options">
                    <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-outline-danger">@lang((isset($namespace) ? "${namespace}::" : '') . $type . '.destroy')</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endif
