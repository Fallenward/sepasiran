<footer class="sp-footer">
    <div class="wrap sp-footer__grid">
        <div class="sp-footer__brand">
            <a class="sp-brand" href="{{ route('frontend.home') }}">
                <span class="sp-brand__fa">سپاسیران</span>
                <span class="sp-brand__en latin">Sepasiran</span>
            </a>
            <p>بازار سگ، خدمات و فروشگاه — مینیمال، مطمئن، فارسی.</p>
        </div>
        <div class="sp-footer__col">
            <h3>بازار</h3>
            <ul>
                <li><a href="{{ route('frontend.dog.supply.index') }}">سگ‌ها</a></li>
                <li><a href="{{ route('frontend.stores.index') }}">فروشگاه</a></li>
                <li><a href="{{ route('frontend.booking.index') }}">رزرو خدمت</a></li>
                <li><a href="{{ route('frontend.services.index') }}">خدمات</a></li>
            </ul>
        </div>
        <div class="sp-footer__col">
            <h3>حساب</h3>
            <ul>
                <li><a href="{{ route('login') }}">ورود</a></li>
                @auth
                    <li><a href="{{ route('user.dashboard') }}">داشبورد</a></li>
                @endauth
                <li><a href="{{ route('frontend.support.index') }}">پشتیبانی</a></li>
            </ul>
        </div>
        <div class="sp-footer__col">
            <h3>ارتباط</h3>
            <ul>
                <li><a href="{{ route('frontend.contact.index') }}">تماس با ما</a></li>
                <li><a href="{{ route('frontend.about') }}">درباره ما</a></li>
                <li><a href="{{ route('frontend.news.index') }}">اخبار</a></li>
            </ul>
        </div>
    </div>
    <div class="wrap sp-footer__bar">
        <span>© ۱۴۰۴ سپاسیران — همه حقوق محفوظ است.</span>
        <span class="latin">Sepasiran · dark / green / gold</span>
    </div>
</footer>
