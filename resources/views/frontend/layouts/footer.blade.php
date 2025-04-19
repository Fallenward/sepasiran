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
                        <div class="footer-menu-items">
                            @foreach($settings->footer_setting_useful_links as $item)
                                <a class="btn btn-link" href="{{$item['url']}}">{{$item['title']}}</a>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="footer-menu-title">
                            <h5>اپکس</h5>
                        </div>
                        <div class="footer-menu-items">
                            @foreach($settings->footer_setting_menu as $item)
                                <a class="btn btn-link" href="{{$item['url']}}">{{$item['title']}}</a>
                            @endforeach
                        </div>
                    </div>

                </div>
                <div class="col-12 col-md-4">
                    <div class="text-center">
                        <img class="img-fluid w-50" src="{{asset($settings->footer_setting_logo)}}">
                        <p>{{$settings->footer_setting_about}}</p>
                        <div class="text-center footer-socials">
                            <a href="{{$settings->footer_setting_social_twitter}}"> <i class="fab fa-2x fa-twitter"></i></a>
                            <a href="{{$settings->footer_setting_social_instagram}}"> <i class="fab fa-2x fa-instagram"></i></a>
                            <a href="{{$settings->footer_setting_social_telegram}}"> <i class="fab fa-2x fa-telegram-plane"></i></a>
                            <a href="{{$settings->footer_setting_social_linkedin}}"> <i class="fab fa-2x fa-linkedin-in"></i></a>
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
                                    <img class="img-fluid" src="{{$settings->footer_setting_enamad_logo}}">
                                </div>
                            </div>
                            <div class="col-6 col-md-6">
                                <div class="footer-menu-items">
                                    <img class="img-fluid" src="{{$settings->footer_setting_samandehi_logo}}">
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
