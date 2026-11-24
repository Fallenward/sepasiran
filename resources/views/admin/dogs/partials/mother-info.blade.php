<div class="row gy-3">
    <div class="col-md-6">
        <div class="sp-admin-panel">
            <h3 class="h6 mb-3">مادر</h3>
            <dl class="sp-admin-kv mb-0">
                <dt>نام</dt><dd>{{ $dog->mother_name ?: '—' }}</dd>
                <dt>نژاد</dt><dd>{{ optional($dog->motherBreed)->name ?: '—' }}</dd>
            </dl>
        </div>
    </div>
    <div class="col-md-6">
        <div class="sp-admin-panel">
            <h3 class="h6 mb-3">والدین مادری</h3>
            <dl class="sp-admin-kv mb-0">
                <dt>پدربزرگ</dt>
                <dd>
                    {{ $dog->maternal_grandfather_name ?: '—' }}
                    <div class="text-muted small">{{ optional($dog->maternalGrandfatherBreed)->name ?: 'نژاد —' }}</div>
                </dd>
                <dt>مادربزرگ</dt>
                <dd>
                    {{ $dog->maternal_grandmother_name ?: '—' }}
                    <div class="text-muted small">{{ optional($dog->maternalGrandmotherBreed)->name ?: 'نژاد —' }}</div>
                </dd>
            </dl>
        </div>
    </div>
</div>