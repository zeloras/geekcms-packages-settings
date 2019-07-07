@extends('admin.layouts.main')

@section('title', \Translate::get('module_setting::admin/sidenav.variables'))

@section('content')
@include('setting::admin.components.top')


<section class="box-typical">
    <header class="box-typical-header">
        <div class="tbl-row">
            <div class="tbl-cell tbl-cell-title border-bottom">
                <h3>{{ \Translate::get('module_setting::admin/main.variables_list') }}</h3>
            </div>
        </div>
    </header>
    <div class="box-typical-body pt-3 pb-3">
        <div class="table-responsive container">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('admin.setting.variables.save') }}" method="POST">
                        @csrf
                        <table class="table table-bordered table-custom table-hover settings-container">
                            <thead>
                            <tr>
                                <th></th>
                                <th>
                                    {{\Translate::get('module_setting::admin/main.key')}}
                                </th>

                                <th>
                                    {{\Translate::get('module_setting::admin/main.value')}}
                                </th>
                                <th class="table-icon-cell table-actions"></th>
                            </tr>
                            </thead>
                            <tbody class="settings-container-wrap">
                            <tr id="configRow0" class="settings-container__line">
                                <td></td>
                                <td>
                                    <input data-key="key" name="configs[key][]" type="text" class="form-control" placeholder="{{\Translate::get('module_setting::admin/main.key')}}">
                                </td>
                                <td>
                                    <input data-key="value" type="text" name="configs[value][]" class="form-control" placeholder="{{\Translate::get('module_setting::admin/main.value')}}">
                                </td>
                                <td class="table-icon-cell">
                                    <button type="button" class="btn btn-primary settings-container-remove" title="{{\Translate::get('module_setting::admin/main.remove_variable')}}">
                                        <i class="fa fa-minus-circle"></i>
                                    </button>
                                    <button data-key="create" type="button" class="btn btn-success settings-container-add" title="{{\Translate::get('module_setting::admin/main.create_variable')}}">
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="form-group text-center pt-3">
                            <button type="submit" class="btn btn-inline btn-success btn-lg">{{\Translate::get('module_setting::admin/main.save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('script')
<script>
    var settings_list_admin = @json($configs);
</script>
@endpush