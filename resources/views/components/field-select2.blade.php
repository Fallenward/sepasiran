<div class="form-group">
    <label for="{{ $id }}">{{ $label }}</label>
    <select class="form-control select2" id="{{ $id }}" name="{{ $id }}[]"
            @if($multiple ?? true) multiple @endif>

        @if(is_array($fields))
            @foreach($fields as $key => $value)
                <option value="{{ $key }}">
                    {{ $value }}
                </option>
            @endforeach
        @else
            @foreach($fields as $field)
                <option value="{{ $field->id }}">
                    {{ $field->title ?? $field->fullname ?? $field->name }}
                </option>
            @endforeach
        @endif
    </select>
</div>
