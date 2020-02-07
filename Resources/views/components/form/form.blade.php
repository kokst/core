{{--
    @component('vendor.kokst.core.components.form.form', [
        'resource' => 'post',
        'type' => 'create', // or 'edit'
        'model' => $post, // if edit
        'softdelete' => true, // optional
        'namespace' => 'post' // optional
        'year' => $year // optional
        'fields' => [
            'title' => ['type' => 'text', 'required' => true],
        ],
    ])
    @endcomponent
--}}

@if(isset($year))
    <form class="card" method="POST" action="/{{ $year }}/{{ $resource }}{{ $type === 'edit' ? "/$model->id" : '' }}">
@else
    <form class="card" method="POST" action="/{{ $resource }}{{ $type === 'edit' ? "/$model->id" : '' }}">
@endif
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
                        @if($options['type'] === 'text')
                            <label class="form-label" for="{{ $field }}">@lang((isset($namespace) ? "${namespace}::" : '') . 'form.' . $field . '-placeholder')</label>

                            <input class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}"
                                type="text"
                                id="{{ $field }}"
                                name="{{ $field }}"
                                placeholder="@lang((isset($namespace) ? "${namespace}::" : '') . 'form.' . $field . '-placeholder')"
                                value="{{ old($field, $type === 'edit' && isset($model->$field) ? $model->$field : null) }}"

                                @if ($loop->first)
                                    autofocus
                                @endif

                                @if(isset($options['required']) && $options['required'])
                                    required
                                @endif>
                        @elseif($options['type'] === 'number')
                            <label class="form-label" for="{{ $field }}">@lang((isset($namespace) ? "${namespace}::" : '') . 'form.' . $field . '-placeholder')</label>
                            <input class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}"
                                type="number"
                                id="{{ $field }}"
                                name="{{ $field }}"
                                placeholder="@lang((isset($namespace) ? "${namespace}::" : '') . 'form.' . $field . '-placeholder')"
                                step="1"
                                value="{{ old($field, $type === 'edit' && isset($model->$field) ? $model->$field : null) }}"

                                @if ($loop->first)
                                    autofocus
                                @endif

                                @if(isset($options['required']) && $options['required'])
                                    required
                                @endif
                            />
                        @elseif($options['type'] === 'password')
                            <label class="form-label" for="{{ $field }}">@lang((isset($namespace) ? "${namespace}::" : '') . 'form.' . $field . '-placeholder')</label>

                            <input class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}"
                                type="password"
                                id="{{ $field }}"
                                name="{{ $field }}"
                                placeholder="@lang((isset($namespace) ? "${namespace}::" : '') . 'form.' . $field . '-placeholder')"

                                @if ($loop->first)
                                    autofocus
                                @endif

                                @if(isset($options['required']) && $options['required'])
                                    required
                                @endif>
                        @elseif($options['type'] === 'percent')
                            <label class="form-label" for="{{ $field }}">@lang((isset($namespace) ? "${namespace}::" : '') . 'form.' . $field . '-placeholder')</label>
                            <div class="input-group">
                                <input class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}"
                                    type="number"
                                    id="{{ $field }}"
                                    name="{{ $field }}"
                                    placeholder="@lang((isset($namespace) ? "${namespace}::" : '') . 'form.' . $field . '-placeholder')"
                                    step="1"
                                    value="{{ old($field, $type === 'edit' && isset($model->$field) ? $model->$field : null) }}"

                                    @if ($loop->first)
                                        autofocus
                                    @endif

                                    @if(isset($options['required']) && $options['required'])
                                        required
                                    @endif
                                />
                                <span class="input-group-append" id="basic-addon2">
                                    <span class="input-group-text">%</span>
                                </span>
                            </div>
                        @elseif($options['type'] === 'kg')
                            <label class="form-label" for="{{ $field }}">@lang((isset($namespace) ? "${namespace}::" : '') . 'form.' . $field . '-placeholder')</label>
                            <div class="input-group">
                                <input class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}"
                                    type="number"
                                    id="{{ $field }}"
                                    name="{{ $field }}"
                                    placeholder="@lang((isset($namespace) ? "${namespace}::" : '') . 'form.' . $field . '-placeholder')"
                                    step="1"
                                    value="{{ old($field, $type === 'edit' && isset($model->$field) ? $model->$field : null) }}"

                                    @if ($loop->first)
                                        autofocus
                                    @endif

                                    @if(isset($options['required']) && $options['required'])
                                        required
                                    @endif
                                />
                                <span class="input-group-append" id="basic-addon2">
                                    <span class="input-group-text">kg</span>
                                </span>
                            </div>
                        @elseif($options['type'] === 'euro')
                            <label class="form-label" for="{{ $field }}">@lang((isset($namespace) ? "${namespace}::" : '') . 'form.' . $field . '-placeholder')</label>
                            <div class="input-group">
                                <input class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}"
                                type="text"
                                id="{{ $field }}"
                                name="{{ $field }}"
                                placeholder="@lang((isset($namespace) ? "${namespace}::" : '') . 'form.' . $field . '-placeholder')"
                                value="{{ old($field, $type === 'edit' && isset($model->$field) ? $model->$field : null) }}"

                                @if ($loop->first)
                                    autofocus
                                @endif

                                @if(isset($options['required']) && $options['required'])
                                    required
                                @endif>

                                <span class="input-group-append" id="basic-addon2">
                                    <span class="input-group-text">€</span>
                                </span>
                            </div>
                        @elseif($options['type'] === 'select')
                            @component('vendor.kokst.core.components.form.select', [
                                'collection' => $options['collection'],
                                'id' => $field,
                                'label' => __((isset($namespace) ? "${namespace}::" : '') . 'form.' . $field . '-placeholder'),
                                'old' => isset($options['old']) ? $options['old'] : null,
                                'placeholder' => isset($options['placeholder']) ? $options['placeholder'] : 'null',
                                'required' => isset($options['required']) && $options['required'] === true ? true : false,
                            ])
                            @endcomponent
                        @elseif($options['type'] === 'checkbox')
                            <label class="custom-control custom-checkbox" for="{{ $field }}">
                                <input class="custom-control-input {{ $errors->has($field) ? 'is-invalid' : '' }}"
                                    type="checkbox"
                                    id="{{ $field }}"
                                    name="{{ $field }}"

                                @if(old($field, $type === 'edit' && isset($model->$field) && $model->$field === 1))
                                    checked
                                @endif

                                @if(isset($options['required']) && $options['required'])
                                    required
                                @endif
                                />
                                <span class="custom-control-label">@lang((isset($namespace) ? "${namespace}::" : '') . 'form.' . $field . '-placeholder')</span>
                            </label>
                        @elseif($options['type'] === 'year')
                            <label class="form-label" for="{{ $field }}">@lang((isset($namespace) ? "${namespace}::" : '') . 'form.' . $field . '-placeholder')</label>
                            <input class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}"
                                type="number"
                                id="{{ $field }}"
                                name="{{ $field }}"
                                placeholder="@lang((isset($namespace) ? "${namespace}::" : '') . 'form.' . $field . '-placeholder')"
                                min="1900"
                                max="{{ date('Y') + 100 }}"
                                step="1"
                                value="{{ date('Y') }}"

                                @if ($loop->first)
                                    autofocus
                                @endif

                                @if(isset($options['required']) && $options['required'])
                                    required
                                @endif
                              />
                        @elseif($options['type'] === 'custom')
                            @if(View::exists($namespace.'::fields.' . $field))
                                @include($namespace.'::fields.' . $field)
                            @else
                                <div class="alert alert-danger" role="alert">
                                    <strong>View {{ $namespace.'::fields.' . $field }}</strong> not found.
                                </div>
                            @endif
                        @else
                            @if(config('app.debug'))
                                <div class="alert alert-danger" role="alert">
                                    <strong>{{ $options['type'] }}</strong> is no valid field type.
                                </div>
                            @endif
                        @endif

                        @if ($errors->has($field))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first($field) }}</strong>
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card-footer text-right">
        <div class="d-flex">
            @if($type === 'create')
                @unless(app('request')->input('first'))
                    <a href="{{ route($resource . '.index') }}" class="btn btn-link">@lang((isset($namespace) ? "${namespace}::" : '') . 'form.cancel')</a>
                @endunless
                <button type="submit" class="btn btn-primary ml-auto">@lang((isset($namespace) ? "${namespace}::" : '') . $type . '.submit')</button>
            @endif

            @if($type === 'edit')
                @if($basic)
                    <a href="{{ route($resource . '.index') }}" class="btn btn-link">@lang((isset($namespace) ? "${namespace}::" : '') . 'form.cancel')</a>
                @else
                    <a href="{{ route($resource . '.show', [Str::snake(Str::camel($resource)) => $model->id, 'year' => $year ?? null]) }}" class="btn btn-link">@lang((isset($namespace) ? "${namespace}::" : '') . 'form.cancel')</a>
                @endif

                <div class="btn-list ml-auto">
                    <button type="submit" class="btn btn-primary">@lang((isset($namespace) ? "${namespace}::" : '') . $type . '.submit')</button>
                </div>
            @endif
        </div>
    </div>
</form>

@if($type === 'edit')
    @if(isset($year))
        <form method="POST" action="/{{ $year }}/{{ $resource }}/{{ $model->id }}">
    @else
        <form method="POST" action="/{{ $resource }}/{{ $model->id }}">
    @endif
        @csrf
        @method('DELETE')

        <div class="card card-collapsed">
            <div class="card-header">
                <h3 class="card-title">@lang((isset($namespace) ? "${namespace}::" : '') . 'form.danger')</h3>
                <div class="card-options">
                    <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        @if($softdelete)
                            <button type="submit" class="btn btn-outline-danger">@lang((isset($namespace) ? "${namespace}::" : '') . $type . '.archive')</button>
                        @else
                            <button type="submit" class="btn btn-outline-danger">@lang((isset($namespace) ? "${namespace}::" : '') . $type . '.destroy')</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endif
