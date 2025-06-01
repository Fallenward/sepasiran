@extends('admin.layout')

@section('template_title')
    ویرایش سگ
@endsection

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">ویرایش اطلاعات سگ: {{ $dog->title }}</h2>

    <form method="POST" action="{{ route('admin.dogs.update', $dog->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <ul class="nav nav-tabs" id="dogEditTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="mother-tab" data-bs-toggle="tab" data-bs-target="#mother" type="button" role="tab">اطلاعات مادر</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="father-tab" data-bs-toggle="tab" data-bs-target="#father" type="button" role="tab">اطلاعات پدر</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">اطلاعات عمومی</button>
            </li>
        </ul>

        <div class="tab-content mt-3">
            <!-- Mother Tab -->
            <div class="tab-pane fade show active" id="mother" role="tabpanel">
                <div class="card">
                    <div class="card-header">اطلاعات مادر</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="mother_name" class="form-label">نام مادر</label>
                            <input type="text" class="form-control" name="mother_name" value="{{ old('mother_name', $dog->mother_name) }}">
                        </div>

                        <div class="mb-3">
                            <label for="mother_breed_id" class="form-label">نژاد مادر</label>
                            <select class="form-select" name="mother_breed_id">
                                @foreach($breeds as $breed)
                                    <option value="{{ $breed->id }}" {{ $dog->mother_breed_id == $breed->id ? 'selected' : '' }}>
                                        {{ $breed->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <hr>
                        <h6>اجداد مادری:</h6>
                        <div class="mb-3">
                            <label for="maternal_grandmother_name" class="form-label">مادربزرگ مادری</label>
                            <input type="text" class="form-control" name="maternal_grandmother_name" value="{{ old('maternal_grandmother_name', $dog->maternal_grandmother_name) }}">
                        </div>
                        <div class="mb-3">
                            <label for="maternal_grandmother_breed_id" class="form-label">نژاد</label>
                            <select class="form-select" name="maternal_grandmother_breed_id">
                                @foreach($breeds as $breed)
                                    <option value="{{ $breed->id }}" {{ $dog->maternal_grandmother_breed_id == $breed->id ? 'selected' : '' }}>
                                        {{ $breed->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="maternal_grandfather_name" class="form-label">پدربزرگ مادری</label>
                            <input type="text" class="form-control" name="maternal_grandfather_name" value="{{ old('maternal_grandfather_name', $dog->maternal_grandfather_name) }}">
                        </div>
                        <div class="mb-3">
                            <label for="maternal_grandfather_breed_id" class="form-label">نژاد</label>
                            <select class="form-select" name="maternal_grandfather_breed_id">
                                @foreach($breeds as $breed)
                                    <option value="{{ $breed->id }}" {{ $dog->maternal_grandfather_breed_id == $breed->id ? 'selected' : '' }}>
                                        {{ $breed->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Father Tab -->
            <div class="tab-pane fade" id="father" role="tabpanel">
                <div class="card">
                    <div class="card-header">اطلاعات پدر</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="father_name" class="form-label">نام پدر</label>
                            <input type="text" class="form-control" name="father_name" value="{{ old('father_name', $dog->father_name) }}">
                        </div>

                        <div class="mb-3">
                            <label for="father_breed_id" class="form-label">نژاد پدر</label>
                            <select class="form-select" name="father_breed_id">
                                @foreach($breeds as $breed)
                                    <option value="{{ $breed->id }}" {{ $dog->father_breed_id == $breed->id ? 'selected' : '' }}>
                                        {{ $breed->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <hr>
                        <h6>اجداد پدری:</h6>
                        <div class="mb-3">
                            <label for="paternal_grandmother_name" class="form-label">مادربزرگ پدری</label>
                            <input type="text" class="form-control" name="paternal_grandmother_name" value="{{ old('paternal_grandmother_name', $dog->paternal_grandmother_name) }}">
                        </div>
                        <div class="mb-3">
                            <label for="paternal_grandmother_breed_id" class="form-label">نژاد</label>
                            <select class="form-select" name="paternal_grandmother_breed_id">
                                @foreach($breeds as $breed)
                                    <option value="{{ $breed->id }}" {{ $dog->paternal_grandmother_breed_id == $breed->id ? 'selected' : '' }}>
                                        {{ $breed->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="paternal_grandfather_name" class="form-label">پدربزرگ پدری</label>
                            <input type="text" class="form-control" name="paternal_grandfather_name" value="{{ old('paternal_grandfather_name', $dog->paternal_grandfather_name) }}">
                        </div>
                        <div class="mb-3">
                            <label for="paternal_grandfather_breed_id" class="form-label">نژاد</label>
                            <select class="form-select" name="paternal_grandfather_breed_id">
                                @foreach($breeds as $breed)
                                    <option value="{{ $breed->id }}" {{ $dog->paternal_grandfather_breed_id == $breed->id ? 'selected' : '' }}>
                                        {{ $breed->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- General Tab -->
            <div class="tab-pane fade" id="general" role="tabpanel">
                <div class="card">
                    <div class="card-header">اطلاعات عمومی</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="color" class="form-label">رنگ</label>
                            <input type="text" class="form-control" name="color" value="{{ old('color', $dog->color) }}">
                        </div>
                        <div class="mb-3">
                            <label for="age" class="form-label">سن</label>
                            <input type="number" class="form-control" name="age" value="{{ old('age', $dog->age) }}">
                        </div>
                        <div class="mb-3">
                            <label for="birth_date" class="form-label">تاریخ تولد</label>
                            <input type="date" class="form-control" name="birth_date" value="{{ old('birth_date', $dog->birth_date) }}">
                        </div>
                        <div class="mb-3">
                            <label for="cover_type" class="form-label">نوع پوشش</label>
                            <input type="text" class="form-control" name="cover_type" value="{{ old('cover_type', $dog->cover_type) }}">
                        </div>
                        <div class="mb-3">
                            <label for="eye_color" class="form-label">رنگ چشم</label>
                            <input type="text" class="form-control" name="eye_color" value="{{ old('eye_color', $dog->eye_color) }}">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">وضعیت</label>
                            <input type="text" class="form-control" name="status" value="{{ old('status', $dog->status) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">شجره نامه</label><br>
                            <input type="checkbox" name="shajare" value="1" {{ $dog->shajare ? 'checked' : '' }}> دارد
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
