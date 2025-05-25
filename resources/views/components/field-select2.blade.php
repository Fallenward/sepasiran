<div class="form-group">
    <label for="{{ is_string($id) ? $id : 'invalid_id' }}">{{ is_string($label) ? $label : 'Label' }}</label>
    <select class="form-control select2" id="{{ is_string($id) ? $id : 'invalid_id' }}" name="{{ is_string($id) ? $id : 'invalid_name' }}[]"
        @if($multiple ?? true) multiple @endif>

        @if(is_array($fields))
            @foreach($fields as $key => $value)
                <option value="{{ $key }}">{{ is_string($value) ? $value : json_encode($value) }}</option>
            @endforeach
        @else
            @foreach($fields as $field)
                <option value="{{ $field->id }}">
                    {{ $field->title ?? $field->fullname ?? $field->name ?? 'بدون عنوان' }}
                </option>
            @endforeach
        @endif
    </select>
</div>
