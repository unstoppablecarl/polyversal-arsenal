<?php
$label    = $label ?? null;
$value    = $value ?? null;
$disabled = $disabled ?? false;
$required = $required ?? false;
$invalid  = $errors->has($key) ? ' is-invalid' : '';
$options = $options ?? [];
$options  = array_merge([
    'class' => 'form-control' . $invalid
], $options);

if ($disabled) {
    $options['disabled'] = true;
}
if ($required) {
    $options['required'] = true;
}
?>
<div class="form-group">
    {{ Form::label($key, $label, ['class' => 'col-sm-2 control-label']) }}
    <div class="col-sm-10">
        {{ Form::textarea($key, $value, $options) }}
        @if ($errors->has($key))
            <div class="invalid-feedback">
                @foreach ($errors->get($key) as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </div>
</div>

