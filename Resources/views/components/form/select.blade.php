{{--
    @component('vendor.kokst.core.components.form.select', [
        'collection' => $posts,
        'id' => 'post_id',
        'label' => 'Posts',
        'old' => $old->id,
        'placeholder' => 'Please select a post',
        'required' => 'required',
    ])
    @endcomponent
--}}

{!! Form::label($id, $label, ['class' => 'form-label']) !!}
{!! Form::select($id, $collection, isset($old) ? $old : old($id), [
    'placeholder' => $placeholder,
    'class' => $id . ' form-control custom-select' . ($errors->has($id) ? ' has-errors' : ''),
    'required' => $required,
]) !!}

@if ($errors->has($id))
    <span class="invalid-feedback">
        <strong>{{ $errors->first($id) }}</strong>
    </span>
@endif

@push('scripts')
    <script type="text/javascript">
        require(['jquery', 'selectize'], function ($, selectize) {
            $(document).ready(function () {
                $('.custom-select#{{ $id }}').selectize({});
            });
        });
    </script>
@endpush
