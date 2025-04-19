<div class="form-group">
    <label for="{{ $id }}">{{ $label }}</label>
    <input type="text"
           class="form-control"
           id="{{ $id }}"
           name="{{ $id }}"
           value="{{ $value ?? '' }}"
           placeholder="{{ $placeholder ?? '' }}"
           @if($required ?? false) required @endif
           @if($readonly ?? false) readonly @endif
           @if($disabled ?? false) disabled @endif>
</div>
