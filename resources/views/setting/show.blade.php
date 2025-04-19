@extends('layouts.app')

@section('template_title')
    {{ $setting->name ?? __('Show') . " " . __('Setting') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Setting</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('settings.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">

                                <div class="form-group mb-2 mb20">
                                    <strong>Color Setting Primary:</strong>
                                    {{ $setting->color_setting_primary }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Color Setting Secondary:</strong>
                                    {{ $setting->color_setting_secondary }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Color Setting Third:</strong>
                                    {{ $setting->color_setting_third }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Color Setting Fourth:</strong>
                                    {{ $setting->color_setting_fourth }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Sms Setting Username:</strong>
                                    {{ $setting->sms_setting_username }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Sms Setting Password:</strong>
                                    {{ $setting->sms_setting_password }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Sms Setting Sender:</strong>
                                    {{ $setting->sms_setting_sender }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Sms Setting Apikey:</strong>
                                    {{ $setting->sms_setting_apikey }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Footer Setting Enamad Logo:</strong>
                                    {{ $setting->footer_setting_enamad_logo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Footer Setting Samandehi Logo:</strong>
                                    {{ $setting->footer_setting_samandehi_logo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Sms Setting Secretkey:</strong>
                                    {{ $setting->sms_setting_secretkey }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Footer Setting Useful Links:</strong>
                                    {{ $setting->footer_setting_useful_links }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Footer Setting Apex:</strong>
                                    {{ $setting->footer_setting_menu }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Footer Setting About:</strong>
                                    {{ $setting->footer_setting_about }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Footer Setting Logo:</strong>
                                    {{ $setting->footer_setting_logo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fav Icon:</strong>
                                    {{ $setting->fav_icon ?? 'فاوآیکن تنظیم نشده' }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Footer Setting Social Twitter:</strong>
                                    {{ $setting->footer_setting_social_twitter }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Footer Setting Social Telegram:</strong>
                                    {{ $setting->footer_setting_social_telegram }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Footer Setting Social Instagram:</strong>
                                    {{ $setting->footer_setting_social_instagram }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Footer Setting Social Linkedin:</strong>
                                    {{ $setting->footer_setting_social_linkedin }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>About Setting About:</strong>
                                    {{ $setting->about_setting_about }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>About Setting Img:</strong>
                                    {{ $setting->about_setting_img }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Comment Settings Type:</strong>
                                    {{ $setting->comment_settings_type }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
