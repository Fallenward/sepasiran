<div class="row p-1">
    <div class="col-md-12">
        <div class="form-group mb-2">
            <label>نام مادر:</label>
            <p class="form-control">{{ $dog->mother_name ?? 'نامشخص' }}</p>
        </div>
        <div class="form-group mb-2">
            <label>نژاد مادر:</label>
            <p class="form-control">{{ optional($dog->motherBreed)->name ?? 'نامشخص' }}</p>
        </div>
        <div class="form-group mb-2">
            <label>پدربزرگ مادری:</label>
            <p class="form-control">{{ $dog->maternal_grandfather_name ?? 'نامشخص' }}</p>
        </div>
        <div class="form-group mb-2">
            <label>نژاد پدربزرگ:</label>
            <p class="form-control">{{ optional($dog->maternalGrandfatherBreed)->name ?? 'نامشخص' }}</p>
        </div>
        <div class="form-group mb-2">
            <label>مادربزرگ مادری:</label>
            <p class="form-control">{{ $dog->maternal_grandmother_name ?? 'نامشخص' }}</p>
        </div>
        <div class="form-group mb-2">
            <label>نژاد مادربزرگ:</label>
            <p class="form-control">{{ optional($dog->maternalGrandmotherBreed)->name ?? 'نامشخص' }}</p>
        </div>
    </div>
</div>
