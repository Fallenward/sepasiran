<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="color_setting_primary" class="form-label">{{ __('Color Setting Primary') }}</label>
            <input type="text" name="color_setting_primary" class="form-control @error('color_setting_primary') is-invalid @enderror" value="{{ old('color_setting_primary', $setting?->color_setting_primary) }}" id="color_setting_primary" placeholder="Color Setting Primary">
            {!! $errors->first('color_setting_primary', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="color_setting_secondary" class="form-label">{{ __('Color Setting Secondary') }}</label>
            <input type="text" name="color_setting_secondary" class="form-control @error('color_setting_secondary') is-invalid @enderror" value="{{ old('color_setting_secondary', $setting?->color_setting_secondary) }}" id="color_setting_secondary" placeholder="Color Setting Secondary">
            {!! $errors->first('color_setting_secondary', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="color_setting_third" class="form-label">{{ __('Color Setting Third') }}</label>
            <input type="text" name="color_setting_third" class="form-control @error('color_setting_third') is-invalid @enderror" value="{{ old('color_setting_third', $setting?->color_setting_third) }}" id="color_setting_third" placeholder="Color Setting Third">
            {!! $errors->first('color_setting_third', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="color_setting_fourth" class="form-label">{{ __('Color Setting Fourth') }}</label>
            <input type="text" name="color_setting_fourth" class="form-control @error('color_setting_fourth') is-invalid @enderror" value="{{ old('color_setting_fourth', $setting?->color_setting_fourth) }}" id="color_setting_fourth" placeholder="Color Setting Fourth">
            {!! $errors->first('color_setting_fourth', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="sms_setting_username" class="form-label">{{ __('Sms Setting Username') }}</label>
            <input type="text" name="sms_setting_username" class="form-control @error('sms_setting_username') is-invalid @enderror" value="{{ old('sms_setting_username', $setting?->sms_setting_username) }}" id="sms_setting_username" placeholder="Sms Setting Username">
            {!! $errors->first('sms_setting_username', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="sms_setting_password" class="form-label">{{ __('Sms Setting Password') }}</label>
            <input type="text" name="sms_setting_password" class="form-control @error('sms_setting_password') is-invalid @enderror" value="{{ old('sms_setting_password', $setting?->sms_setting_password) }}" id="sms_setting_password" placeholder="Sms Setting Password">
            {!! $errors->first('sms_setting_password', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="sms_setting_sender" class="form-label">{{ __('Sms Setting Sender') }}</label>
            <input type="text" name="sms_setting_sender" class="form-control @error('sms_setting_sender') is-invalid @enderror" value="{{ old('sms_setting_sender', $setting?->sms_setting_sender) }}" id="sms_setting_sender" placeholder="Sms Setting Sender">
            {!! $errors->first('sms_setting_sender', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="sms_setting_apikey" class="form-label">{{ __('Sms Setting Apikey') }}</label>
            <input type="text" name="sms_setting_apikey" class="form-control @error('sms_setting_apikey') is-invalid @enderror" value="{{ old('sms_setting_apikey', $setting?->sms_setting_apikey) }}" id="sms_setting_apikey" placeholder="Sms Setting Apikey">
            {!! $errors->first('sms_setting_apikey', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="footer_setting_enamad_logo" class="form-label">{{ __('Footer Setting Enamad Logo') }}</label>
            <input type="text" name="footer_setting_enamad_logo" class="form-control @error('footer_setting_enamad_logo') is-invalid @enderror" value="{{ old('footer_setting_enamad_logo', $setting?->footer_setting_enamad_logo) }}" id="footer_setting_enamad_logo" placeholder="Footer Setting Enamad Logo">
            {!! $errors->first('footer_setting_enamad_logo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="footer_setting_samandehi_logo" class="form-label">{{ __('Footer Setting Samandehi Logo') }}</label>
            <input type="text" name="footer_setting_samandehi_logo" class="form-control @error('footer_setting_samandehi_logo') is-invalid @enderror" value="{{ old('footer_setting_samandehi_logo', $setting?->footer_setting_samandehi_logo) }}" id="footer_setting_samandehi_logo" placeholder="Footer Setting Samandehi Logo">
            {!! $errors->first('footer_setting_samandehi_logo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="sms_setting_secretkey" class="form-label">{{ __('Sms Setting Secretkey') }}</label>
            <input type="text" name="sms_setting_secretkey" class="form-control @error('sms_setting_secretkey') is-invalid @enderror" value="{{ old('sms_setting_secretkey', $setting?->sms_setting_secretkey) }}" id="sms_setting_secretkey" placeholder="Sms Setting Secretkey">
            {!! $errors->first('sms_setting_secretkey', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="footer_setting_useful_links" class="form-label">{{ __('Footer Setting Useful Links') }}</label>
            <input type="text" name="footer_setting_useful_links" class="form-control @error('footer_setting_useful_links') is-invalid @enderror" value="{{ old('footer_setting_useful_links', $setting?->footer_setting_useful_links) }}" id="footer_setting_useful_links" placeholder="Footer Setting Useful Links">
            {!! $errors->first('footer_setting_useful_links', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="footer_setting_apex" class="form-label">{{ __('Footer Setting Apex') }}</label>
            <input type="text" name="footer_setting_apex" class="form-control @error('footer_setting_apex') is-invalid @enderror" value="{{ old('footer_setting_apex', $setting?->footer_setting_apex) }}" id="footer_setting_apex" placeholder="Footer Setting Apex">
            {!! $errors->first('footer_setting_apex', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="footer_setting_about" class="form-label">{{ __('Footer Setting About') }}</label>
            <input type="text" name="footer_setting_about" class="form-control @error('footer_setting_about') is-invalid @enderror" value="{{ old('footer_setting_about', $setting?->footer_setting_about) }}" id="footer_setting_about" placeholder="Footer Setting About">
            {!! $errors->first('footer_setting_about', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="footer_setting_logo" class="form-label">{{ __('Footer Setting Logo') }}</label>
            <input type="text" name="footer_setting_logo" class="form-control @error('footer_setting_logo') is-invalid @enderror" value="{{ old('footer_setting_logo', $setting?->footer_setting_logo) }}" id="footer_setting_logo" placeholder="Footer Setting Logo">
            {!! $errors->first('footer_setting_logo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fav_icon" class="form-label">{{ __('Fav Icon') }}</label>
            <input type="text" name="fav_icon" class="form-control @error('fav_icon') is-invalid @enderror" value="{{ old('fav_icon', $setting?->fav_icon) }}" id="fav_icon" placeholder="Fav Icon">
            {!! $errors->first('fav_icon', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="footer_setting_social_twitter" class="form-label">{{ __('Footer Setting Social Twitter') }}</label>
            <input type="text" name="footer_setting_social_twitter" class="form-control @error('footer_setting_social_twitter') is-invalid @enderror" value="{{ old('footer_setting_social_twitter', $setting?->footer_setting_social_twitter) }}" id="footer_setting_social_twitter" placeholder="Footer Setting Social Twitter">
            {!! $errors->first('footer_setting_social_twitter', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="footer_setting_social_telegram" class="form-label">{{ __('Footer Setting Social Telegram') }}</label>
            <input type="text" name="footer_setting_social_telegram" class="form-control @error('footer_setting_social_telegram') is-invalid @enderror" value="{{ old('footer_setting_social_telegram', $setting?->footer_setting_social_telegram) }}" id="footer_setting_social_telegram" placeholder="Footer Setting Social Telegram">
            {!! $errors->first('footer_setting_social_telegram', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="footer_setting_social_instagram" class="form-label">{{ __('Footer Setting Social Instagram') }}</label>
            <input type="text" name="footer_setting_social_instagram" class="form-control @error('footer_setting_social_instagram') is-invalid @enderror" value="{{ old('footer_setting_social_instagram', $setting?->footer_setting_social_instagram) }}" id="footer_setting_social_instagram" placeholder="Footer Setting Social Instagram">
            {!! $errors->first('footer_setting_social_instagram', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="footer_setting_social_linkedin" class="form-label">{{ __('Footer Setting Social Linkedin') }}</label>
            <input type="text" name="footer_setting_social_linkedin" class="form-control @error('footer_setting_social_linkedin') is-invalid @enderror" value="{{ old('footer_setting_social_linkedin', $setting?->footer_setting_social_linkedin) }}" id="footer_setting_social_linkedin" placeholder="Footer Setting Social Linkedin">
            {!! $errors->first('footer_setting_social_linkedin', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="about_setting_about" class="form-label">{{ __('About Setting About') }}</label>
            <input type="text" name="about_setting_about" class="form-control @error('about_setting_about') is-invalid @enderror" value="{{ old('about_setting_about', $setting?->about_setting_about) }}" id="about_setting_about" placeholder="About Setting About">
            {!! $errors->first('about_setting_about', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="about_setting_img" class="form-label">{{ __('About Setting Img') }}</label>
            <input type="text" name="about_setting_img" class="form-control @error('about_setting_img') is-invalid @enderror" value="{{ old('about_setting_img', $setting?->about_setting_img) }}" id="about_setting_img" placeholder="About Setting Img">
            {!! $errors->first('about_setting_img', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="comment_settings_type" class="form-label">{{ __('Comment Settings Type') }}</label>
            <input type="text" name="comment_settings_type" class="form-control @error('comment_settings_type') is-invalid @enderror" value="{{ old('comment_settings_type', $setting?->comment_settings_type) }}" id="comment_settings_type" placeholder="Comment Settings Type">
            {!! $errors->first('comment_settings_type', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>