@extends('admin.layout') {{-- or your dashboard layout --}}

@section('content')
<div class="container">
    <h2>افزودن سگ جدید</h2>

    <form action="{{ route('breeder.dashboard-store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>نام سگ (title)</label>
            <input type="text" name="title" class="form-control">
        </div>

        <div class="form-group">
            <label>رنگ</label>
            <input type="text" name="color" class="form-control">
        </div>

        <div class="form-group">
            <label>وضعیت (status)</label>
            <select name="status" class="form-control">
                <option value="ready">آماده</option>
                <option value="pending">در انتظار</option>
                <option value="sold">فروخته شده</option>
                <option value="waiting">منتظر</option>
            </select>
        </div>

        <div class="form-group">
            <label>سن</label>
            <input type="number" name="age" class="form-control">
        </div>

        <div class="form-group">
            <label>تاریخ تولد</label>
            <input type="date" name="birth_date" class="form-control">
        </div>

        <div class="form-group">
            <label>نوع پوشش</label>
            <input type="text" name="cover_type" class="form-control">
        </div>

        <div class="form-group">
            <label>رنگ چشم</label>
            <input type="text" name="eye_color" class="form-control">
        </div>

        <div class="form-check">
            <input type="checkbox" name="shajare" value="1" class="form-check-input">
            <label class="form-check-label">دارای شجره نامه</label>
        </div>

        <div class="form-group">
            <label>نژاد</label>
            <select name="breed_id" class="form-control">
                <option value="">-- انتخاب کنید --</option>
                @foreach($breeds as $breed)
                    <option value="{{ $breed->id }}">{{ $breed->name }}</option>
                @endforeach
            </select>
        </div>

        <hr>
        <h4>اطلاعات والدین</h4>

        <div class="form-group">
            <label>نام پدر</label>
            <input type="text" name="father_name" class="form-control">
        </div>

        <div class="form-group">
            <label>نژاد پدر</label>
            <select name="father_breed_id" class="form-control">
                <option value="">-- انتخاب کنید --</option>
                @foreach($breeds as $breed)
                    <option value="{{ $breed->id }}">{{ $breed->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>نام مادر</label>
            <input type="text" name="mother_name" class="form-control">
        </div>

        <div class="form-group">
            <label>نژاد مادر</label>
            <select name="mother_breed_id" class="form-control">
                <option value="">-- انتخاب کنید --</option>
                @foreach($breeds as $breed)
                    <option value="{{ $breed->id }}">{{ $breed->name }}</option>
                @endforeach
            </select>
        </div>

        <hr>
        <h4>پدربزرگ و مادربزرگ‌ها</h4>

        <div class="form-group">
            <label>مادربزرگ مادری</label>
            <input type="text" name="maternal_grandmother_name" class="form-control">
        </div>

        <div class="form-group">
            <label>نژاد مادربزرگ مادری</label>
            <select name="maternal_grandmother_breed_id" class="form-control">
                <option value="">-- انتخاب کنید --</option>
                @foreach($breeds as $breed)
                    <option value="{{ $breed->id }}">{{ $breed->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>پدربزرگ مادری</label>
            <input type="text" name="maternal_grandfather_name" class="form-control">
        </div>

        <div class="form-group">
            <label>نژاد پدربزرگ مادری</label>
            <select name="maternal_grandfather_breed_id" class="form-control">
                <option value="">-- انتخاب کنید --</option>
                @foreach($breeds as $breed)
                    <option value="{{ $breed->id }}">{{ $breed->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>مادربزرگ پدری</label>
            <input type="text" name="paternal_grandmother_name" class="form-control">
        </div>

        <div class="form-group">
            <label>نژاد مادربزرگ پدری</label>
            <select name="paternal_grandmother_breed_id" class="form-control">
                <option value="">-- انتخاب کنید --</option>
                @foreach($breeds as $breed)
                    <option value="{{ $breed->id }}">{{ $breed->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>پدربزرگ پدری</label>
            <input type="text" name="paternal_grandfather_name" class="form-control">
        </div>

        <div class="form-group">
            <label>نژاد پدربزرگ پدری</label>
            <select name="paternal_grandfather_breed_id" class="form-control">
                <option value="">-- انتخاب کنید --</option>
                @foreach($breeds as $breed)
                    <option value="{{ $breed->id }}">{{ $breed->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success mt-3">ثبت اطلاعات</button>
    </form>
</div>
@endsection
