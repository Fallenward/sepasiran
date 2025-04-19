<div class="animate__animated animate__fadeInUp card shadow p-4">
    <h3>ارسال نظر</h3>
    <form action="{{ route('frontend.blogs.store.comment') }}" method="POST">
        @csrf
        @if(!auth()->check())
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">نام</label>
                        <input name="name" type="text" class="form-control" id="name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="family">نام خانوادگی</label>
                        <input name="family" type="text" class="form-control" id="family" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">ایمیل</label>
                        <input name="email" type="email" class="form-control" id="email" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone">شماره موبایل</label>
                        <input name="phone" type="text" class="form-control" id="phone" required>
                    </div>
                </div>
            </div>
            <input type="hidden" name="blog_id" value="{{$blog->id}}">
        @endif
        <div class="form-group">
            <label for="comment">متن نظر</label>
            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">ارسال نظر</button>
    </form>
</div>
