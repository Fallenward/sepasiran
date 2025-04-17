<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'color_setting_primary' => 'string',
			'color_setting_secondary' => 'string',
			'color_setting_third' => 'string',
			'color_setting_fourth' => 'string',
			'sms_setting_username' => 'string',
			'sms_setting_password' => 'string',
			'sms_setting_sender' => 'string',
			'sms_setting_apikey' => 'string',
			'footer_setting_enamad_logo' => 'string',
			'footer_setting_samandehi_logo' => 'string',
			'sms_setting_secretkey' => 'string',
			'footer_setting_about' => 'string',
			'footer_setting_logo' => 'string',
			'fav_icon' => 'string',
			'footer_setting_social_twitter' => 'string',
			'footer_setting_social_telegram' => 'string',
			'footer_setting_social_instagram' => 'string',
			'footer_setting_social_linkedin' => 'string',
			'about_setting_about' => 'string',
			'about_setting_img' => 'string',
			'comment_settings_type' => 'required',
        ];
    }
}
