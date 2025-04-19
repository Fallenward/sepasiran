<div class="bottom-nav">
    <a href="#" class="{{ request()->routeIs('frontend.home') ? 'active' : '' }}">
        <i class="fas fa-home"></i><span>خانه</span>
    </a>
    <a href="#" class="{{ request()->routeIs('frontend.courses') ? 'active' : '' }}">
        <i class="fas fa-store"></i><span>فروشگاه</span>
    </a>
    <a href="">
        <i class="fas fa-user"></i><span>پروفایل</span>
    </a>
    @if(!is_null(auth()->user()))
        <a href="#" class="{{ request()->routeIs('frontend.cart') ? 'active' : '' }}">
            <i class="fas fa-shopping-bag"></i><span>سبد خرید</span>
            @php
               $cartCount =  \App\Models\Cart::where('user_id',auth()->user()->id)->where('status',\App\Models\Cart::STATUS_ACTIVE)->count();
            @endphp
            @if($cartCount>0)
                <span class="cart-counter">{{$cartCount}}</span>
            @endif
        </a>

    @else
        <a href="#" class="" >
            <i class="fas fa-user"></i><span>ورود / ثبت نام</span>
        </a>
    @endif
</div>
