<?php
$label    = $label ?? null;
$value    = $value ?? null;
$disabled = $disabled ?? false;
$required = $required ?? false;
$defaultValue = $defaultValue ?? null;
$invalid  = $errors->has($key) ? ' is-invalid' : '';
$attributes  = [
    'class' => 'form-control' . $invalid
];

if ($disabled) {
    $attributes['disabled'] = true;
}
if ($required) {
    $attributes['required'] = true;
}
if($defaultValue){
    $options = array_merge(['' => $defaultValue], $options);
}

?>
<div class="form-group">
    {{ Form::label($key, $label, ['class' => 'col-sm-2 control-label']) }}
    <div class="col-sm-10">
        {{ Form::select($key, $options, $value, $attributes) }}
        @if ($errors->has($key))
            <div class="invalid-feedback">
                @foreach ($errors->all($key) as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </div>
</div>

