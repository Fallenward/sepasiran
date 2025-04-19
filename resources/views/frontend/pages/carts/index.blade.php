@extends('frontend.layouts.app')

@section('title', $title)

@section('content')
    <div class="container mt-4">
        <div class="col-12 col-md-6">
            <h2>{{$title}}</h2>
            <x-title-divider></x-title-divider>
        </div>
        @if($carts->isEmpty())
            <div class="text-center p-5">
                <i class="fas fa-shopping-bag fa-10x text-muted"></i>
                <h4 class="mt-3 text-muted">سبد خرید شما خالی است!</h4>
                <p class="text-muted text-center">برای مشاهده و خرید دوره‌ها، به فروشگاه مراجعه کنید.</p>
                <a href="{{ route('frontend.courses') }}" class="btn btn-primary">مشاهده دوره‌ها</a>
            </div>
        @else
            <div class="cart-container">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="cart-container">
                            @foreach($carts as $cart)

                                <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                    <button class="text-primary btn remove-item" data-id="{{ $cart->id }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <img src="{{ asset('storage/' . $cart->course->image) }}" class="product-img me-3"
                                         alt="{{ $cart->course->title }}">
                                    <div class="flex-grow-1 me-2">
                                        <h5 class="mb-1">{{ $cart->course->title }}</h5>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-end">قیمت:</span>
                                            <div>
                                                <strong class="product-price text-start"
                                                        data-id="{{ $cart->id }}">{{ number_format($cart->price * $cart->quantity) }}</strong>
                                                <span>{!! $cart->course->getPriceUnit() !!}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <span class="text-end">تعداد:</span>
                                            <div class="d-flex align-items-center text-start">
                                                <button class="btn text-primary update-quantity" data-action="decrease"
                                                        data-id="{{ $cart->id }}">-
                                                </button>
                                                <span class="mx-2 quantity"
                                                      data-id="{{ $cart->id }}">{{ $cart->quantity }}</span>
                                                <button class="btn text-primary update-quantity" data-action="increase"
                                                        data-id="{{ $cart->id }}">+
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="summary-box p-4">
                            <h4 class="mb-3 text-center">🛒 خلاصه سفارش</h4>
                            <div id="loading-overlay" class="loading-overlay d-none">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">در حال بارگذاری...</span>
                                </div>
                            </div>
                            <div class="border-bottom pb-2 mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>مجموع سبد خرید:</span>
                                    <div>
                                        <strong id="total-price">{{ number_format($totalPrice) }}</strong>
                                        <span>{!! $cart->course->getPriceUnit() !!}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="border-bottom pb-2 mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>مالیات (10%):</span>
                                    <div>
                                        <strong id="tax-amount">+{{ number_format($tax) }}</strong>
                                        <span>{!! $cart->course->getPriceUnit() !!}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="border-bottom pb-2 mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>تخفیف شما:</span>
                                    <div>
                                        <strong class="text-success"
                                                id="discount-amount">-{{ number_format($discount) }}</strong>
                                        <span>{!! $cart->course->getPriceUnit() !!}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="pb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>کد تخفیف:</span>
                                    <input type="text" id="coupon-code" class="form-control form-control-sm w-50"
                                           placeholder="وارد کنید">
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <button id="apply-coupon" class="btn btn-secondary btn-sm">اعمال تخفیف</button>
                                    <button id="remove-coupon" class="btn btn-danger btn-sm d-none">حذف</button>
                                </div>
                            </div>
                            <div class="border-top pt-3 mt-3">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold fs-5">💳 مبلغ نهایی قابل پرداخت:</span>
                                    <div>
                                        <strong class="text-primary fs-5"
                                                id="final-price">{{ number_format($finalPrice) }}</strong>
                                        <span>{!! $cart->course->getPriceUnit() !!}</span>
                                    </div>
                                </div>
                            </div>
                            <div id="loading-container" class="d-flex justify-content-center align-items-center d-none"
                                 style="height: 100px;">
                                <div id="loading-animation" style="width: 80px; height: 80px;"></div>
                            </div>
                            <button class="btn btn-success w-100 mt-4 py-2 fs-5" id="payButton">
                                <i class="fas fa-check-circle"></i> ثبت سفارش و پرداخت
                                <span id="loadingIcon" style="display: none"><i class="fas fa-spinner fa-spin"></i></span>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {

            $('#coupon-code').on('keyup', function () {
                let couponCode = $(this).val().trim();
                if (couponCode === '') {
                    $('#apply-coupon').prop('disabled', true);
                } else {
                    $('#apply-coupon').prop('disabled', false);
                }
            });

            // بررسی وضعیت دکمه هنگام بارگذاری صفحه
            $('#coupon-code').trigger('keyup');

            function updateMinusButtons() {
                $('.update-quantity[data-action="decrease"]').each(function () {
                    let cartId = $(this).data('id');
                    let quantity = parseInt($('.quantity[data-id="' + cartId + '"]').text());
                    $(this).prop('disabled', quantity <= 1).css('opacity', quantity <= 1 ? 0 : 1);
                });
            }

            function updateSummary(response) {
                $('#total-price').text(response.total_price);
                $('#tax-amount').text('+' + response.tax);
                $('#discount-amount').text('-' + response.discount);
                $('#final-price').text(response.final_price);
            }

            $('.remove-item').click(function () {
                let cartId = $(this).data('id');
                let cartItem = $(this).closest('.d-flex.align-items-center.border-bottom.pb-3.mb-3');

                // نمایش اسپینر برای نشان دادن بارگذاری
                $('#loading-overlay').removeClass('d-none');

                $.ajax({
                    url: '{{ route("frontend.card.remove") }}',
                    type: 'POST',
                    data: {
                        cart_id: cartId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            // حذف آیتم از DOM با انیمیشن
                            cartItem.fadeOut(300, function () {
                                $(this).remove();

                                // اگر سبد خرید خالی شد، پیام مناسب نمایش داده شود
                                if ($('.cart-container .d-flex.align-items-center.border-bottom.pb-3.mb-3').length === 0) {
                                    $('.cart-container').html(`
                                        <div class="text-center p-5">
                                            <i class="fas fa-shopping-bag fa-10x text-muted"></i>
                                            <h4 class="mt-3 text-muted">سبد خرید شما خالی است!</h4>
                                            <p class="text-muted text-center">برای مشاهده و خرید دوره‌ها، به فروشگاه مراجعه کنید.</p>
                                            <a href="{{ route('frontend.courses') }}" class="btn btn-primary">مشاهده دوره‌ها</a>
                                        </div>
                                    `);
                                }

                                // به‌روزرسانی خلاصه سبد خرید
                                updateSummary(response);
                            });
                        } else {
                            showMessage('مشکلی پیش آمد، لطفا دوباره امتحان کنید.', 'error');
                        }
                    },
                    complete: function () {
                        // مخفی کردن اسپینر پس از اتمام درخواست
                        $('#loading-overlay').addClass('d-none');
                    }
                });
            });

            $('#apply-coupon').click(function () {
                let couponCode = $('#coupon-code').val();
                if (couponCode === '') {
                    showMessage('لطفاً کد تخفیف را وارد کنید.', 'error');
                    return;
                }

                // نمایش اسپینر
                $('#loading-overlay').removeClass('d-none');

                $.ajax({
                    url: '{{ route("frontend.card.apply.coupon") }}',
                    type: 'POST',
                    data: {
                        coupon: couponCode,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            // به‌روزرسانی خلاصه سبد خرید
                            updateSummary(response);

                            // غیرفعال کردن دکمه اعمال تخفیف و فعال کردن دکمه حذف تخفیف
                            $('#apply-coupon').addClass('d-none');
                            $('#remove-coupon').removeClass('d-none');
                            $('#coupon-code').prop('disabled', true);

                            // نمایش پیام موفقیت
                            showMessage('تخفیف با موفقیت اعمال شد.', 'success');
                        } else {
                            showMessage(response.message, 'error'); // نمایش پیام خطا
                        }
                    },
                    complete: function () {
                        // مخفی کردن اسپینر
                        $('#loading-overlay').addClass('d-none');
                    }
                });
            });

            $('#remove-coupon').click(function () {
                // نمایش اسپینر
                $('#loading-overlay').removeClass('d-none');

                $.ajax({
                    url: '{{ route("frontend.card.remove.coupon") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            // به‌روزرسانی خلاصه سبد خرید
                            updateSummary(response);

                            // فعال کردن دکمه اعمال تخفیف و غیرفعال کردن دکمه حذف تخفیف
                            $('#apply-coupon').removeClass('d-none');
                            $('#remove-coupon').addClass('d-none');
                            $('#coupon-code').prop('disabled', false).val('');

                            // نمایش پیام موفقیت
                            showMessage('تخفیف با موفقیت حذف شد.', 'success');
                        }
                    },
                    complete: function () {
                        // مخفی کردن اسپینر
                        $('#loading-overlay').addClass('d-none');
                    }
                });
            });

            $('.update-quantity').click(function () {
                let action = $(this).data('action');
                let cartId = $(this).data('id');
                let quantityElement = $('.quantity[data-id="' + cartId + '"]');
                let cartItem = quantityElement.closest('.d-flex.align-items-center.border-bottom.pb-3.mb-3');
                let priceElement = $('.product-price[data-id="' + cartId + '"]');
                let currentQuantity = parseInt(quantityElement.text());
                let newQuantity = action === "increase" ? currentQuantity + 1 : currentQuantity - 1;

                $.ajax({
                    url: '{{ route("frontend.card.update") }}',
                    type: 'POST',
                    data: {
                        cart_id: cartId,
                        quantity: newQuantity,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            if (response.removed) {
                                cartItem.fadeOut(300, function () {
                                    $(this).remove();
                                });
                            } else {
                                quantityElement.text(newQuantity);
                                priceElement.text(response.new_price);
                                updateMinusButtons();
                            }
                            updateSummary(response);
                        } else {
                            showMessage('مشکلی پیش آمد، لطفا دوباره امتحان کنید.', 'error');
                        }
                    }
                });
            });

            function showLoading() {
                $('#loading-overlay').removeClass('d-none');
            }

            function hideLoading() {
                $('#loading-overlay').addClass('d-none');
            }

            $('#apply-coupon, #remove-coupon, .update-quantity, .remove-item').click(function () {
                showLoading();
            });

            $(document).ajaxComplete(function () {
                hideLoading();
            });

            updateMinusButtons();

            $('#payButton').on('click', function () {
                var $button = $(this);
                var $loadingIcon = $('#loadingIcon');

                // Disable the button and show the loading icon
                $button.prop('disabled', true);
                $loadingIcon.show();

                $.ajax({
                    url: '{{route('frontend.card.checkout')}}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {

                        if (data.status === true) {
                            window.location.href = data.payment_url; // Redirect to the payment gateway
                        } else {
                            showMessage('خطا در پرداخت', 'error');
                        }
                    },
                    error: function (xhr, status, error) {

                    },
                    complete: function () {
                        // $loadingIcon.hide();
                        // $button.prop('disabled', false);
                    }
                });
            });

        });
    </script>
@endsection
