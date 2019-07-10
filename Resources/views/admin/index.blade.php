@extends('admin.layouts.main')

@section('title', Translate::get('module_setting::admin/sidenav.global'))

@section('content')
    @include('setting::admin.components.top')

    <section class="box-typical">
        <div class="box-typical-body pb-1">
            <form action="{{ route('admin.setting.save') }}" method="POST">
                @csrf
                <section class="tabs-section tab-section__no-border">
                    <div class="tabs-section-nav">
                        <div class="tbl">
                            <ul class="nav" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active show" href="#tabs-2-tab-1" role="tab" data-toggle="tab"
                                       aria-selected="true">
                                    <span class="nav-link-in">
                                        {{ Translate::get('module_setting::admin/main.settings_tab_main') }}
                                    </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tabs-2-tab-2" role="tab" data-toggle="tab"
                                       aria-selected="false">
                                    <span class="nav-link-in">
                                        {{ Translate::get('module_setting::admin/main.settings_tab_mail') }}
                                    </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tabs-2-tab-3" role="tab" data-toggle="tab"
                                       aria-selected="false">
                                    <span class="nav-link-in">
                                        {{ Translate::get('module_setting::admin/main.settings_tab_information') }}
                                    </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div><!--.tabs-section-nav-->

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active show" id="tabs-2-tab-1">
                            <table class="display table table-striped table-bordered">
                                <tbody>
                                <tr>
                                    <td>{{ Translate::get('module_setting::admin/main.main_settings_name') }}</td>
                                    <td>
                                        <input type="text" value="{{ $configs['app.name'] }}" name="config[app.name]"
                                               class="form-control" placeholder="Geekcms">
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ Translate::get('module_setting::admin/main.main_settings_url') }}</td>
                                    <td>
                                        <input type="url" value="{{ $configs['app.url'] }}" name="config[app.url]"
                                               class="form-control" placeholder="https://geekcms.com">
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ Translate::get('module_setting::admin/main.main_settings_template') }}</td>
                                    <td>
                                        <select name="config[themes.default]" class="form-control">
                                            @foreach (Theme::all() as $theme)
                                                <option value="{{ $theme->name }}"
                                                        @if ($theme->name === $configs['themes.default']) selected="selected" @endif>{{ $theme->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ Translate::get('module_setting::admin/main.main_settings_language') }}</td>
                                    <td>
                                        <select name="config[app.locale]" class="form-control">
                                            @foreach (getSupportedLocales() as $locale => $lang)
                                                <option value="{{ $locale }}"
                                                        @if (!empty($configs['app.locale']) && $configs['app.locale'] === $locale) selected="selected" @endif>{{ array_get($lang, 'name', $locale) }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ Translate::get('module_setting::admin/main.main_settings_closed') }}</td>
                                    <td>
                                        <select name="config[app.close]" class="form-control">
                                            <option value="0"
                                                    @if (empty($configs['app.close']) || !(bool)$configs['app.close']) selected="selected" @endif>{{ Translate::get('module_setting::admin/main.main_settings_closed_enabled') }}</option>
                                            <option value="1"
                                                    @if (!empty($configs['app.close']) && (bool)$configs['app.close']) selected="selected" @endif>{{ Translate::get('module_setting::admin/main.main_settings_closed_closed') }}</option>
                                        </select>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div><!--.tab-pane-->
                        <div role="tabpanel" class="tab-pane fade" id="tabs-2-tab-2">
                            <table class="display table table-striped table-bordered">
                                <tbody>
                                <tr>
                                    <td>{{ Translate::get('module_setting::admin/main.mail_from_name') }}</td>
                                    <td>
                                        <input type="text" value="{{ $configs['mail.from.name'] }}"
                                               name="config[mail.from.name]" class="form-control"
                                               placeholder="Geekcms-mail">
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ Translate::get('module_setting::admin/main.mail_from_email') }}</td>
                                    <td>
                                        <input type="text" value="{{ $configs['mail.from.address'] }}"
                                               name="config[mail.from.address]" class="form-control"
                                               placeholder="geekcms@geekcms.com">
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ Translate::get('module_setting::admin/main.mail_driver') }}</td>
                                    <td>

                                        <select name="config[mail.driver]" class="form-control">
                                            @foreach (Gcms::getMailDrivers() as $driver)
                                                <option value="{{$driver}}"
                                                        @if ($configs['mail.driver'] === $driver) selected="selected" @endif>{{ucfirst($driver)}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ Translate::get('module_setting::admin/main.mail_host') }}</td>
                                    <td>
                                        <input type="text" value="{{ $configs['mail.host'] }}" name="config[mail.host]"
                                               class="form-control" placeholder="smtp.geekcms.com">
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ Translate::get('module_setting::admin/main.mail_port') }}</td>
                                    <td>
                                        <input type="text" value="{{ $configs['mail.port'] }}" name="config[mail.port]"
                                               class="form-control" placeholder="587">
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ Translate::get('module_setting::admin/main.mail_encryption') }}</td>
                                    <td>
                                        <input type="text" value="{{ $configs['mail.encryption'] }}"
                                               name="config[mail.encryption]" class="form-control" placeholder="tls">
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ Translate::get('module_setting::admin/main.mail_username') }}</td>
                                    <td>
                                        <input type="text" value="{{ $configs['mail.username'] }}"
                                               name="config[mail.username]" class="form-control" placeholder="username">
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ Translate::get('module_setting::admin/main.mail_password') }}</td>
                                    <td>
                                        <input type="text" value="{{ $configs['mail.password'] }}"
                                               name="config[mail.password]" class="form-control" placeholder="password">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div><!--.tab-pane-->
                        <div role="tabpanel" class="tab-pane fade" id="tabs-2-tab-3">
                            <table class="display table table-striped table-bordered">
                                <tbody>
                                <tr>
                                    <td>{{ Translate::get('module_setting::admin/main.information_framework_version') }}</td>
                                    <td>{{ App::VERSION() }}</td>
                                </tr>
                                <tr>
                                    <td>{{ Translate::get('module_setting::admin/main.information_cms_version') }}</td>
                                    <td>{{ Gcms::getVersion() }}</td>
                                </tr>
                                <tr>
                                    <td>{{ Translate::get('module_setting::admin/main.information_cms_developers') }}</td>
                                    <td>
                                        @foreach (Gcms::getAuthors() as $author)
                                            <a href="mailto:{{ $author['email'] }}">{{ $author['name'] }}</a>,&nbsp;
                                        @endforeach
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div><!--.tab-pane-->
                    </div><!--.tab-content-->

                    <div class="form-group text-center pt-3">
                        <button type="submit"
                                class="btn btn-inline btn-success btn-lg">{{Translate::get('module_setting::admin/main.save')}}</button>
                    </div>
                </section>
            </form>
        </div>
    </section>

@endsection