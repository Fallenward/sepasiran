<div class="row p-1">
    <div class="col-md-12">
        <div class="form-group mb-2">
            <label>نام پدر:</label>
            <p class="form-control">{{ $dog->father_name ?? 'نامشخص' }}</p>
        </div>
        <div class="form-group mb-2">
            <label>نژاد پدر:</label>
            <p class="form-control">{{ optional($dog->fatherBreed)->name ?? 'نامشخص' }}</p>
        </div>
        <div class="form-group mb-2">
            <label>پدربزرگ پدری:</label>
            <p class="form-control">{{ $dog->paternal_grandfather_name ?? 'نامشخص' }}</p>
        </div>
        <div class="form-group mb-2">
            <label>نژاد پدربزرگ:</label>
            <p class="form-control">{{ optional($dog->paternalGrandfatherBreed)->name ?? 'نامشخص' }}</p>
        </div>
        <div class="form-group mb-2">
            <label>مادربزرگ پدری:</label>
            <p class="form-control">{{ $dog->paternal_grandmother_name ?? 'نامشخص' }}</p>
        </div>
        <div class="form-group mb-2">
            <label>نژاد مادربزرگ:</label>
            <p class="form-control">{{ optional($dog->paternalGrandmotherBreed)->name ?? 'نامشخص' }}</p>
        </div>
    </div>
</div>
