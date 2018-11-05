<?php
$label    = $label ?? null;
$value    = $value ?? null;
$disabled = $disabled ?? false;
$invalid  = $errors->has($key) ? ' is-invalid' : '';
$attributes  = [
    'class' => 'form-control' . $invalid
];

if ($disabled) {
    $attributes['disabled'] = true;
}

?>
<div class="form-group">
    {{ Form::label($key, $label, ['class' => 'col-sm-2 control-label']) }}
    <div class="col-sm-10">
        {{ Form::checkbox($key, 1, $value, $attributes) }}
        @if ($errors->has($key))
            <div class="invalid-feedback">
                @foreach ($errors->all($key) as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </div>
</div>

