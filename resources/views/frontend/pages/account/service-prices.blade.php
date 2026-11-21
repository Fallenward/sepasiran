@extends('layouts.app')

@section('title', 'قیمت خدمات')

@section('content')
<div class="wrap" style="padding:32px 0 64px;max-width:760px">
    <div class="panel">
        <h1 style="margin:0 0 8px;font-size:1.45rem">قیمت خدمات من</h1>
        <p class="sp-booking__hint" style="margin:0 0 18px">این مبالغ در رزرو مستقیم مشتریان برای شما نمایش داده می‌شود. اگر خالی بماند، قیمت پیش‌فرض سامانه استفاده می‌شود.</p>

        @if(session('success'))
            <p style="color:var(--sp-green);margin:0 0 14px">{{ session('success') }}</p>
        @endif
        @if($errors->any())
            <ul style="margin:0 0 14px;padding-right:18px;color:#f2b4b4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="post" action="{{ route('account.service-prices.update') }}" class="sp-booking__main" style="display:grid;gap:18px">
            @csrf
            @foreach($rows as $row)
                <section class="sp-booking__locked-card" style="margin:0">
                    <span>{{ $row['label'] }}</span>
                    <div class="field" style="margin-top:10px">
                        <label for="price_{{ $row['key'] }}">قیمت پایه (تومان)</label>
                        <input class="num" type="number" min="0" step="1000" id="price_{{ $row['key'] }}"
                               name="prices[{{ $row['key'] }}][base_price]"
                               value="{{ old('prices.'.$row['key'].'.base_price', $row['base_price']) }}" required>
                        <p class="sp-booking__hint">پیش‌فرض سامانه: {{ number_format($row['default_price']) }} تومان</p>
                    </div>

                    @if($row['key'] === 'coach' && count($trainingTypes))
                        <div style="display:grid;gap:10px;margin-top:8px">
                            <strong style="font-size:0.92rem">هزینه اضافی نوع آموزش</strong>
                            @foreach($trainingTypes as $training)
                                <div class="field">
                                    <label for="surcharge_{{ $training['key'] }}">{{ $training['label'] }}</label>
                                    <input class="num" type="number" min="0" step="1000"
                                           id="surcharge_{{ $training['key'] }}"
                                           name="prices[{{ $row['key'] }}][training_surcharges][{{ $training['key'] }}]"
                                           value="{{ old('prices.'.$row['key'].'.training_surcharges.'.$training['key'], $row['training_surcharges'][$training['key']] ?? $training['surcharge']) }}">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>
            @endforeach

            <div class="sp-booking__actions">
                <a class="btn btn-ghost" href="{{ url()->previous() }}">بازگشت</a>
                <button type="submit" class="btn btn-primary">ذخیره قیمت‌ها</button>
            </div>
        </form>
    </div>
</div>
@endsection
