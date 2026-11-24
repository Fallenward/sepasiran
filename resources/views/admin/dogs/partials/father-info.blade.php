<div class="row gy-3">
    <div class="col-md-6">
        <div class="sp-admin-panel">
            <h3 class="h6 mb-3">پدر</h3>
            <dl class="sp-admin-kv mb-0">
                <dt>نام</dt><dd>{{ $dog->father_name ?: '—' }}</dd>
                <dt>نژاد</dt><dd>{{ optional($dog->fatherBreed)->name ?: '—' }}</dd>
            </dl>
        </div>
    </div>
    <div class="col-md-6">
        <div class="sp-admin-panel">
            <h3 class="h6 mb-3">والدین پدری</h3>
            <dl class="sp-admin-kv mb-0">
                <dt>پدربزرگ</dt>
                <dd>
                    {{ $dog->paternal_grandfather_name ?: '—' }}
                    <div class="text-muted small">{{ optional($dog->paternalGrandfatherBreed)->name ?: 'نژاد —' }}</div>
                </dd>
                <dt>مادربزرگ</dt>
                <dd>
                    {{ $dog->paternal_grandmother_name ?: '—' }}
                    <div class="text-muted small">{{ optional($dog->paternalGrandmotherBreed)->name ?: 'نژاد —' }}</div>
                </dd>
            </dl>
        </div>
    </div>
</div>