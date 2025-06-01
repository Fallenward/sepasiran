<div class="row p-1">
    <div class="col-md-12">
        <div class="form-group mb-2">
            <label>نام:</label>
            <p class="form-control">{{ $dogs->name ?? 'نامشخص' }}</p>
        </div>
        <div class="form-group mb-2">
            <label>نژاد:</label>
            <p class="form-control">{{ $dogs->breed->name ?? 'نامشخص' }}</p>
        </div>
        <div class="form-group mb-2">
            <label>رنگ:</label>
            <p class="form-control">{{ $dog->color ?? 'نامشخص' }}</p>
        </div>
        <div class="form-group mb-2">
            <label>سن:</label>
            <p class="form-control">{{ $dog->age ?? 'نامشخص' }}</p>
        </div>
        <div class="form-group mb-2">
            <label>نوع پوشش:</label>
            <p class="form-control">{{ $dog->cover_type ?? 'نامشخص' }}</p>
        </div>
        <div class="form-group mb-2">
            <label>رنگ چشم:</label>
            <p class="form-control">{{ $dog->eye_color ?? 'نامشخص' }}</p>
        </div>
        <div class="form-group mb-2">
            <label>شجره نامه:</label>
            <p class="form-control">{{ $dog->shajare ? 'بله' : 'خیر' }}</p>
        </div>
    </div>
</div>
