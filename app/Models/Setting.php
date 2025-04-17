<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 *
 * @property $id
 * @property $color_setting_primary
 * @property $color_setting_secondary
 * @property $color_setting_third
 * @property $color_setting_fourth
 * @property $sms_setting_username
 * @property $sms_setting_password
 * @property $sms_setting_sender
 * @property $sms_setting_apikey
 * @property $footer_setting_enamad_logo
 * @property $footer_setting_samandehi_logo
 * @property $sms_setting_secretkey
 * @property $footer_setting_useful_links
 * @property $footer_setting_apex
 * @property $footer_setting_about
 * @property $footer_setting_logo
 * @property $fav_icon
 * @property $footer_setting_social_twitter
 * @property $footer_setting_social_telegram
 * @property $footer_setting_social_instagram
 * @property $footer_setting_social_linkedin
 * @property $about_setting_about
 * @property $about_setting_img
 * @property $comment_settings_type
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Setting extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['color_setting_primary', 'color_setting_secondary', 'color_setting_third', 'color_setting_fourth', 'sms_setting_username', 'sms_setting_password', 'sms_setting_sender', 'sms_setting_apikey', 'footer_setting_enamad_logo', 'footer_setting_samandehi_logo', 'sms_setting_secretkey', 'footer_setting_useful_links', 'footer_setting_apex', 'footer_setting_about', 'footer_setting_logo', 'fav_icon', 'footer_setting_social_twitter', 'footer_setting_social_telegram', 'footer_setting_social_instagram', 'footer_setting_social_linkedin', 'about_setting_about', 'about_setting_img', 'comment_settings_type'];


}
