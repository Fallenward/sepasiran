@extends('layouts.app')

@section('template_title')
    Settings
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Settings') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('settings.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>

									<th >Color Setting Primary</th>
									<th >Color Setting Secondary</th>
									<th >Color Setting Third</th>
									<th >Color Setting Fourth</th>
									<th >Sms Setting Username</th>
									<th >Sms Setting Password</th>
									<th >Sms Setting Sender</th>
									<th >Sms Setting Apikey</th>
									<th >Footer Setting Enamad Logo</th>
									<th >Footer Setting Samandehi Logo</th>
									<th >Sms Setting Secretkey</th>
									<th >Footer Setting Useful Links</th>
									<th >Footer Setting Apex</th>
									<th >Footer Setting About</th>
									<th >Footer Setting Logo</th>
									<th >Fav Icon</th>
									<th >Footer Setting Social Twitter</th>
									<th >Footer Setting Social Telegram</th>
									<th >Footer Setting Social Instagram</th>
									<th >Footer Setting Social Linkedin</th>
									<th >About Setting About</th>
									<th >About Setting Img</th>
									<th >Comment Settings Type</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($settings as $setting)
                                        <tr>
                                            <td>{{ ++$i }}</td>

										<td >{{ $setting->color_setting_primary }}</td>
										<td >{{ $setting->color_setting_secondary }}</td>
										<td >{{ $setting->color_setting_third }}</td>
										<td >{{ $setting->color_setting_fourth }}</td>
										<td >{{ $setting->sms_setting_username }}</td>
										<td >{{ $setting->sms_setting_password }}</td>
										<td >{{ $setting->sms_setting_sender }}</td>
										<td >{{ $setting->sms_setting_apikey }}</td>
										<td >{{ $setting->footer_setting_enamad_logo }}</td>
										<td >{{ $setting->footer_setting_samandehi_logo }}</td>
										<td >{{ $setting->sms_setting_secretkey }}</td>
										<td >{{ $setting->footer_setting_useful_links }}</td>
										<td >{{ $setting->footer_setting_menu }}</td>
										<td >{{ $setting->footer_setting_about }}</td>
										<td >{{ $setting->footer_setting_logo }}</td>
										<td >{{ $setting->fav_icon }}</td>
										<td >{{ $setting->footer_setting_social_twitter }}</td>
										<td >{{ $setting->footer_setting_social_telegram }}</td>
										<td >{{ $setting->footer_setting_social_instagram }}</td>
										<td >{{ $setting->footer_setting_social_linkedin }}</td>
										<td >{{ $setting->about_setting_about }}</td>
										<td >{{ $setting->about_setting_img }}</td>
										<td >{{ $setting->comment_settings_type }}</td>

                                            <td>
                                                <form action="{{ route('settings.destroy', $setting->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('settings.show', $setting->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('settings.edit', $setting->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $settings->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
