<footer>
    <div class="footer-line"></div>
    <div class="footer-top">
        <div class="container footer-container">
            <div class="footer-flex">
                <i class="fa fa-wifi footer-icon"></i>
                <div class="footer-text">
                    <span class="footer-title">پاسخگوی شما عزیزان هستیم</span>
                    <small class="footer-subtitle">شماره خود را وارد کنید!</small>
                </div>
            </div>
            <div>
                <input type="text" class="footer-input" placeholder="شماره خود را وارد کنید">
                <button class="btn btn-primary footer-button">ثبت شماره <i class="fa fa-arrow-left"></i></button>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="d-flex">
                <div class="col-12 col-md-4 d-flex">

                    <div class="col-12 col-md-6">
                        <div class="footer-menu-title">
                            <h5>لینک های مفید</h5>
                        </div>
                        <div class="footer-menu-links">
                            <a href="#" class="footer-link">لورم ایپسوم</a>
                            <a href="#" class="footer-link">لورم ایپسوم</a>
                            <a href="#" class="footer-link">لورم ایپسوم</a>
                            <a href="#" class="footer-link">لورم ایپسوم</a>
                        </div>
                    </div>
{{--                    <div class="col-12 col-md-6">--}}
{{--                        <div class="footer-menu-title">--}}
{{--                            <h5>لینک های مفید</h5>--}}
{{--                        </div>--}}
{{--                        <div class="footer-menu-items">--}}
{{--                            @if(!empty($settings) && $settings->footer_setting_useful_links)--}}
{{--                                @foreach($settings->footer_setting_useful_links as $item)--}}
{{--                                    <a class="btn btn-link" href="{{$item['url']}}">{{$item['title']}}</a>--}}
{{--                              @endforeach--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <div class="col-12 col-md-6">
                        <div class="footer-menu-title">
                            <h5>سپاس ایران</h5>
                        </div>
                        <div class="footer-menu-links">
                            <a href="#" class="footer-link">لورم ایپسوم</a>
                            <a href="#" class="footer-link">لورم ایپسوم</a>
                            <a href="#" class="footer-link">لورم ایپسوم</a>
                            <a href="#" class="footer-link">لورم ایپسوم</a>
                        </div>
                    </div>

                    <style>
                        .footer-menu-title h5 {
                            font-size: 1.25rem;
                            font-weight: bold;
                            color: #007bff; /* رنگ آبی برای عنوان */
                            margin-bottom: 15px;
                        }

                        .footer-menu-links {
                            padding: 10px;
                        }

                        .footer-link {
                            display: block;
                            padding: 10px;
                            color: #333; /* رنگ متن لینک‌ها */
                            text-decoration: none;
                            font-size: 1rem;
                            font-weight: 600;
                            border: 1px solid #e0e0e0; /* حاشیه سبک برای هر لینک */
                            margin-bottom: 5px;
                            border-radius: 5px; /* گوشه‌های نرم */
                            transition: background-color 0.3s ease, color 0.3s ease; /* انیمیشن تغییر رنگ */
                        }

                        .footer-link:hover {
                            background-color: #007bff; /* رنگ پس‌زمینه روی هاور */
                            color: white; /* رنگ متن سفید روی هاور */
                        }
                    </style>

                    {{--                    <div class="col-12 col-md-6">--}}
{{--                        <div class="footer-menu-title">--}}
{{--                            <h5>سپاس ایران</h5>--}}
{{--                        </div>--}}
{{--                        <div class="footer-menu-items">--}}
{{--                            @if(!empty($settings) && !empty($settings->footer_setting_menu))--}}
{{--                                @foreach($settings->footer_setting_menu as $item)--}}
{{--                                    <a class="btn btn-link" href="{{ $item['url'] }}">{{ $item['title'] }}</a>--}}
{{--                                @endforeach--}}
{{--                            @endif--}}

{{--                        </div>--}}
{{--                    </div>--}}

                </div>
                <div class="col-12 col-md-4">
                    <div class="text-center">
                        <img class="img-fluid w-50" src="{{ asset(optional($settings)->footer_setting_logo ?? 'storage/Annotation 2025-04-19 110311.jpg') }}">
{{--                        <p>{{ optional($settings)->footer_setting_about }}</p>--}}
                        <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد کتابهای زیادی در شصت و سه درصد گذشته حال و آینده شناخت فراوان جامعه و متخصصان را می طلبد</p>
                        <div class="text-center footer-socials">
                            <a href="{{ optional($settings)->footer_setting_social_twitter ?? '#' }}">
                                <i class="fab fa-2x fa-twitter"></i>
                            </a>

                            <a href="{{ optional($settings)->footer_setting_social_instagram ?? '#' }}">
                                <i class="fab fa-2x fa-instagram"></i>
                            </a>

                            <a href="{{ optional($settings)->footer_setting_social_telegram ?? '#' }}">
                                <i class="fab fa-2x fa-telegram-plane"></i>
                            </a>

                            <a href="{{ optional($settings)->footer_setting_social_linkedin ?? '#' }}">
                                <i class="fab fa-2x fa-linkedin-in"></i>
                            </a>

                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="col-12 col-md-12">
                        <div class="footer-menu-title">
                            <h5>نماد اعتماد</h5>
                        </div>
                        <div class="footer-certificates">
                            <div class="col-6 col-md-6">
                                <div class="footer-menu-items">
                                    <img class="img-fluid" src="{{ asset(optional($settings)->footer_setting_enamad_logo ?? 'storage/Annotation 2025-04-19 125731.jpg') }}">
                                </div>
                            </div>

                            <div class="col-6 col-md-6">
                                <div class="footer-menu-items">
                                    <img class="img-fluid" src="{{ asset(optional($settings)->footer_setting_samandehi_logo ?? 'storage/Annotation 2025-04-19 125650.jpg') }}">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright text-center">
        <a href="https://kiboweb.com" class="btn btn-link text-decoration-none text-black-50">طراحی شده توسط کیبووب</a>
    </div>
</footer>
