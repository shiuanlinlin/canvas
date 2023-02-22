@extends('layouts.default')

@section('pageTitle', __('src.房產資料建立'))

@include('base.plugins', ['plugins' => ["date_range_picker"]])

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        .s_img_labeBox{ position: relative; }
        .s_img_labeBox span { display: inline-block; padding: 2px 10px; border: 2px solid rgb(227, 76, 0); background: rgb(227, 76, 0);  color: rgb(255, 255, 255); font-size: 14px; position: absolute; cursor: pointer;}
        .s_img_labeBox span.active{border: 2px dashed #000; background: red;}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div class="w-100 d-flex justify-content-between">
            <h1>{{__('src.房產資料建立')}}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-body pt-0">
                    {{Form::open(['route' => "estate.changeBG", 'files' => true, 'id' => 'changeBgForm'])}}
                    <input type="hidden" name="company" value="{{$companyData->id ?? null}}"/>
                    <input type="hidden" name="case" value="{{$caseData->id ?? null}}"/>
                    <input type="file" class="d-none" name="background" id="background" accept="image/png,image/jpeg"/>
                    {{Form::close()}}

                    <div class="d-flex flex-row justify-content-between pt-4 pb-3">
                        <div class="d-flex w-100">
                            <div class="search-input-group">
                                <select class="form-control default-SumoSelect" id="company" name="company">
                                    <option value="">{{columnTip('公司','請選擇')}}</option>
                                    @foreach($companyList as $company)
                                        <option value="{{$company->id}}" {{$companyData && $companyData->id === $company->id ? "SELECTED" : ''}}>{{$company->short_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="search-input-group">
                                <select class="form-control default-SumoSelect" id="case" name="case">
                                    <option value="">{{__('src.建案')}}</option>
                                    @foreach($caseList as $case)
                                        <option value="{{$case->id}}" {{$caseData && $caseData->id === $case->id ? "SELECTED" : ''}}>{{$case->short_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="search-input-group">
                                <select class="form-control default-SumoSelect" id="type" name="type">
                                    <option value="info" {{$type === 'info' ? 'SELECTED' : ''}}>{{__('src.全區資料')}}</option>
                                    <option value="standard" {{$type === 'standard' ? 'SELECTED' : ''}}>{{__('src.標準套型')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="search-btn-group text-nowrap">
                            @if($companyData && $caseData)
                                <!-- <button class="btn btn-gk-search" onclick="document.getElementById('background').click()">{{__('src.更換背景總圖')}}</button> -->
                                <button type="button" class="btn btn-gk-search" id="s_ImageLabel">{{__('src.更換背景總圖')}}</button>
                                <button type="button" class="btn btn-gk-view" data-toggle="modal" id="add_house_real" data-target="#addBuildingModal">{{__('src.添加房屋')}}</button>
                                <!-- <button class="btn btn-gk-del">{{__('src.鎖定')}}</button> -->
                            @endif
                        </div>
                    </div>
                    <!-- 原始的修改圖片 -->
                    <!-- <a href="#" class="d-block">
                        @if($companyData && $caseData)
                            <?php
                                $bg_image = "";
                                if($caseData->background_image){ $bg_image = $caseData->background_image;}
                            ?>
                            <img src="{{-- url($bg_image) --}}" class="img-fluid" alt="" style="width: 100%;height: auto">
                        @endif
                    </a> -->
                    @if($companyData && $caseData)
                    <!-- <div class="card card-default">
                        <div class="card-body"> -->
                            <div class="search-btn-group text-nowrap">
                                <!-- 新增按鈕 -->
                                <!-- <button type="button" class="btn btn-gk-view" id="s_addbuttonLabel">添加標籤</button> -->
                                <div class="d-none">
                                    <input type="file" id="s_ImageLabelfile">更換圖片
                                </div>
                                <!-- <button type="button" class="btn btn-gk-search" id="s_ImageLabel">{{--__('src.更換背景總圖')--}}</button> -->

                                <!-- 選擇區域、棟別、樓層、戶 -->
                                <div class="d-flex" id="s_ChangeTypeImage">
                                    <div class="form-group mt-4">
                                        <select class="form-control" data-status="area">
                                            <!-- 用foreach來產生option -->
                                            <option value="A0">全區</option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-4">
                                        <select class="form-control" data-status="building" id="chuang_pic">
                                        <option value="0">棟別</option>
                                        @foreach($chuangList as $chu)
                                            <option value="{{$chu->id}}" <?php if($chu->id==$chuangid){echo 'selected'; } ?> >{{$chu->name}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mt-4">
                                        <select class="form-control" data-status="floor" id="hu_pic">
                                        <option value="0">樓層</option>
                                        @foreach($louData_all as $lou)
                                            <option value="{{$lou->id}}" <?php if($louid==$lou->id){ echo "selected"; }?> > {{$lou->name}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mt-4 d-none">
                                        <select class="form-control" data-status="household">
                                        <option value="0">戶別</option>
                                        <option value="H1">1</option>
                                        <option value="H2">2</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- 添加標籤 -->
                            <div class="s_BtnGroud my-2">
                                <button type="button" class="btn btn-gk-view text-secondary border" data-simg="left"><i class="fa-solid fa-left-long"></i></button>
                                <button type="button" class="btn btn-gk-view text-secondary border" data-simg="up"><i class="fa-solid fa-up-long"></i></button>
                                <button type="button" class="btn btn-gk-view text-secondary border" data-simg="down"><i class="fa-solid fa-down-long"></i></button>
                                <button type="button" class="btn btn-gk-view text-secondary border" data-simg="right"><i class="fa-solid fa-right-long"></i></button>

                                <button type="button" class=" btn btn-gk-print" id="s_SavebuttonLabel">{{__('src.儲存標籤')}}</button>
                            </div>
                            <!-- 背景圖 -->
                            <div id="s_ImageLabelAll">
                                <!-- 用foreach產生圖片 -->
                                <!-- 全區 -->
                                <div class="s_img_labeBox d-block active" data-imgidbox="A0" data-labelname="imgbox">
                                    <img id="img_0" src="\{{optional($caseData)->background_image}}" class="img-fluid" alt="" style="width: 100%;height: auto">
                                </div>

                                @foreach($caseLou as $lou)
                                    <div class="s_img_labeBox d-none" data-imgidbox="A0_F{{$lou->id}}" data-labelname="imgbox">
                                        <?php 
                                            $backslash = '';
                                            if($lou->floor_plan){ $backslash = '\\'; }
                                        ?>
                                        <img id="img_{{$lou->id}}" src="{{$backslash}}{{optional($lou)->floor_plan}}" class="img-fluid" alt="" style="width: 100%;height: auto">
                                    </div>
                                @endforeach


                            </div>

                        <!-- </div>
                    </div> -->
                    @endif
                </div>
            </div>

            @if($companyData && $caseData)
                <div class="item-menu-tab-wrapper">
                    <ul class="nav nav-tabs item-menu-tab" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all"
                               aria-selected="true">{{__('src.全區')}}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="building-tab" data-toggle="tab" href="#building" role="tab"
                               aria-controls="building" aria-selected="false">{{__('src.棟')}}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="floor-tab" data-toggle="tab" href="#floor" role="tab" aria-controls="floor"
                               aria-selected="false">{{__('src.樓')}}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="household-tab" data-toggle="tab" href="#household" role="tab"
                               aria-controls="household" aria-selected="false">{{__('src.戶')}}</a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content" id="myTabContent">
                    <!-- nav nav-tab 全區 -->
                    <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                        <div class="card card-default">
                            <div class="card-body">
                                <div class="text-right">
                                    <span>{{__('src.建立人')}}：{{optional($caseData->createEmployee)->name}}</span>
                                    <button type="button" id="reload_page_button" class="btn btn-gk-print ml-3">{{__('src.面積重整')}}</button>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>{{__('src.本項目共有建築物(棟)')}}</label>
                                            <div class="form-control">{{ $num_chuang }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>{{__('src.總建築面積')}}m<sup>2<sup></label>
                                            <div class="form-control">{{--number_format($infoData->total_area, 2)--}}{{ $all_tot_m2 }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row bg-gk-yellow pt-2">
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>{{__('src.住戶面積')}}M<sup>2</sup></label>
                                            <div class="form-control">{{ $tot_a_m2 }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>{{__('src.住宅戶數')}}</label>
                                            <div class="form-control">{{ $tot_a_num }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row bg-gk-yellow">
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>{{__('src.商業面積')}}M<sup>2</sup></label>
                                            <div class="form-control">{{ $tot_b_m2 }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>{{__('src.商業戶數')}}</label>
                                            <div class="form-control">{{ $tot_b_num }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>{{__('src.物業用房')}}M<sup>2</sup></label>
                                            <div class="form-control">{{ $tot_h_m2 }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>&nbsp;</label>

                                        </div>
                                    </div>
                                </div>
                                <div class="row bg-gk-yellow">
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>{{__('src.單間車庫面積')}}M<sup>2</sup></label>
                                            <div class="form-control">{{ $tot_g_m2 }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>{{__('src.單間車庫數量')}}</label>
                                            <div class="form-control">{{ $tot_g_num }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>{{__('src.人防面積')}}M<sup>2</sup></label>
                                            <div class="form-control">{{ $tot_e_m2}}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>{{__('src.人防戶數')}}</label>
                                            <div class="form-control">{{ $tot_e_num}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row bg-gk-yellow mb-3">
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>{{__('src.儲藏室面積')}}M<sup>2</sup></label>
                                            <div class="form-control">{{ $tot_d_m2 }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>{{__('src.儲藏室間數')}}</label>
                                            <div class="form-control">{{ $tot_d_num }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>{{__('src.其他面積M2')}}</label>
                                            <div class="form-control">{{ $tot_i_m2 }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- nav nav-tab 棟 -->
                    <div class="tab-pane fade" id="building" role="tabpanel" aria-labelledby="building-tab">
                        <div class="card card-default">
                            <div class="card-body">
                                <div class="mb-3 d-flex justify-content-end align-items-center">
                                    <span>{{__('src.建立人')}}：{{optional($caseData->createEmployee)->name}}</span>
                                    @if($isManager)
                                    <button type="button" class="btn btn-gk-print ml-3" data-toggle="modal" data-target="#SchangeDatasS" data-sh1="{{optional($caseData->createEmployee)->name}}" data-sh2="{{$caseData->created_employee}}" data-ssid="1" data-did="{{$caseData->id}}" data-title="{{__('src.更改建立人')}}">{{__('src.更改建立人')}}</button>
                                    @endif

                                    <span class="text-gk-dark ml-4">{{__('src.本棟工地負責人')}} : {{(optional($caseData->createEmployeeMain)->name)}}</span>
                                    <button type="button" class="btn btn-gk-print ml-3" data-toggle="modal" data-target="#SchangeDatasS" data-sh1="{{optional($caseData->createEmployeeMain)->name}}" data-sh2="{{$caseData->editor_main}}" data-ssid="2" data-did="{{$caseData->id}}" data-title="{{__('src.更改負責人')}}" >{{__('src.更改負責人')}}</button>
                                </div>

                                <div class="row bg-gk-pink mb-3">
                                    <div class="table-responsive px-3" style="height: 300px;overflow-y: auto">
                                        <table class="table gk-default-table nowrap default-v-m dataTable no-footer table-hover"
                                               style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th scope="col" class="text-left">{{__('src.棟號')}}</th>
                                                <th scope="col" class="text-left">{{__('src.總面積')}}</th>
                                                <th scope="col" class="text-left">{{__('src.住宅(戶)')}}</th>
                                                <th scope="col" class="text-left">{{__('src.商業(戶)')}}</th>

                                                <th scope="col" class="text-left"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($chuangs_arr as $hid => $chuang)
                                                <tr id="trch_{{$hid}}" data-hid="{{$hid}}" data-did="{{$hid}}">
                                                    <td scope="row" class="text-left">{{ $chuang['name'] }}</td>
                                                    <td class="text-left">{{ $chuang['chuang_all_tot_area'] }}</td>
                                                    <td class="text-left">{{ $chuang['chuang_a_num'] }}</td>
                                                    <td class="text-left">{{ $chuang['chuang_b_num'] }}</td>
                                                    <td class="text-left">
                                                        <!-- sky -->
                                                        <button type="button" class="btn btn-gk-print ml-3" data-toggle="modal" data-target="#s_creationModal" data-title="{{__('src.檢視')}}" data-id="s_t7" data-h1="{{$chuang['name']}}" data-h2="{{$chuang['chuang_all_tot_area']}}" data-h3="{{$chuang['chuang_a_area']}}" data-h4="{{$chuang['chuang_a_num']}}" data-h5="{{$chuang['chuang_a_area']}}" data-h6="{{$chuang['chuang_c_area']}}" data-h7="{{$chuang['chuang_c_num']}}" data-h8="{{$chuang['chuang_h_area']}}" data-h9="{{$chuang['chuang_e_area']}}" data-h10="{{$chuang['chuang_e_num']}}" {{$chuang['chuang_g_area']}}" data-h11="{{$chuang['chuang_g_num']}}" data-h12="{{$chuang['chuang_d_area']}}" data-h13="{{$chuang['chuang_d_num']}}"
                                                        data-h14="{{$chuang['chuang_i_area']}}" data-h15="{{$chuang['on_ground']}}" data-h16="{{$chuang['under_ground']}}" >{{__('src.檢視')}}</button>
                                                        <button type="button" class="btn btn-gk-print ml-3" data-toggle="modal" data-target="#s_creationModal" data-title="{{__('src.更改期區')}}" data-id="s_t6" data-h1="{{$chuang['period']}}" data-h2="{{$chuang['area']}}" >{{__('src.更改期區')}}</button>
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#s_creationModal" data-title="{{__('src.棟編輯')}}" data-id="s_t1" data-h1="{{$chuang['name']}}" data-h2="{{$chuang['name']}}"  >{{__('src.更改幢名')}}</button>
                                                        <button type="button" class="btn btn-gk-del ml-3 chuang_del_btn" data-chid="{{$hid}}">{{__('src.刪除本棟')}}</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @if(false)
                                {{--@if($chuangData)--}}
                                    <div class="mb-3">
                                        {{$chuangData->name.__('src.總面積')}}：{{ $chuang_all_tot_m2 }} M2
                                    </div>

                                    <div class="row bg-gk-yellow pt-2">
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.住戶面積M2')}}</label>
                                                <div class="form-control">{{ $chuang_tot_a_m2 }}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.戶數')}}</label>
                                                <div class="form-control">{{ $chuang_tot_a_num }}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.商業面積M2')}}</label>
                                                <div class="form-control">{{ $chuang_tot_b_m2 }}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.戶數')}}</label>
                                                <div class="form-control">{{ $chuang_tot_b_num }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row bg-gk-yellow">
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.物業用房M2')}}</label>
                                                <div class="form-control">{{ $chuang_tot_h_m2 }}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.人防面積M2')}}</label>
                                                <div class="form-control">{{ $chuang_tot_e_m2 }}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.位')}}</label>
                                                <div class="form-control">{{ $chuang_tot_e_num }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row bg-gk-yellow">
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.車庫面積M2')}}</label>
                                                <div class="form-control">?</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.位')}}</label>
                                                <div class="form-control">?</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.公共車位M2')}}</label>
                                                <div class="form-control">?</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.位')}}</label>
                                                <div class="form-control">?</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row bg-gk-yellow">
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.單間車庫M2')}}</label>
                                                <div class="form-control">{{ $chuang_tot_g_m2 }}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.間')}}</label>
                                                <div class="form-control">{{ $chuang_tot_g_num}}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.儲藏室M2')}}</label>
                                                <div class="form-control">{{ $chuang_tot_d_m2 }}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.間')}}</label>
                                                <div class="form-control">{{ $chuang_tot_d_num }}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.其他面積M2')}}</label>
                                                <div class="form-control">{{ $chuang_tot_i_m2 }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row bg-gk-yellow">
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <div class="form-control border-0 bg-transparent">
                                                    <span class="align-middle">{{--__('src.本棟樓層：地上層，地下層', ['on' =>$chuangData->on_ground, 'under' =>$chuangData->under_ground])--}}
                                                    {{__('src.本棟樓層(地上層/地下層)')}} : {{ optional($chuangData_show)->on_ground }} / {{ optional($chuangData_show)->under_ground }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.開發分期')}}</label>
                                                <div class="form-control">{{$chuangData->getPeriodText()}}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.分區')}}</label>
                                                <div class="form-control">{{$chuangData->area}}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="floor" role="tabpanel" aria-labelledby="floor-tab">
                        <div class="card card-default">
                            <div class="card-body">
                                <div class="row text-right d-flex justify-content-end align-items-center">
                                    <div class="col-12 col-md-4">
                                        <select class="form-control slctp" name="slc_chuang" id="slc_chuang1" data-slctp="slc_chuang1">
                                            <option value="" >{{__('src.請選擇')}}</option>
                                            @foreach($chuangList as $chu)
                                                <option value="{{$chu->id}}" <?php if($chu->id==$chuangid){echo 'selected'; } ?> >{{$chu->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <span>{{__('src.建立人')}}：{{optional($caseData->createEmployee)->name}}</span>
                                    </div>
                                    <!-- <input type="button" class="btn btn-gk-back ml-3" value="{{__('src.建立本層平面圖')}}"> -->
                                </div>

                                <div class="mb-3">
                                    @if($chuangData)
                                        {{$chuangData->name . __('src.的樓層資料')}}
                                    @endif
                                </div>

                                <div class="row bg-gk-pink mb-3">
                                    <div class="table-responsive px-3" style="height: 300px;overflow-y: auto">
                                        <table class="table gk-default-table nowrap default-v-m dataTable no-footer table-hover"
                                               style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th scope="col" class="text-left">{{__('src.樓層')}}</th>
                                                <th scope="col" class="text-left">{{__('src.面積')}}</th>
                                                <th scope="col" class="text-left">{{__('src.戶，位')}}</th>
                                                <th scope="col" class="text-left"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {{-- @foreach($louList as $hid => $lou) --}}
                                            @foreach($louData_all as $hid => $lou)
                                                <tr data-hid="{{$hid}}" data-did="{{$lou->id}}">
                                                    <td scope="row" class="text-left">{{$lou->name}}</td>
                                                    <td class="text-left">{{number_format($lou->total_area, 2)}}</td>
                                                    <td class="text-left">{{$lou->site}}</td>
                                                    <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#s_creationModal" data-title="{{__('src.樓編輯')}}" data-id="s_t2"
                                                    data-h1="{{ $lou->name }}" data-h2="{{ $lou->total_area }}" data-h3="{{ $lou->a_area }}" data-h4="{{ $lou->a_count }}" data-h5="{{ $lou->c_area }}" data-h6="{{ $lou->c_count }}" data-h7="{{ $lou->h_area }}" data-h8="{{ $lou->e_area }}" data-h9="{{ $lou->e_count }}" data-h14="{{ $lou->g_area }}" data-h15="{{ $lou->g_count }}" data-h16="{{ $lou->d_area }}" data-h17="{{ $lou->d_count }}" data-h18="{{ $lou->i_area }}" >{{__('src.檢視')}}</button></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @if(false)
                                {{-- @if($louData) --}}
                                    <!-- <div class="mb-3">
                                        {{$louData->name.__('src.總面積')}}：{{number_format($louData->total_area, 2)}} M2
                                    </div>
                                    <div class="row bg-gk-yellow pt-2">
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.住戶面積M2')}}</label>
                                                <div class="form-control">{{number_format(($louData->a_area + $louData->b_area), 2)}}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.戶數')}}</label>
                                                <div class="form-control">{{$louData->a_count + $louData->b_count}}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.商業面積M2')}}</label>
                                                <div class="form-control">{{number_format($louData->c_area, 2)}}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.戶數')}}</label>
                                                <div class="form-control">{{$louData->c_count}}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row bg-gk-yellow">

                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.物業用房M2')}}</label>
                                                <div class="form-control">{{number_format($louData->h_area, 2)}}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.人防面積M2')}}</label>
                                                <div class="form-control">{{number_format($louData->e_area, 2)}}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.位')}}</label>
                                                <div class="form-control">{{$louData->e_count}}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row bg-gk-yellow">
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.車庫面積M2')}}</label>
                                                <div class="form-control">{{number_format($louData->f_area, 2)}}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.位')}}</label>
                                                <div class="form-control">{{$louData->f_count}}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.公共車位M2')}}</label>
                                                <div class="form-control">0</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.位')}}</label>
                                                <div class="form-control">0</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row bg-gk-yellow">
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.單間車庫M2')}}</label>
                                                <div class="form-control">{{number_format($louData->g_area, 2)}}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.間')}}</label>
                                                <div class="form-control">{{$louData->g_count}}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.儲藏室M2')}}</label>
                                                <div class="form-control">{{number_format($louData->d_area, 2)}}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.間')}}</label>
                                                <div class="form-control">{{$louData->d_count}}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.其他面積M2')}}</label>
                                                <div class="form-control">{{number_format($louData->i_area, 2)}}</div>
                                            </div>
                                        </div>
                                    </div> -->
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- nav nav-tab 戶 -->
                    <div class="tab-pane fade" id="household" role="tabpanel" aria-labelledby="household-tab">
                        <div class="card card-default">
                            <div class="card-body">
                                <div class="row text-right d-flex justify-content-end align-items-center">
                                    <div class="col-12 col-md-4">
                                        <select class="form-control slctp slc_2" name="slc_chuang" id="slc_chuang2">
                                            <option value="" >{{__('src.請選擇')}}</option>
                                            @foreach($chuangList as $chu)
                                                <option value="{{$chu->id}}" <?php if($chu->id==$chuangid){echo 'selected'; } ?> >{{$chu->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <select class="form-control slctp slc_2" name="slc_lou" id="slc_lou2">
                                            <option value="" >{{__('src.請選擇')}}</option>
                                            @foreach($louData_all as $lou)
                                                <option value="{{$lou->id}}" <?php if($louid==$lou->id){ echo "selected"; }?> > {{$lou->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <span>{{__('src.建立人')}}：{{optional($caseData->createEmployee)->name}}</span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    @if($caseData && $louData)
                                        {{$caseData->name.$louData->name.__('src.房屋資料')}}
                                    @endif
                                </div>

                                <div class="row bg-gk-pink mb-3">
                                    <div class="table-responsive px-3" style="height: 300px;overflow-y: auto">
                                        <table class="table gk-default-table nowrap default-v-m dataTable no-footer table-hover" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-left" style="width:15%">{{__('src.戶')}}</th>
                                                    <th scope="col" class="text-left" style="width:15%">{{__('src.用途')}}</th>
                                                    <th scope="col" class="text-left" style="width:10%">{{__('src.面積')}}</th>
                                                    <th scope="col" class="text-left" style="width:10%">{{__('src.電號')}}</th>
                                                    <th scope="col" class="text-left" style="width:10%">{{__('src.水號')}}</th>
                                                    <th scope="col" class="text-left" style="width:10%">{{__('src.煤氣')}}</th>
                                                    <th scope="col" class="text-left" style="width:10%">{{__('src.電視')}}</th>
                                                    <th scope="col" class="text-left" style="width:15%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            {{--@foreach($huList as $hu)--}}
                                            @foreach($huData_all as $hid =>  $huData)
                                                <tr data-hid="{{$hid}}" data-did="{{$huData->id}}">
                                                    <td scope="row" class="text-left">{{$huData->name}}</td>
                                                    <td class="text-left">{{$huData->use}}</td>
                                                    <td class="text-left">{{--number_format($hu->area, 2)--}}
                                                    {{$huData->area}}</td>
                                                    <td class="text-left">{{$huData->electric_number}}</td>
                                                    <td class="text-left">{{$huData->water_number}}</td>
                                                    <td class="text-left">{{$huData->gas}}</td>
                                                    <td class="text-left">{{$huData->television}}</td>
                                                    <td class="text-left"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#s_creationModal" data-title="{{__('src.戶編輯')}}" data-id="s_t3" data-h1="{{ $huData->name }}" data-h2="{{ $huData->use }}" data-h3="{{ $huData->area }}" data-h4="{{ $huData->electric_number }}" data-h5="{{ $huData->	water_number }}" data-h6="{{ $huData->gas }}" data-h7="{{ $huData->television }}" >{{__('src.編輯')}}</button></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{--@if($huData)--}}
                                @if(false)
                                    <!-- <div class="mb-3">
                                        {{__('src.區棟樓室', ['area' => 3, 'dong' => 25, 'build' => 4, 'room' => 001])}}
                                    </div>
                                    <div class="row bg-gk-yellow pt-2">
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.用途')}}</label>
                                                <div class="form-control">{{$huData->getUseText()}}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.面積M2')}}</label>
                                                <div class="form-control">{{number_format($huData->area, 2)}}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.電號')}}</label>
                                                <input type="text" class="form-control" placeholder="" name="electronic">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.水號')}}</label>
                                                <input type="text" class="form-control" placeholder="" name="water">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row bg-gk-yellow">
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.煤氣')}}</label>
                                                <input type="text" class="form-control" placeholder="" name="gas">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{__('src.電視')}}</label>
                                                <input type="text" class="form-control" placeholder="" name="tv">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="button" class="form-control btn btn-gk-print ml-3" >{{__('src.儲存')}}</button>
                                            </div>
                                        </div>
                                    </div> -->
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('inlineScripts')
    <!-- sky -->
    <!-- Modal -->
    <div class="modal fade" id="s_creationModal" tabindex="-1" aria-labelledby="s_creationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="s_creationModalLabel">message</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <!-- modal-body Content-->
            <!--
                data-tid=s_t1 表示這是 棟的欄位資料
                ex: 户 就是 data-tid=s_t4
            -->
            <!-- 棟的資料1 -->
            <div data-tid="s_t1">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                    <label>{{__('src.原棟號')}}</label>
                    <input type="text" class="form-control" data-name="chuangs_name_ori" data-guise="input" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                    <label>{{__('src.新棟號')}}</label>
                    <input type="text" class="form-control" data-name="chuangs_name_new" data-guise="input">
                    </div>
                </div>
            </div>
            <!-- 棟的資料2 -->
            <div class="d-none" data-tid="s_t7">
                <div class="form-group">
                  <label>{{__('src.幢名')}}</label>
                  <input type="text" class="form-control" data-name="chuang_name" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.總面積')}}</label>
                  <input type="text" class="form-control" data-name="chuang_tot_area" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.住戶面積')}}</label>
                  <input type="text" class="form-control" data-name="chuang_a_area" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.住戶戶數')}}</label>
                  <input type="text" class="form-control" data-name="chuang_a_count" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.商業面積')}}</label>
                  <input type="text" class="form-control" data-name="chuang_c_area" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.商業戶數')}}</label>
                  <input type="text" class="form-control" data-name="chuang_c_count" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.物業用房')}}</label>
                  <input type="text" class="form-control" data-name="chuang_h_area" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.人防面積')}}</label>
                  <input type="text" class="form-control" data-name="chuang_e_area" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.間')}}</label>
                  <input type="text" class="form-control" data-name="chuang_e_count" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.單間車庫面積')}}</label>
                  <input type="text" class="form-control" data-name="chuang_g_area2" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.單間車庫數量')}}</label>
                  <input type="text" class="form-control" data-name="chuang_g_count2" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.儲藏室面積')}}</label>
                  <input type="text" class="form-control" data-name="chuang_d_area" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.儲藏室間數')}}</label>
                  <input type="text" class="form-control" data-name="chuang_d_count" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.其他面積')}}</label>
                  <input type="text" class="form-control" data-name="chuang_i_area" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.地上層數')}}</label>
                  <input type="text" class="form-control" data-name="chuang_on_ground" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.地下層數')}}</label>
                  <input type="text" class="form-control" data-name="chuang_under_ground" data-guise="input" readonly>
                </div>
            </div>

            <!-- 樓的資料 -->
            <div class="d-none" data-tid="s_t2">
                <div class="form-group">
                  <label>{{__('src.樓層')}}</label>
                  <input type="text" class="form-control" data-name="lou_name" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.總面積')}}</label>
                  <input type="text" class="form-control" data-name="lou_tot_area" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.住戶面積')}}</label>
                  <input type="text" class="form-control" data-name="lou_a_area" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.住戶戶數')}}</label>
                  <input type="text" class="form-control" data-name="lou_a_count" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.商業面積')}}</label>
                  <input type="text" class="form-control" data-name="lou_c_area" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.商業戶數')}}</label>
                  <input type="text" class="form-control" data-name="lou_c_count" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.物業用房')}}</label>
                  <input type="text" class="form-control" data-name="lou_h_area" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.人防面積')}}</label>
                  <input type="text" class="form-control" data-name="lou_e_area" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>位</label>
                  <input type="text" class="form-control" data-name="lou_e_count" data-guise="input" readonly>
                </div>
                <!-- <div class="form-group">
                  <label>{{__('src.車庫面積')}}</label>
                  <input type="text" class="form-control" data-name="lou_g_area" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.車庫間數')}}</label>
                  <input type="text" class="form-control" data-name="lou_g_count" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.公共車位')}}</label>
                  <input type="text" class="form-control" data-name="lou_0" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.位')}}</label>
                  <input type="text" class="form-control" data-name="lou_1" data-guise="input" readonly>
                </div> -->
                <div class="form-group">
                  <label>{{__('src.單間車庫面積')}}</label>
                  <input type="text" class="form-control" data-name="lou_g_area2" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.單間車庫數量')}}</label>
                  <input type="text" class="form-control" data-name="lou_g_count2" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.儲藏室面積')}}</label>
                  <input type="text" class="form-control" data-name="lou_d_area" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.儲藏室間數')}}</label>
                  <input type="text" class="form-control" data-name="lou_d_count" data-guise="input" readonly>
                </div>
                <div class="form-group">
                  <label>{{__('src.其他面積')}}</label>
                  <input type="text" class="form-control" data-name="lou_i_area" data-guise="input" readonly>
                </div>
            </div>
            <!-- 戶的資料 -->
            <div class="d-none" data-tid="s_t3">
                <div class="form-group">
                  <label>{{__('src.戶號')}}</label>
                  <input type="text" class="form-control" data-name="hu_name" data-guise="input">
                </div>
                <div class="form-group">
                  <label>{{__('src.用途')}}</label>
                  <!-- <input type="text" class="form-control" data-name="hu_use"> -->
                  <select class="form-control" data-name="hu_use" data-guise="input">
                    <option value="null">{{__('src.請選擇')}}</option>
                    @foreach($useList as $val => $usename)
                        <option value="{{$val}}">{{$usename}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label>{{__('src.面積')}}</label>
                  <input type="number" class="form-control" data-name="hu_area" data-guise="input">
                </div>
                <div class="form-group">
                  <label>{{__('src.電號')}}</label>
                  <input type="text" class="form-control" data-name="hu_ele" data-guise="input">
                </div>
                <div class="form-group">
                  <label>{{__('src.水號')}}</label>
                  <input type="text" class="form-control" data-name="hu_water" data-guise="input">
                </div>
                <div class="form-group">
                  <label>{{__('src.煤氣')}}</label>
                  <input type="text" class="form-control" data-name="hu_gas" data-guise="input">
                </div>
                <div class="form-group">
                  <label>{{__('src.電視')}}</label>
                  <input type="text" class="form-control" data-name="hu_tv" data-guise="input">
                </div>
            </div>
            <!-- 更改期區-->
            <div class="d-none" data-tid="s_t6">
                <div class="form-group">
                    <label>{{__('src.開發分期改為')}}</label>
                    <select class="form-control" data-name="develop_period" data-guise="input">
                        <option value="null" >{{__('src.請選擇')}}</option>
                        <option value="1" >{{__('src.第一期')}}</option>
                        <option value="2" >{{__('src.第二期')}}</option>
                        <option value="3" >{{__('src.第三期')}}</option>
                    </select>
                </div>
                <div class="form-group">
                  <label>{{__('src.開發分區改為')}}</label>
                  <input type="number" class="form-control" data-name="develop_area" data-guise="input">
                </div>
            </div>
            <!-- modal-body Content end -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('src.關閉')}}</button>
              <button type="button" class="btn btn-primary" id="s_submit">{{__('src.儲存')}}</button>
            </div>
          </div>
        </div>
    </div>
    <!-- 簡易modal -->
    <div class="modal fade" id="SchangeDatasS" tabindex="-1" aria-labelledby="SchangeDatasSLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="SchangeDatasSLabel">message</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <!-- 更改建立人 -->
                <div data-title="{{__('src.更改建立人')}}" data-tid="s_ssid1">
                    <div class="form-group">
                    <label>{{__('src.原建立人')}}</label>
                    <input type="text" class="form-control" data-name="creator_old" data-guise="input" readonly>
                    </div>
                    <div class="form-group">
                        <label>{{__('src.新建立人')}}</label>
                        <select class="form-control" data-name="creator_new" data-guise="input">
                            <option value="null">請選擇</option>
                            @foreach($this_com_employees as $employee)
                            <option value="{{$employee->id}}">{{$employee->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- 更改負責人 -->
                <div class="d-none" data-title="{{__('src.更改負責人')}}" data-tid="s_ssid2">
                    <div class="form-group">
                        <label>{{__('src.原負責人')}}</label>
                        <input type="text" class="form-control" data-name="in_charge_old" data-guise="input">
                    </div>
                    <div class="form-group">
                        <label>{{__('src.新負責人')}}</label>
                        <select class="form-control" data-name="in_charge_new" data-guise="input">
                            @if(optional($caseData)->editor_1)
                                <option value="{{$caseData->editor_1}}">{{optional($caseData->editorEmployee1)->name}}</option>
                            @endif
                            @if(optional($caseData)->editor_2)
                                <option value="{{$caseData->editor_2}}">{{optional($caseData->editorEmployee2)->name}}</option>
                            @endif
                            @if(optional($caseData)->editor_3)
                                <option value="{{$caseData->editor_3}}">{{optional($caseData->editorEmployee3)->name}}</option>
                            @endif
                            @if(optional($caseData)->editor_4)
                                <option value="{{$caseData->editor_4}}">{{optional($caseData->editorEmployee4)->name}}</option>
                            @endif
                            @if(optional($caseData)->editor_5)
                                <option value="{{$caseData->editor_5}}">{{optional($caseData->editorEmployee5)->name}}</option>
                            @endif
                        </select>
                    </div>
                </div>
                <!-- 簡易版modal -->
                <!-- <div class="d-none" data-title="戶的資料" data-tid="s_ssid3">
                <div class="form-group">
                    <label>CCC</label>
                    <input type="text" class="form-control" data-name="floor_number" data-guise="input">
                </div>
                <div class="form-group">
                    <label>面积</label>
                    <select class="form-control" data-name="acreage_number" data-guise="input">
                    <option value="null">請選擇</option>
                    <option value="33">33</option>
                    <option value="10.00">10.00</option>
                    <option value="9">9</option>
                    <option value="6">6</option>
                    <option value="12.00">12.00</option>
                </select>
                </div>
                </div> -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('src.關閉')}}</button>
              <button type="button" class="btn btn-primary" id="s_Saves_submit">{{__('src.儲存')}}</button>
            </div>
          </div>
        </div>
      </div>

    <!-- 添加房屋 -->
    <div class="modal fade" id="addBuildingModal" tabindex="-1" role="dialog" aria-labelledby="addBuildingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-windows modal-xl" role="document">
            <div class="modal-content">
                <div class="title-bar">
                    <div class="title-bar-text"></div>
                    <div class="title-bar-controls">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="mdi mdi-close"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title" id="addBuildingModalLabel">{{__('src.添加房屋')}}</h5>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        {{Form::open(['url' => route('estate.addInfo',[$companyData->id ?? null, $caseData->id ?? null]), 'id' => 'infoForm'])}}
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>{{__('src.選擇作業')}}</label>
                                    <select class="form-control default-SumoSelect {{$errors->has('addType') ? 'is-invalid' : ''}}" id="addType" name="type">
                                        <option value="info" {{old('type') === 'info' ? 'SELECTED' : ''}}>{{__('src.新增房屋資料')}}</option>
                                        <option value="standard" {{old('type') === 'standard' ? 'SELECTED' : ''}}>{{__('src.建立標準套型')}}</option>
                                    </select>
                                    @if($errors->has('addType'))
                                        <div class="d-block is-invalid invalid-feedback">{{$errors->first('addType')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group add-type-check for-info">
                                    <label>{{__('src.共已編製(棟建築)')}}</label>
                                    <input type="text" class="form-control bg-white" value="{{$infoData ? $infoData->dong_count : 0}}" readonly>
                                </div>
                                <div class="form-group add-type-check for-standard">
                                    <label>{{__('src.套型名稱')}}</label>
                                    <input type="text" class="form-control {{$errors->has('name') ? 'is-invalid' : ''}}" id="name" name="name" value="{{old('name')}}" autocomplete="off">
                                    @if($errors->has('name'))
                                        <div class="d-block is-invalid invalid-feedback">{{$errors->first('name')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row add-type-check for-info">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label>{{__('src.套型名稱')}}</label>
                                    <select class="form-control default-SumoSelect {{$errors->has('standard') ? 'is-invalid' : ''}}" name="standard" id="standard">
                                        <option value="">{{__('src.選擇')}}</option>
                                        @foreach($standardList as $standard)
                                            <option value="{{$standard->id}}" {{old('standard') == $standard->id ? 'SELECTED' : ''}}>{{$standard->name}}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('standard'))
                                        <div class="d-block is-invalid invalid-feedback">{{$errors->first('standard')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="form-group">
                                    <label>{{__('src.分區')}}</label>
                                    <input type="text" class="form-control {{$errors->has('area') ? 'is-invalid' : ''}}" name="area" id="area" autocomplete="off" value="{{old('area')}}">
                                    @if($errors->has('area'))
                                        <div class="d-block is-invalid invalid-feedback">{{$errors->first('area')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="form-group">
                                    <label>{{__('src.分期')}}</label>
                                    <select class="form-control default-SumoSelect {{$errors->has('period') ? 'is-invalid' : ''}}" name="period" id="period">
                                        @foreach($periodList as $periodKey => $period)
                                            <option value="{{$periodKey}}" {{old('period') === $periodKey ? 'SELECTED' : ''}}>{{__('src.'.$period)}}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('period'))
                                        <div class="d-block is-invalid invalid-feedback">{{$errors->first('period')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex col-12 col-md-4">
                                <div class="form-group">
                                    <label>{{__('src.自第(幢)')}}</label>
                                    <input type="text" class="form-control {{$errors->has('start_dong') ? 'is-invalid' : ''}}" name="start_dong" id="start_dong" autocomplete="off" value="{{old('start_dong')}}">
                                    @if($errors->has('start_dong'))
                                        <div class="d-block is-invalid invalid-feedback">{{$errors->first('start_dong')}}</div>
                                    @endif
                                </div>
                                <div class="mx-2">～</div>
                                <div class="form-group">
                                    <label>{{__('src.第(幢)')}}</label>
                                    <input type="text" class="form-control disabled {{$errors->has('end_dong') ? 'is-invalid' : ''}}" name="end_dong" id="end_dong" autocomplete="off" value="{{old('end_dong')}}">
                                    @if($errors->has('end_dong'))
                                        <div class="d-block is-invalid invalid-feedback">{{$errors->first('end_dong')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row" id="addHouseWrapper">
                            <div class="d-flex flex-wrap col-12 align-items-center">
                                <div>{{__('src.地上')}}：</div>
                                <div class="cell-w-10 flex-grow-1">
                                    <div class="form-group">
                                        <label>{{__('src.自第(層)')}}</label>
                                        <input type="text" class="form-control {{$errors->has('start_on_ground') ? 'is-invalid' : ''}}" name="start_on_ground" id="start_on_ground" autocomplete="off" value="{{old('start_on_ground')}}">
                                        @if($errors->has('start_on_ground'))
                                            <div class="d-block is-invalid invalid-feedback">{{$errors->first('start_on_ground')}}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mx-2">～</div>
                                <div class="cell-w-10 mr-2 flex-grow-1">
                                    <div class="form-group">
                                        <label>{{__('src.第(層)')}}</label>
                                        <input type="text" class="form-control {{$errors->has('end_on_ground') ? 'is-invalid' : ''}}" name="end_on_ground" id="end_on_ground" autocomplete="off" value="{{old('end_on_ground')}}">
                                        @if($errors->has('end_on_ground'))
                                            <div class="d-block is-invalid invalid-feedback">{{$errors->first('end_on_ground')}}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="cell-w-10 mr-2 flex-grow-1">
                                    <div class="form-group">
                                        <label>{{__('src.用途')}}</label>
                                        <select class="form-control default-SumoSelect {{$errors->has('on_ground_use') ? 'is-invalid' : ''}}" name="on_ground_use" id="on_ground_use">
                                            <option value=""></option>
                                            @foreach($useList as $useKey => $use)
                                                <option value="{{$useKey}}" {{$useKey === old('on_ground_use') ? 'SELECTED' : '' }}>{{$use}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('on_ground_use'))
                                            <div class="d-block is-invalid invalid-feedback">{{$errors->first('on_ground_use')}}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="cell-w-10 mr-2 flex-grow-1">
                                    <div class="form-group">
                                        <label>{{__('src.每層')}}</label>
                                        <div class="d-flex">
                                            <input type="text" class="form-control {{$errors->has('on_ground_site') ? 'is-invalid' : ''}}" name="on_ground_site" id="on_ground_site" autocomplete="off" value="{{old('on_ground_site')}}">
                                            <select class="form-control default-SumoSelect {{$errors->has('on_ground_site_unit') ? 'is-invalid' : ''}}" name="on_ground_site_unit" id="on_ground_site_unit">
                                                <option value=""></option>
                                                @foreach($unitList as $unitKey => $unit)
                                                    <option value="{{$unitKey}}" {{$unitKey === old('on_ground_site_unit') ? 'SELECTED' : ''}}>{{$unit}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if($errors->has('on_ground_site'))
                                            <div class="d-block is-invalid invalid-feedback">{{$errors->first('on_ground_site')}}</div>
                                        @endif
                                        @if($errors->has('on_ground_site_unit'))
                                            <div class="d-block is-invalid invalid-feedback">{{$errors->first('on_ground_site_unit')}}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="cell-w-10 mr-2 flex-grow-1">
                                    <div class="form-group">
                                        <label>{{__('src.每戶面積M2')}}</label>
                                        <input type="text" class="form-control {{$errors->has('on_ground_site_area') ? 'is-invalid' : ''}}" name="on_ground_site_area" id="on_ground_site_area" autocomplete="off" value="{{old('on_ground_site_area')}}">
                                        @if($errors->has('on_ground_site_area'))
                                            <div class="d-block is-invalid invalid-feedback">{{$errors->first('on_ground_site_area')}}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap col-12 align-items-center">
                                <div>{{__('src.地下')}}：</div>
                                <div class="cell-w-10 flex-grow-1">
                                    <div class="form-group">
                                        <label>{{__('src.自第(層)')}}</label>
                                        <input type="text" class="form-control {{$errors->has('start_under_ground') ? 'is-invalid' : ''}}" name="start_under_ground" id="start_under_ground" autocomplete="off" value="{{old('start_under_ground')}}">
                                        @if($errors->has('start_under_ground'))
                                            <div class="d-block is-invalid invalid-feedback">{{$errors->first('start_under_ground')}}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mx-2">～</div>
                                <div class="cell-w-10 mr-2 flex-grow-1">
                                    <div class="form-group">
                                        <label>{{__('src.第(層)')}}</label>
                                        <input type="text" class="form-control {{$errors->has('end_under_ground') ? 'is-invalid' : ''}}" name="end_under_ground" id="end_under_ground" autocomplete="off" value="{{old('end_under_ground')}}">
                                        @if($errors->has('end_under_ground'))
                                            <div class="d-block is-invalid invalid-feedback">{{$errors->first('end_under_ground')}}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="cell-w-10 mr-2 flex-grow-1">
                                    <div class="form-group">
                                        <label>{{__('src.用途')}}</label>
                                        <select class="form-control default-SumoSelect {{$errors->has('under_ground_use') ? 'is-invalid' : ''}}" name="under_ground_use" id="under_ground_use">
                                            <option value=""></option>
                                            @foreach($useList as $useKey => $use)
                                                <option value="{{$useKey}}" {{old('under_ground_use') === $useKey ? 'SELECTED' : ''}}>{{$use}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('under_ground_use'))
                                            <div class="d-block is-invalid invalid-feedback">{{$errors->first('under_ground_use')}}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="cell-w-10 mr-2 flex-grow-1">
                                    <div class="form-group">
                                        <label>{{__('src.每層')}}</label>
                                        <div class="d-flex">
                                            <input type="text" class="form-control {{$errors->has('under_ground_site') ? 'is-invalid' : ''}}" name="under_ground_site" id="under_ground_site" autocomplete="off" value="{{old('under_ground_site')}}">
                                            <select class="form-control default-SumoSelect {{$errors->has('under_ground_site_unit') ? 'is-invalid' : ''}}" name="under_ground_site_unit" id="under_ground_site_unit">
                                                <option value=""></option>
                                                @foreach($unitList as $unitKey => $unit)
                                                    <option value="{{$unitKey}}" {{old('under_ground_site_unit') === $unitKey ? 'SELECTED' : ''}}>{{$unit}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if($errors->has('under_ground_site'))
                                            <div class="d-block is-invalid invalid-feedback">{{$errors->first('under_ground_site')}}</div>
                                        @endif
                                        @if($errors->has('under_ground_site_unit'))
                                            <div class="d-block is-invalid invalid-feedback">{{$errors->first('under_ground_site_unit')}}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="cell-w-10 mr-2 flex-grow-1">
                                    <div class="form-group">
                                        <label>{{__('src.每戶面積M2')}}</label>
                                        <input type="text" class="form-control {{$errors->has('under_ground_site_area') ? 'is-invalid' : ''}}" name="under_ground_site_area" id="under_ground_site_area" autocomplete="off" value="{{old('under_ground_site_area')}}">
                                        @if($errors->has('under_ground_site_area'))
                                            <div class="d-block is-invalid invalid-feedback">{{$errors->first('under_ground_site_area')}}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{Form::close()}}
                    </div>

                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-gk-save" id="infoAddBtn">{{__('src.確定')}}</button>
                        <button type="button" class="btn btn-gk-save" data-toggle="modal" data-target="#showExplain">{{__('src.說明')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 添加房屋-說明 -->
    <div class="modal fade" id="showExplain" tabindex="-1" role="dialog" aria-labelledby="addBuildingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-windows modal-xl" role="document">
            <div class="modal-content">
                <div class="title-bar">
                    <div class="title-bar-text"></div>
                    <div class="title-bar-controls">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="mdi mdi-close"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title" id="showExplain">{{__('src.說明')}}</h5>
                </div>
                <div class="modal-body">
                        1. 區名及床號如為數字，產生的動名會增加「區」及「幢」字，文字則不加。(區不填表示不區)產生棟名如：1區01幢、梅苑06幢、12幢、虹梅院攬翠樓(區填虹梅院，幢填攬翠樓)...。<br>
                        2. 每層戶(位)選項將用於該戶或車位編號最後用字如：67幢6樓007室，人防地下1層153號。<br>
                        3. 可以不分區不分進度期別，或先建立房屋資料，以後再按實際情況調整分區進度。<br>
                        4. 同一棟的同一層如有不同的用途的戶號時，可分次生成如：<br>
                        第1次：地上「3」層~「3」層，每層「6」戶住宅。按「產生」後，修改為「12」戶商業再按「產生」時，將生成該棟有6戶住宅及12戶商業共18戶。<br>
                        5. 1樓為裙樓或商場或地下人防等類型，請單獨建立一棟單層的，上面各幢另外建立。<br>
                        如有套用套型時，請先將各標準套型建立完成後再選用套型來建立房屋資料。
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            $('#addType').change(function () {
                var $this = $(this);
                $('.add-type-check.for-' + this.value).toggle(true);
                $('.add-type-check:not(.for-' + this.value + ')').toggle(false);
            }).trigger('change');

            const companyEle = $("#company"),
                caseEle = $("#case"),
                typeEle = $("#type"),
                backgroundEle = $("#background"),
                url = '{{route('estate.index')}}',
                companyId = '{{$companyData->id ?? null}}',
                caseId = '{{$caseData->id ?? null}}';

            companyEle.change(function () {
                reloadPage();
            });

            caseEle.change(function () {
                reloadPage();
            });

            typeEle.change(function () {
                reloadPage();
            });

            backgroundEle.change(function () {
                formSubmit('changeBgForm');
            });

            function reloadPage() {
                var _url = url,
                    _companyId = companyEle.val(),
                    _caseId = caseEle.val(),
                    _type = typeEle.val();

                if (_companyId) {
                    _url += '/' + _companyId;
                    if (_caseId) {
                        _url += '/' + _caseId;
                        if (_type) {
                            _url += "/" + _type;
                        }
                    }
                }
                GoUrl(_url);
            }


            const addTypeEle = $("#addType"),
                infoFormValidate = $("#infoForm").validate({
                    rules: {
                        name: {
                            required: function () {
                                return addTypeEle.val() === 'standard';
                            }
                        },
                        area: {
                            required: function () {
                                return addTypeEle.val() === 'info';
                            }
                        },
                        start_dong: {
                            required: function () {
                                return addTypeEle.val() === 'info';
                            },
                            number: true,
                            max: function () {
                                const endDong = $("#end_dong").val();
                                return isNaN(endDong) || !endDong ? 999 : endDong;
                            }
                        },
                        end_dong: {
                            required: function () {
                                return addTypeEle.val() === 'info';
                            },
                            number: true,
                            min: function () {
                                const startDong = $("#start_dong").val();
                                return isNaN(startDong) || !startDong ? -999 : startDong;
                            }
                        },
                        start_on_ground: {
                            required: function () {
                                return isNaN($("#end_on_ground").val())
                            },
                            number: true,
                            max: function () {
                                const endDong = $("#end_on_ground").val();
                                return isNaN(endDong) || !endDong ? 999 : endDong;
                            }
                        },
                        end_on_ground: {
                            required: function () {
                                return isNaN($("#start_on_ground").val())
                            },
                            number: true,
                            min: function () {
                                const startDong = $("#start_on_ground").val();
                                return isNaN(startDong) || !startDong ? -999 : startDong;
                            }
                        },
                        on_ground_site: {
                            number: true
                        },
                        on_ground_site_area: {
                            number: true
                        },
                        start_under_ground: {
                            required: function () {
                                return isNaN($("#end_under_ground").val())
                            },
                            number: true,
                            max: function () {
                                const endDong = $("#end_under_ground").val();
                                return isNaN(endDong) || !endDong ? 999 : endDong;
                            }
                        },
                        end_under_ground: {
                            required: function () {
                                return isNaN($("#start_under_ground").val())
                            },
                            number: true,
                            min: function () {
                                const startDong = $("#start_under_ground").val();
                                return isNaN(startDong) || !startDong ? -999 : startDong;
                            }
                        },
                        under_ground_site: {
                            number: true
                        },
                        under_ground_site_area: {
                            number: true
                        }

                    },
                    messages: {
                        name: '{{columnTip('套型名稱')}}',
                        area: '{{columnTip('分區')}}',
                        period: '{{columnTip('分期')}}',
                        start_dong: {
                            required: '{{columnTip('自第(幢)')}}',
                            number: '{{__('src.請輸入正確的數值')}}',
                            max: '{{__('src.數值需小於')}}' + $("#end_dong").val()
                        },
                        end_dong: {
                            required: '{{columnTip('第(幢)')}}',
                            number: '{{__('src.請輸入正確的數值')}}',
                            min: '{{__('src.數值需大於')}}' + $("#start_dong").val()
                        },
                        start_on_ground: {
                            required: '{{columnTip('自第(層)')}}',
                            number: '{{__('src.請輸入正確的數值')}}',
                            max: '{{__('src.數值需小於')}}' + $("#end_on_ground").val()
                        },
                        end_on_ground: {
                            required: '{{columnTip('第(層)')}}',
                            number: '{{__('src.請輸入正確的數值')}}',
                            min: '{{__('src.數值需大於')}}' + $("#start_on_ground").val()
                        },
                        on_ground_site: {
                            number: '{{__('src.請輸入正確的數值')}}'
                        },
                        on_ground_site_area: {
                            number: '{{__('src.請輸入正確的數值')}}'
                        },
                        start_under_ground: {
                            required: '{{columnTip('自第(層)')}}',
                            number: '{{__('src.請輸入正確的數值')}}',
                            max: '{{__('src.數值需小於')}}' + $("#end_under_ground").val()
                        },
                        end_under_ground: {
                            required: '{{columnTip('第(層)')}}',
                            number: '{{__('src.請輸入正確的數值')}}',
                            min: '{{__('src.數值需大於')}}' + $("#start_under_ground").val()
                        },
                        under_ground_site: {
                            number: '{{__('src.請輸入正確的數值')}}'
                        },
                        under_ground_site_area: {
                            number: '{{__('src.請輸入正確的數值')}}'
                        }
                    }
                });

            $("#standard").change(function () {
                const val = $(this).val();
                if (isNaN(val) || !val) {
                    $("#addHouseWrapper").find('input,select').removeAttr('disabled');
                    $('#on_ground_use')[0].sumo.enable();
                    $('#on_ground_site_unit')[0].sumo.enable();
                    $('#under_ground_use')[0].sumo.enable();
                    $('#under_ground_site_unit')[0].sumo.enable();
                } else {
                    $("#addHouseWrapper").find('input,select').attr('disabled', true).val('');
                    $('#on_ground_use')[0].sumo.disable().unSelectAll();
                    ;
                    $('#on_ground_site_unit')[0].sumo.disable().unSelectAll();
                    ;
                    $('#under_ground_use')[0].sumo.disable().unSelectAll();
                    ;
                    $('#under_ground_site_unit')[0].sumo.disable().unSelectAll();
                    ;
                }
            });

            $("#infoAddBtn").click(function () {
                if (infoFormValidate.form()) formSubmit('infoForm');
            });
        })();
    </script>

    <script type="text/javascript">
        $("#reload_page_button").on('click', function (){
            location.reload();
        });
        $(".chuang_del_btn").on('click', function (){
            let chid = $(this).data("chid");
            // $("#trch_"+chid).remove();
            chuang_del(chid);
        });
        $(".hu_edit").on('click', function (){
            let hudata = $(this).data("huid");
        });

        let thiscom = $("#company").val();
        let thiscase = $("#case").val();
        let thistype = $("#type").val();
        $("#slc_chuang1").on('change', function (){
            let slc_type = $(this).val();
            window.location.replace("{{route('estate.index')}}"+"/"+thiscom+"/"+thiscase+"/"+thistype+"/"+slc_type);
        });

        $("#slc_chuang2").on('change', function (){
            let slc_chuang2_val = $(this).val();
            $("#slc_lou2").empty();
            @if($louid)
                $('#slc_lou2 option[value="{{$louid}}"]').attr('selected', 'selected');
            @endif
            reolad_lous(slc_chuang2_val);
        });

        $("#slc_lou2").on('change', function(){
            let chu_val = $("#slc_chuang2").val();
            let lou_val = $("#slc_lou2").val();
            if(chu_val=="" && lou_val!="" && lou_val!="null"){
                $('#slc_chuang2 option[value=""]').attr('selected', 'selected');
                $('#slc_lou2 option[value=""]').attr('selected', 'selected');
                alert('請選擇幢號');
            }else if(chu_val=="" && lou_val=="" && lou_val=="null"){
                alert('請選擇幢號及樓號');
            }else if(chu_val!="" && lou_val!="" && lou_val!="null")
            {
                window.location.replace("{{route('estate.index')}}"+"/"+thiscom+"/"+thiscase+"/"+thistype+"/"+chu_val+"/"+lou_val);
            }
        });

        // $("#explain_btn").on('click', function(){

        // });

    </script>

    <script type="text/javascript">
        // sky
        //Tab數量(可設定上限，全出、棟、樓、戶)
        let TabNumber = 30;
        let colNum = 30; // modal內的col欄位 ex:樓層、總面積、住戶面積、戶數
        //要送出與更新頁面的資料(丟後端)
        let AllarrayS = [];
        //共用Tab key設定
        // s_t1 表示頁面上有幾個Tab
        // 這邊主要在取Obj key 名稱，可以減少進去裡面尋找
        //== 自訂功能 ==// (s_Building可修改，作為辨識修改哪個檔案)
        let TabKeyAll = {"s_t1":"s_Building","s_t2":"s_Floor","s_t3":"s_Household", "s_t6":"edit_peri_area"};
        //== 自訂功能 ==//
        //ajax對應的Url
        let TabKeyUrlAll = {"s_t1":"/ajax_chuang_edit", "s_t2":"/test2", "s_t3":"/test3"};

        //Modal js
        $('#s_creationModal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);

            //== Modal標題資料 ==//
            let ModalTitle = button.data('title') ? button.data('title') : false;

            //== 取得 data-id ==//
            let Tabid = button.data('id') ? button.data('id') : false;

            //== 取得 data-did ==//
            let Tab_didTag = event.relatedTarget.parentElement != false ?  (event.relatedTarget.parentElement.parentElement != false ? event.relatedTarget.parentElement.parentElement : false ) : false;
            let Tab_did = Tab_didTag !=false ? (Tab_didTag.dataset.did !=false ? Tab_didTag.dataset.did : false ) : false;

            //== 取得目前第幾行 有順序問題不可以移動編輯按鈕==//
            let Tabhid = false;
            if(event.relatedTarget.parentElement.parentElement)
            {
                //取得按鈕上層位置
                let TabTr = event.relatedTarget.parentElement.parentElement;
                //取得 data-hid 的值
                Tabhid = TabTr.dataset.hid ? TabTr.dataset.hid : false;
            }

            //== 判斷Modal顯示的資料 ==//
            if(Tabid != false && document.querySelector('[data-tid="'+Tabid+'"]'))
            {
                //== 先將所有Modal資料隱藏 ==//
                for(let i=1; i<= TabNumber; i++)
                {
                     //== 判斷data-tid是否存在 ==//
                    if(document.querySelector('[data-tid="s_t'+i+'"]'))
                    {
                        document.querySelector('[data-tid="s_t'+i+'"]').classList.add('d-none');
                    }
                }
                //== 顯示正確資料 ==//
                document.querySelector('[data-tid="'+Tabid+'"]').classList.remove('d-none');
            }
            else
            {
                console.error("Modal資料顯示出錯");
            }

            //== 取得列表資料 ==//
            //data-h1資料有10個
            let array = {};
            let k = 0;
            //取得欄位名稱
            for(let i=0; i<= colNum; i++)
            {
                k= i+1;
                //檢查是否有data-h1 ~ data-h10
                if(event.relatedTarget.getAttribute('data-h'+k))
                {
                    //array["h"+k] = button.data('h'+k);
                    array["h"+k] = event.relatedTarget.getAttribute('data-h'+k);
                }
            }

            //== 取得資料的詳細名稱 ==//
            let TabClass = document.querySelector('[data-tid="'+Tabid+'"]');
            // let stInpus = TabClass.querySelectorAll('input');
            let stInpus = TabClass.querySelectorAll('[data-guise="input"]');
            let i = 1;
            stInpus.forEach(item=>{
                // item.value = array["h"+i];
                if( array["h"+i] != undefined )
                {
                    if(item.nodeName == "INPUT" && array["h"+i] == "null")
                    {
                        item.value = "";
                    }
                    else
                    {
                        item.value = array["h"+i];
                    }
                }
                else
                {
                    item.value = "";
                }
                i++;
            });

            //== 將資料寫上去Modal ==//
            var modal = $(this);
            //標題
            modal.find('.modal-title').text(ModalTitle);
             //== 將送出按鈕添加 目前顯示的資料是哪個tab ==//
            if(Tabid && Tabhid && Tab_did)
            {
                modal.find('#s_submit').attr('data-id',Tabid);
                modal.find('#s_submit').attr('data-hid',Tabhid);
                modal.find('#s_submit').attr('data-did',Tab_did);
            }
            else
            {
                console.error("取得列表欄位出錯，data-id、data-hid、Tab_did 取得失敗");
            }
        });

        //儲存按鈕送出
        $('#s_submit').on('click',function(event){
            let target = event.target;
            //== 取得 Tab id  與 行數id (用來回寫) ==//
            let sid = target.dataset.id ? target.dataset.id: false;
            let hid = target.dataset.hid ? target.dataset.hid: false;
            let did = target.dataset.did ? target.dataset.did: false;

            //以下都不可以是false
            if(!sid && !hid && !did)
            {
                alert("取得 sid、hid、did 失敗，請檢查");
            }
            //Modal id
            let Modal = document.getElementById('s_creationModal');

            //抓Modal的欄位(抓取上方修改的資料)
            let st1Tag = Modal.querySelector('[data-tid="'+sid+'"]') ? Modal.querySelector('[data-tid="'+sid+'"]') : false;
            // let st1Inpus = st1Tag.querySelectorAll('input') ? st1Tag.querySelectorAll('input') : false;
            let st1Inpus = st1Tag.querySelectorAll('[data-guise="input"]') ? st1Tag.querySelectorAll('[data-guise="input"]') : false;
            if(st1Inpus)
            {
                let st1_array = {};
                //將資料整理到obj
                st1Inpus.forEach(item=>{
                    // st1_array[item.dataset.name] = item.value;
                    st1_array[item.dataset.name] = (item.value == "null" ? "":item.value);
                });
                //整理資料
                AllarrayS = [];
                //== 依據Tab取名 ==//
                let Job = { _token: $("meta[name='csrf-token']").attr('content') };
                Job[TabKeyAll[sid]] = st1_array;
                //寫入共用陣列
                AllarrayS.push({'Data':Job,'Tabsid':sid,'Tabhid':hid,'Tabdid':did,'Url':TabKeyUrlAll[sid]});
            }

            let this_url = '';
            if(sid=='s_t1' || sid=='s_t2' || sid=='s_t3'){ this_url = '/ajax_chuang_edit';
            }else if(sid=='s_t6'){ this_url = '/ajax_lou_period_edit'; }

            //送入ajax
            ajax_submit(AllarrayS[0], this_url);

        });

        function ajax_submit(json, this_url)
        {
            $.ajax({
                url:this_url,
                method: "post",
                data:
                {
                    _token: $("meta[name='csrf-token']").attr('content'),
                    json
                },
                success: function (res) {
                    if (res.status == "success") {
                        UpdatePageS();
                        alert('{{__('src.更新成功')}}');
                    }else{
                        alert('{{__('src.發生錯誤')}}');
                    }
                },
                error: function () {
                    UpdatePageS();
                    alert('{{__('src.存儲失敗')}}');

                },
            });
        }

        function UpdatePageS()
        {
            //抓取共用陣列來知道目前更新的是誰
            let sid = AllarrayS[0].Tabsid;
            let tableTag = document.querySelector('table[data-id="'+sid+'"]') ? document.querySelector('table[data-id="'+sid+'"]') : false;
            let tableH = AllarrayS[0].Tabhid;
            let did = AllarrayS[0].Tabdid;
            if(tableTag)
            {
                let Tr = tableTag.querySelector('[data-did="'+did+'"]') != false ? tableTag.querySelector('[data-did="'+did+'"]') : false;
                if(!Tr)
                {
                    alert('回寫資料 UpdatePageS()，無法取得did，請檢查原始碼Tr是否有 data-did');
                }
                let Trchild = Tr.children;
                //(最後一欄是按鈕)
                let TrLength = Trchild.length - 1;
                let Th = Tr.querySelector('th')
                let Td = Tr.querySelectorAll('td');
                let TdButton = Td[Td.length -1].querySelector('button');
                let newjob = {};
                //== 依據Tab取名==//
                //將資料寫進去空白Obj裏面
                newjob = AllarrayS[0].Data[TabKeyAll[sid]];
                //只有取值不取key
                let newValue = Object.values(newjob);
                //將資料寫回區表格裡面
                // Th.textContent = newValue[0];
                // for(let i=0; i<(newValue.length - 1);i++)
                //將資料寫回區表格裡面 (最後一欄是按鈕)
                for(let i=0; i<TrLength;i++)
                {
                    Td[i].textContent = newValue[i+1];
                }
                //修改按鈕上的欄位
                // for(let i=0; i<=newValue.length;i++)
                for(let i=0; i<TrLength;i++)
                {
                    // TdButton.dataset['h'+Number(i+1)] = newValue[i];
                    //空的按鈕寫回去null
                    TdButton.dataset['h'+Number(i+1)] = newValue[i] == "" ? "null" : newValue[i] ;
                }
            }

            $('#s_creationModal').modal('hide');
        }

        $('#SchangeDatasS').on('show.bs.modal', function (event) {
            //資料長度
            let DataLength = 10;
            let button = $(event.relatedTarget);
            let buttonData = event.relatedTarget;
            let did2 = buttonData.dataset.did;

            //== Modal標題資料 ==//
            let ModalTitle = button.data('title') ? button.data('title') : false;

            //== 取得 data-id ==//
            let SSid = button.data('ssid') ? button.data('ssid') : false;

            //== 判斷Modal顯示的資料 ==//
            if(SSid != false && document.querySelector('[data-tid="s_ssid'+SSid+'"]'))
            {
                //== 先將所有Modal資料隱藏 ==//
                for(let i=1; i<= DataLength; i++)
                {
                        //== 判斷data-tid是否存在 ==//
                    if(document.querySelector('[data-tid="s_ssid'+i+'"]'))
                    {
                        document.querySelector('[data-tid="s_ssid'+i+'"]').classList.add('d-none');
                    }
                }
                //== 顯示正確資料 ==//
                document.querySelector('[data-tid="s_ssid'+SSid+'"]').classList.remove('d-none');
            }
            else
            {
                console.error("Modal資料顯示出錯");
            }

            //== 取得資料的詳細名稱 ==//
            let TabClass = document.querySelector('[data-tid="s_ssid'+SSid+'"]');
            let stInpus = TabClass.querySelectorAll('[data-guise="input"]');
            let titles = TabClass.dataset.title;

            let DatajOB = [];
            for(let i=0; i<DataLength;i++)
            {
                if(buttonData.dataset['sh'+(i+1)])
                {
                    DatajOB['sh'+(i+1)] = buttonData.dataset['sh'+(i+1)];
                }
            }
            var modal = $(this);
            let Inputs = stInpus;

            let i = 1;
            Inputs.forEach(item=>{
                if( DatajOB["sh"+i] != undefined )
                {
                    if(item.nodeName == "INPUT" && DatajOB["sh"+i] == "null")
                    {
                        item.value = "";
                    }
                    else
                    {
                        item.value = DatajOB["sh"+i];
                    }
                }
                else
                {
                    item.value = "";
                }
                i++;
            });

            //== 將資料寫上去Modal ==//
            var modal = $(this);
            //標題
            modal.find('.modal-title').text(ModalTitle);
            //== 將送出按鈕添加 目前顯示的資料是哪個tab ==//
            if(SSid && titles)
            {
                modal.find('#s_Saves_submit').attr('data-id','s_ssid'+SSid);
                modal.find('#s_Saves_submit').attr('data-did',did2);
                modal.find('#s_Saves_submit').attr('data-title',titles);
            }
            else
            {
                console.error("取得Modal與按鈕出錯，data-ssid、data-title 取得失敗");
            }
        });

        $('#s_Saves_submit').on('click',function(event){
            let target = event.target;
            let ssid = target.dataset.id ? target.dataset.id: false;
            let did2 = target.dataset.did ? target.dataset.did: false;
            //以下都不可以是false
            if(!ssid)
                {
                    alert("取得 sid、hid、did 失敗，請檢查");
                }
            let Modals = document.getElementById('SchangeDatasS');
            let st1Tag = Modals.querySelector('[data-tid="'+ssid+'"]') ? Modals.querySelector('[data-tid="'+ssid+'"]') : false;
            let st1Inpus = st1Tag.querySelectorAll('[data-guise="input"]') ? st1Tag.querySelectorAll('[data-guise="input"]') : false;
            if(st1Inpus)
            {
                let st1_array = {};
                //將資料整理到obj
                st1Inpus.forEach(item=>{
                    st1_array[item.dataset.name] = (item.value == "null" ? "" : item.value);
                });
                //整理資料
                AllarrayS = [];
                //== 依據Tab取名 ==//
                let Job = { _token: $("meta[name='csrf-token']").attr('content') };
                Job = st1_array;
                //寫入共用陣列

                if(target.dataset.id && target.dataset.title)
                {
                AllarrayS.push({ 'Data':Job,'id':target.dataset.id,'title':target.dataset.title, 'did2':did2 });
                }
                else
                {
                console.error("Modal送出按鈕出錯， data-ssid、data-title 取得失敗");
                }

                let this_url2 = '';
                let arr_id = AllarrayS[0]['id'];
                if(arr_id=="s_ssid1") { this_url2="/ajax_creator_edit";
                }else if(arr_id=="s_ssid2") { this_url2="/ajax_creator_edit"; }
                //送入ajax
                if(this_url2!=''){ ajax_submit(AllarrayS[0], this_url2); }
                UpdateDataDetail();
            }

            //資料回寫button ata-ssid="1"
            function UpdateDataDetail()
            {
                let ssid = AllarrayS[0].id;
                let splitid = ssid.split('s_ssid');
                let newjob = {};
                //== 依據Tab取名==//
                //將資料寫進去空白Obj裏面
                newjob = AllarrayS[0].Data;
                //只有取值不取key
                let newValue = Object.values(newjob);
                let newbutton = document.querySelector('[data-ssid="'+splitid[1]+'"]');

                for(let i=0; i<newValue.length;i++)
                {
                    document.querySelector('[data-ssid="'+splitid[1]+'"]').dataset['sh'+(i+1)] = newValue[i] == "" ? "null" : newValue[i];
                }

                $('#SchangeDatasS').modal('hide');
            }
        });

        $('#chuang_pic').on('change', function(){
            console.log($(this).val());
            // 刷新樓層
            $.ajax({
                url:'/ajax_get_lou',
                method: "post",
                data: 
                {
                    _token: $("meta[name='csrf-token']").attr('content'),
                    values: $(this).val(),
                    caseid: "{{optional($caseData)->id}}"
                },
                success: function (res) {
                    if (res.status == "success") {
                        $('#hu_pic').empty();

                        $('#hu_pic').append($('<option></option>').attr('value', '0').text('樓層'));
                        $.each(res.lou_data, function (index, value) {
                            // console.log(value);
                            $('#hu_pic').append($('<option></option>').attr('value', value['id']).text(value['name']));
                        })
                        
                    }else{
                        alert('{{__('src.發生錯誤')}}');
                    }
                },
                error: function () {
                    alert('{{__('src.取樓資料成功失敗')}}');

                },
            });
        });

    </script>

    <script type="text/javascript">
        // ajax
        function chuang_del(chuangid)
        {
        $.ajax({
                url: "/ajax_chuang_del",
                method: "post",
                data: {
                    _token: $("meta[name='csrf-token']").attr('content'),
                    chuangid: chuangid
                },
                error: function () {
                    Swal.close();
                    alert('發生錯誤(estate_index_ajax0)');
                },
                success: function (res) {
                    Swal.close();
                    if (res.status != "success") {
                        alert('發生錯誤(estate_index_ajax1)');
                    }else{
                        if(res.result=="del_data_not_exist"){
                            alert('刪除資料不存在');
                        }else if(res.result=="wrong"){
                            alert('發生錯誤(estate_index_ajax2)');
                        }else if(res.result=="deleted"){
                            $("#trch_"+chuangid).remove();
                        }
                    }
                }
            });
        }
        function reolad_lous(chuang2_id)
        {
            $.ajax({
                url: "/ajax_reolad_lous",
                method: "post",
                data: {
                    _token: $("meta[name='csrf-token']").attr('content'),
                    chuang2_id: chuang2_id
                },
                error: function () {
                    Swal.close();
                    alert('發生錯誤(estate_index_ajax0)');
                },
                success: function (res) {
                    Swal.close();
                    if (res.status != "success") {
                        alert('發生錯誤(estate_index_ajax1)');
                    }else{
                        if(res.result=="no_data"){
                            alert('查無該棟資料');
                        }else if(res.result=="ok"){
                            let flrs = res.loudata;
                            $.each(flrs, function(louid, louname){
                                $('#slc_lou2').append($('<option>', {
                                    value: louid,
                                    text: louname
                                }));
                            });
                            $('#slc_lou2 option[value=""]').attr('selected', 'selected');
                        }
                    }
                }
            });
        }

    </script>


    <script>

    //標籤按鈕，新增移除效果
    //屬性設定：
    // data-labelname='span' ==  標籤用<span>
    // data-labelname='addbutton' ==  新增用<button>
    // data-labelname='delbutton' ==  移除用<button>
    // data-labelname='move' == 移動<button>
    // data-labelname="titleinput" ==  標題用<input>
    // data-labelname="imgbox" ==  圖片外框
    //id規則： s_Label_1 ~ s_Label_10 不可以重複
    let ssidname = "s_Label";
    let ssLabelsPan = "<span data-labelname='span'></span>";
    //圖片區塊
    const ImageLabelAll = document.getElementById('s_ImageLabelAll');
    //名稱輸入
    const s_titleinputLabel = document.getElementById('s_titleinputLabel');

    //== 標籤開始
    //== 1.Demoobj ==//
    let AllLabeljson = {
        "List":
            {
                "area":
                [
                    {
                        "name":"全區資料",
                        "tag":"A0",
                        "id":0,
                        "img": "",
                        "label": [
                            // 標籤資料
                            @foreach($chuang_tags as $tag)
                                { "name":"{{$tag->name}}", "id":{{$tag->id}}, "left":{{$tag->left}}, "top":{{$tag->top}} },
                            @endforeach
                        ],
                        // {
                        "floor": 
                            [
                                @foreach($chuangList as $chu)
                                    @foreach($chu->lou as $lou_d)
                                    { "name":"{{$lou_d->name}}",
                                        "tag":"A0_F{{$lou_d->id}}",
                                        "id":{{$lou_d->id}} ,
                                        "label": [
                                            @foreach($lou_d->hu_to_tag as $hutag)
                                            { "name":"{{$hutag->name}}","id":{{$hutag->id}},"left":{{$hutag->left}},"top":{{$hutag->top}} },
                                            @endforeach
                                        ],
                                    },
                                    @endforeach
                                @endforeach
                            ],
                        // },
                    },
                ]
            }
        };


    //載入標籤
    function S_LabelLOAD(json)
    {
        // 判斷全區是否有圖片
        if( $("#img_0").attr("src") != '\\')
        {
            //抓區被選取的img div
            if(document.querySelector('.s_img_labeBox.active'))
            {
                //整理好的json
                let Imagejson = json;
                //label長度
                let LabelLength = Imagejson ? Imagejson.length : 0;
                
                if(LabelLength > 0 )
                {
                    let LabeHtml = '';
                    Imagejson.forEach(item=>{
                        LabeHtml += "<span data-labelname='span' data-labelid='"+item.id+"' style='top:"+(item.top != "" && item.top ? item.top : "8")+"px; left:"+(item.left != "" && item.left ? item.left : "8")+"px;'>"+item.name+"</span>";
                    });
                    //目前顯示的圖片
                    let SthisImage = $('.s_img_labeBox.active');
                    SthisImage.append(LabeHtml);
                }

            }
        }
    }

    //載入標籤(//預設顯示A區)
    S_LabelLOAD(AllLabeljson['List']['area'][0].label);


    //選取標籤
    ImageLabelAll.addEventListener("click",(event)=>{
        let target = event.target;
        let labelname = ('labelname' in target.dataset) ? target.dataset.labelname : false;
        if(labelname == "span")
        {
            if(document.querySelector("span[data-labelname='span'].active"))
            {
                document.querySelector("span[data-labelname='span'].active").classList.remove('active');
            }

            target.classList.add('active');
            // s_titleinputLabel.value = document.querySelector("span[data-labelname='span'].active").textContent;
        }
    });

    //移動標籤
    $(".s_BtnGroud button").on('click',function(event){
        //先找尋是否有標籤被選取了
        if(document.querySelector("span[data-labelname='span'].active"))
        {
            let s_Span = document.querySelector("span[data-labelname='span'].active");
            switch($(this).data('simg'))
            {
                case "left":
                    if(s_Span.style.left != "" || s_Span.style.left !=null)
                    {
                        let s_SpanWidth = s_Span.offsetWidth;
                        let oldleft = s_Span.style.left;
                        let oldleftnumber = oldleft.split('px');
                        let newleft = Number(oldleftnumber[0]) - 10;
                        let leftvalue = (newleft <= 0 ) ? 0  : newleft;
                        s_Span.style.left = leftvalue + "px";
                        let spanid = s_Span.dataset.labelid;
                        thisPositionImageJson(spanid,"left",leftvalue);
                    }
                    break;
                case "right":
                    if(s_Span.style.left != "" || s_Span.style.left !=null)
                    {
                        //圖片外框寬度
                        let ImageWidths = document.querySelector('.s_img_labeBox.active') !=null ? document.querySelector('.s_img_labeBox.active').offsetWidth : false;

                        let s_SpanWidth = s_Span.offsetWidth;
                        let oldleft = s_Span.style.left;
                        let oldleftnumber = oldleft.split('px');
                        let newleft = Number(oldleftnumber[0]) + 10;
                        let leftvalue = (newleft > (ImageWidths - s_SpanWidth)) ? ImageWidths : newleft;
                        s_Span.style.left = leftvalue + "px";
                        let spanid = s_Span.dataset.labelid;
                        thisPositionImageJson(spanid,"left",leftvalue);
                    }
                    break;
                    case "up":
                        if(s_Span.style.top != "" || s_Span.style.top !=null)
                        {
                            let oldtop = s_Span.style.top;
                            let oldtopnumber = oldtop.split('px');
                            let newtop = Number(oldtopnumber[0]) - 10;
                            let topvalue = (newtop <= 0 ) ? 0  : newtop;
                            s_Span.style.top = topvalue + "px";
                            let spanid = s_Span.dataset.labelid;
                            thisPositionImageJson(spanid,"top",topvalue);
                        }
                    break;
                    case "down":
                        if(s_Span.style.top != "" || s_Span.style.top !=null)
                        {
                            //圖片高度
                            let ImageHeight = document.querySelector('.s_img_labeBox.active img').offsetHeight !=null ? document.querySelector('.s_img_labeBox.active img').offsetHeight : false;
                            let s_SpanHeight = s_Span.offsetHeight;
                            let oldtop = s_Span.style.top;
                            let oldtopnumber = oldtop.split('px');
                            let newtop = Number(oldtopnumber[0]) + 10;
                            let topvalue = (newtop >= (ImageHeight - s_SpanHeight) ) ?  ImageHeight : newtop;
                            s_Span.style.top =  topvalue + "px";
                            let spanid = s_Span.dataset.labelid;
                            thisPositionImageJson(spanid,"top",topvalue);
                        }
                    break;
            }
        }
        else
        {
            alert("未選取標籤");
        }

    });

    //新增標籤
    $('#s_addbuttonLabel').on('click',function(event){
        if($('#s_titleinputLabel').val() != "")
        {
            NEwLAbelSpan();
        }
        else
        {
            alert("未輸入名稱");
        }
    });

    //新增標籤
    function NEwLAbelSpan()
    {
        //判斷目前被選擇的圖片外框
        if(document.querySelector('.s_img_labeBox.active'))
        {
            //抓出對應id
            let Thisbox = document.querySelector('.s_img_labeBox.active');
            let Loadid = ('imgidbox' in Thisbox.dataset ) ? Thisbox.dataset.imgidbox : false;
            let area = AllLabeljson['List']['area'];
            //input抓去
            let areaTag = document.querySelector('[data-status="area"]');
            let buildingTag = document.querySelector('[data-status="building"]');
            let floorTag = document.querySelector('[data-status="floor"]');
            let householdTag = document.querySelector('[data-status="household"]');
            //區域
            if(buildingTag.value == 0 && floorTag.value == 0 && householdTag.value == 0)
            {
                let index = FindIndexPotion(area,Loadid);
                let spanlength = area[index]['label'].length;
                try {
                    let newid = area[index]['label'].length > 0 ? area[index]['label'][spanlength - 1].id + 1 : 1;
                    area[index]['label'].push({"name":$('#s_titleinputLabel').val(),"id":newid,"left":0,"top":0});
                    $('.s_img_labeBox.active').append("<span data-labelname='span' data-labelid='"+newid+"' style='top:0px;left:0px;'>"+$('#s_titleinputLabel').val()+"</span>");
                }
                catch (e)
                {
                    console.error("區域新增標籤失敗");
                }
            }
            //棟別
            if(buildingTag.value != 0 && floorTag.value == 0 && householdTag.value == 0)
            {
                try {
                    let areaKeyindex = FindIndexPotion(area,areaTag.value);
                    let buildingTag = document.querySelector('[data-status="building"]');
                    let buildingkey = buildingTag.value;
                    let buildingjson = area[areaKeyindex]['building'];

                    let index = FindIndexPotion(buildingjson,Loadid);
                    let spanlength = buildingjson[index]['label'].length;
                    let newid = buildingjson[index]['label'].length > 0 ? buildingjson[index]['label'][spanlength - 1].id + 1 : 1;
                    buildingjson[index]['label'].push({"name":$('#s_titleinputLabel').val(),"id":newid,"left":0,"top":0});
                    $('.s_img_labeBox.active').append("<span data-labelname='span' data-labelid='"+newid+"' style='top:0px;left:0px;'>"+$('#s_titleinputLabel').val()+"</span>");
                }
                catch (e)
                {
                    console.error("棟別新增標籤失敗");
                }
            }
            //樓層
            if(buildingTag.value != 0 && floorTag.value != 0 && householdTag.value == 0)
            {
                try {
                    let areaKeyindex = FindIndexPotion(area,areaTag.value);
                    let buildingTag = document.querySelector('[data-status="building"]');
                    let buildingkey = buildingTag.value;
                    let buildingjson = area[areaKeyindex]['building'];
                    let buildingindex = FindIndexPotion(buildingjson,areaTag.value+"_"+buildingkey);
                    let floorjson = buildingjson[buildingindex]['floor'];

                    let index = FindIndexPotion(floorjson,Loadid);
                    let spanlength = floorjson[index]['label'].length;
                    let newid = floorjson[index]['label'].length > 0 ? floorjson[index]['label'][spanlength - 1].id + 1 : 1;
                    floorjson[index]['label'].push({"name":$('#s_titleinputLabel').val(),"id":newid,"left":0,"top":0});
                    $('.s_img_labeBox.active').append("<span data-labelname='span' data-labelid='"+newid+"' style='top:0px;left:0px;'>"+$('#s_titleinputLabel').val()+"</span>");
                }
                catch (e)
                {
                    console.error("樓層新增標籤失敗");
                }
            }
            //戶別
            if(buildingTag.value != 0 && floorTag.value != 0 && householdTag.value != 0)
            {
                try {
                    let areaKeyindex = FindIndexPotion(area,areaTag.value);
                    let buildingTag = document.querySelector('[data-status="building"]');
                    let buildingkey = buildingTag.value;
                    let buildingjson = area[areaKeyindex]['building'];
                    let buildingindex = FindIndexPotion(buildingjson,areaTag.value+"_"+buildingkey);
                    let floorjson = buildingjson[buildingindex]['floor'];
                    let floorkey = areaTag.value+"_"+buildingkey+"_"+floorTag.value;
                    let floorindex = FindIndexPotion(floorjson,floorkey);
                    let householdjson = floorjson[floorindex]['household'];
                    let index = FindIndexPotion(householdjson,Loadid);
                    let spanlength = householdjson[index]['label'].length;
                    let newid = householdjson[index]['label'].length > 0 ? householdjson[index]['label'][spanlength - 1].id + 1 : 1;
                    householdjson[index]['label'].push({"name":$('#s_titleinputLabel').val(),"id":newid,"left":0,"top":0});
                    $('.s_img_labeBox.active').append("<span data-labelname='span' data-labelid='"+newid+"' style='top:0px;left:0px;'>"+$('#s_titleinputLabel').val()+"</span>");
                }
                catch (e)
                {
                    console.error("戶新增標籤失敗");
                }
            }

            console.log("新增最終結果");
            console.log(AllLabeljson);
        }
    }


    //將位置寫回標籤 spanid 標籤的id  ,Position 位置 left or top  ,values 值
    function thisPositionImageJson(spanid, Position, values)
    {
        //抓出對應id
        let Thisbox = document.querySelector('.s_img_labeBox.active');
        let Loadid = ('imgidbox' in Thisbox.dataset ) ? Thisbox.dataset.imgidbox : false;
        let area = AllLabeljson['List']['area'];
        //區域
        //input抓去
        let areaTag = document.querySelector('[data-status="area"]');
        let floorTag = document.querySelector('[data-status="floor"]');
        let householdTag = document.querySelector('[data-status="household"]');
        //區域
        if(floorTag.value == 0 && householdTag.value == 0)
        {
            try{
                let index = FindIndexPotion(area, Loadid);
                let areajson = area[index].label;
                let spanindex = FindSpanPotion(areajson, Number(spanid));
                let spanjson = areajson[spanindex];
                spanjson[Position] = values;

            }
            catch (e)
            {
                console.error("區域移動寫回發生錯誤");
            }

        }
        //樓
        if(floorTag.value != 0 && householdTag.value == 0)
        {
            try{
                let index = FindIndexPotion(area,areaTag.value);
                let areajson = area[index]['floor'];
                let floorkey = areaTag.value+"_F"+floorTag.value;
                let floorindex = FindIndexPotion(areajson,floorkey);
                let floorjson = areajson[floorindex]['label'];
                let spanindex = FindSpanPotion(floorjson,Number(spanid));
                let spanjson = floorjson[spanindex];
                spanjson[Position] = values;
            }
            catch (e)
            {
                console.error("樓移動寫回發生錯誤");
            }
        }
        //戶
        if(floorTag.value != 0 && householdTag.value != 0)
        {
            try{
                let index = FindIndexPotion(area,areaTag.value);
                let areajson = area[index]['floor'];
                let floorkey = areaTag.value+"_F"+floorTag.value;
                let floorindex = FindIndexPotion(areajson,floorkey);
                // return false
                if(floorindex == -1)
                {
                    console.error("戶尋找樓的json失敗！請查看是否json內沒有值"+ floorkey);
                    return false;
                }
                let floorjson = areajson[floorindex]['household'];
                let householdkey = floorkey+"_H"+householdTag.value;
                let householdindex = FindIndexPotion(floorjson,householdkey);
                if(householdindex == -1)
                {
                    console.error("戶尋找json失敗！請查看是否json內沒有值");
                    return false;
                }
                let householdjson = floorjson[householdindex]['label'];
                let spanindex = FindSpanPotion(householdjson,Number(spanid));
                let spanjson = householdjson[spanindex];
                spanjson[Position] = values;
            }
            catch (e)
            {
                console.error("戶移動寫回發生錯誤");
            }
        }
        console.log("將位置寫回標籤");
        // console.log(AllLabeljson);
    }

    //尋找所在位置回傳span
    function FindSpanPotion(List,spanid)
    {
        var index = $.map(List, function(item, index) {
            return item.id;
        }).indexOf(spanid);

        return index;
    }

    //切換棟別
    s_ChangeTypeImage.addEventListener('change',event =>{
        let target = event.target;
        let values = target.value;
        let status = ('status' in target.dataset) ? target.dataset.status : false;
        console.log("//切換棟別");
        // console.log(target);
        // console.log(values);
        // console.log(status);
        try {
            ChangeAllSelect(target,values,status);
        }
        catch (e) {
            console.error("切換出現錯誤");
        }
    });

    //切換執行區、棟、樓、戶
    function ChangeAllSelect(target,values,status)
    {
        //棟
        //區域開啟切換
        if(status == "area")
        {
            //開啟新的圖片
            let newactivebox = document.querySelector('[data-imgidbox="'+values+'');
            newactivebox.classList.add('active');
            newactivebox.classList.add('d-block');
            newactivebox.classList.remove('d-none');

            //抓取資料
            let area = AllLabeljson['List']['area'];
            var index = $.map(area, function(item, index) {
                return item.tag;
            }).indexOf(values);

            //如果棟別切換，其他下拉選單清除
            //取得區域
            let areavalue = document.querySelector('[data-status="area"]');
            let floorvalue = document.querySelector('[data-status="floor"]');
            let householdvalue = document.querySelector('[data-status="household"]');
            floorvalue.value = 0;
            householdvalue.value = 0;
            //取得區域值
            let areakey = areavalue.value;

            //清除其他選項
            Clear_Changebar("area");

            //開啟新的圖片
            NewImageBoxOpen(areakey);

            //載入標籤
            S_LabelLOAD(area[index].label);
        }

        //樓
        if(status == "floor")
        {
            console.log("floor");

            //清除其他選項
            Clear_Changebar("floor");

            //取得區域值
            let areavalue = document.querySelector('[data-status="area"]');
            let areakey = areavalue.value;
            
            console.log("區域值");
            console.log(areakey);
            let floorvalue = document.querySelector('[data-status="floor"]');
            let floorkey = floorvalue.value!= 0 ? floorvalue.value : false;
            console.log("floor別值");
            console.log(floorkey);

            if(!floorkey)
            {
                floorvalue.value = 0;
                console.error("ChangeAllSelect() 取得不到樓層");
            }
            else
            {
                //取得json資料區域
                let area = AllLabeljson['List']['area'];
                var index = $.map(area, function(item, index) {
                    return item.tag;
                }).indexOf(areakey);

                // 取得json資料棟
                let floor = AllLabeljson['List']['area'][index]['floor'];
                let floorkeySumname = areakey+"_F"+floorkey;
                let flindex = $.map(floor, function(item, index) {
                    return item.tag;
                }).indexOf(floorkeySumname);

                if(flindex == -1)
                {
                    console.error('可能json與頁面html不同步，沒有'+floorkeySumname);
                }
                else
                {
                    let floorkey = floorkeySumname;
                    //開啟圖片
                    NewImageBoxOpen(floorkey);
                    //載入標籤
                    if(floor[flindex].label && floor[flindex].label.length >= 0)
                    {
                        S_LabelLOAD(floor[flindex].label);
                    }
                    else
                    {
                        console.log("無標籤");
                    }
                }
            }

        }

        //戶
        if(status == "household")
        {
            //取得區域值
            let areavalue = document.querySelector('[data-status="area"]');
            let areakey = areavalue.value;
            console.log("區域值");
            console.log(areakey);
            //取得樓
            let floorvalue = document.querySelector('[data-status="floor"]');
            let floorvaluevalue = floorvalue.value;
            console.log("樓值");
            console.log(floorvaluevalue);
            //取得戶
            let householdvalue = document.querySelector('[data-status="household"]');
            let householdvaluevalue = householdvalue.value;
            console.log("戶值");
            console.log(householdvaluevalue);

            if(floorvaluevalue == 0)
            {
                householdvalue.value = 0;
                alert("請依序選擇、樓、戶");
            }
            else
            {
                //取得json資料區域
                let area = AllLabeljson['List']['area'];
                var index = $.map(area, function(item, index) {
                    return item.tag;
                }).indexOf(areakey);

                if(areakey == -1)
                {
                    console.error("區域尋找資料失敗！可能json檔案沒有區域");
                    return false;
                }

                //取得json資料棟樓
                let floor = AllLabeljson['List']['area'][index]['floor'];
                let floorkey = areakey+"_F"+floorvaluevalue;
                var flindex = $.map(floor, function(item, index) {
                    return item.tag;
                }).indexOf(floorkey);

                if(flindex == -1)
                {
                    console.error("區域尋找資料失敗！可能json檔案沒有區域");
                    return false;
                }

                //取得json資料棟樓
                let household = floor[flindex]['household'];
                let householdkey = floorkey+"_H"+householdvaluevalue;
                var hoindex = $.map(household, function(item, index) {
                    return item.tag;
                }).indexOf(householdkey);

                let Endjson = household[hoindex];

                //開啟圖片
                NewImageBoxOpen(householdkey);
                //載入標籤
                if(Endjson)
                {
                    S_LabelLOAD(Endjson.label);
                }
                else
                {
                    console.error('json檔案內無此資料'+householdkey);
                }
            }
        }
    }

    //開啟新的圖片
    function NewImageBoxOpen(status)
    {
        //清除輸入名稱的欄位
        $('#s_titleinputLabel').val("");

        //移除舊的
        if(document.querySelector('[data-labelname="imgbox"].active'))
        {
            let oldimgbox = document.querySelector('[data-labelname="imgbox"].active');
            //清除舊標籤
            let spanAll = document.querySelectorAll('[data-labelname="span"]');
                spanAll.forEach(item=>{
                    item.remove();
            });
            //關閉舊的圖片
            oldimgbox.classList.remove('active');
            oldimgbox.classList.remove('d-block');
            oldimgbox.classList.add('d-none');
        }

        //開啟新的
        let newbox = document.querySelector('[data-imgidbox="'+status+'"]');
        if(newbox)
        {
            newbox.classList.add('active');
            newbox.classList.add('d-block');
            newbox.classList.remove('d-none');
        }
        else
        {
            console.error('讀取切換數值出錯,可能沒有這個圖片框 ， tag :'+status);
        }
    }

    //清除切換
    function Clear_Changebar(status)
    {
        //取得區域
        try
        {
            let areavalue = document.querySelector('[data-status="area"]');
            let floorvalue = document.querySelector('[data-status="floor"]');
            let householdvalue = document.querySelector('[data-status="household"]');
            if(status == "area")
            {
                floorvalue.value = 0;
                householdvalue.value = 0;
            }

            if(status == "floor" && householdvalue)
            {
                householdvalue.value = 0;
            }
        }
        catch
        {
            console.error("清除選單失敗");
        }

    }


    //儲存目前的標籤
    function SaveImageBuTTon()
    {
        //取得區域key
        let newJson = AllLabeljson;
        let area = newJson["List"]['area'];
        let LabelObjlenght = LabelObj.length;
        let LabelObjkey = Object.keys(LabelObj);
        //區域回寫
        for(let i=0; i<LabelObjkey.length; i++)
        {
            if(area[i] && LabelObj[area[i].tag && area[i].tag])
            {
                area[i].label = LabelObj[area[i].tag];
            }
        }
        //棟抓出key
        let areassplit = [];
        let floorsplit = [];
        let householdsplit = [];
        for(let i=0; i<LabelObjkey.length; i++)
        {
            if(LabelObjkey[i].length == 4)
            {
                //拆解字串，抓出區域
                areassplit.push(LabelObjkey[i].split('_'));

            }
            if(LabelObjkey[i].length == 7)
            {
                //拆解字串，抓出樓
                floorsplit.push(LabelObjkey[i].split('_'));
            }

            if(LabelObjkey[i].length == 10)
            {
                //拆解字串，抓出戶
                householdsplit.push(LabelObjkey[i].split('_'));
            }
        }
        //棟回寫
        for(let i=0; i<areassplit.length; i++)
        {
            //先找出區域的位置
            var result = $.map(area, function(item, index) {
                return item.tag;
            }).indexOf(areassplit[i][0]);

            let buildingArray = area[result]["building"];

            //找出樓的棟
            let key = areassplit[i][0]+"_"+areassplit[i][1];
            var building = $.map(buildingArray, function(item, index) {
                return item.tag;
            }).indexOf(key);
            buildingArray[building].label = LabelObj[key];
        }
        //樓回寫
        //console.log(floorsplit);
        for(let i=0; i<floorsplit.length; i++)
        {
            //先找出區域的位置
            var result = $.map(area, function(item, index) {
                return item.tag;
            }).indexOf(floorsplit[i][0]);

            let buildingArray = area[result]["building"];

            //找出棟
            let key = floorsplit[i][0]+"_"+floorsplit[i][1];
            var building = $.map(buildingArray, function(item, index) {
                return item.tag;
            }).indexOf(key);

            let floorArray = area[result]["building"][building].floor;

            //找出樓
            let keyfloor = floorsplit[i][0]+"_"+floorsplit[i][1]+"_"+floorsplit[i][2];
            var floorindex = $.map(floorArray, function(item, index) {
                return item.tag;
            }).indexOf(keyfloor);

            //將樓寫回去
            floorArray[floorindex].label = LabelObj[keyfloor];
        }
        //戶回寫
        for(let i=0; i<householdsplit.length; i++)
        {
            //先找出區域的位置
            var result = $.map(area, function(item, index) {
                return item.tag;
            }).indexOf(householdsplit[i][0]);

            let buildingArray = area[result]["building"];

            //找出棟
            let key = householdsplit[i][0]+"_"+householdsplit[i][1];
            var building = $.map(buildingArray, function(item, index) {
                return item.tag;
            }).indexOf(key);

            let floorArray = area[result]["building"][building].floor;

            //找出樓
            let keyfloor = householdsplit[i][0]+"_"+householdsplit[i][1]+"_"+householdsplit[i][2];
            var floorindex = $.map(floorArray, function(item, index) {
                return item.tag;
            }).indexOf(keyfloor);

            let householdArray = area[result]["building"][building].floor[floorindex].household;

            let keyhousehold = householdsplit[i][0]+"_"+householdsplit[i][1]+"_"+householdsplit[i][2]+"_"+householdsplit[i][3];
            var householdindex = $.map(householdArray, function(item, index) {
                return item.tag;
            }).indexOf(keyhousehold);

            //戶 寫回去
            householdArray[householdindex].label = LabelObj[keyhousehold];

        }
    }

    //更換圖片
    $('#s_ImageLabel').on('click',function(event){
        //區域
        //input抓去
        $('#s_ImageLabelfile').click();

    });

    // $('#add_house').on('click',function(){
    //     if($("#img_0").attr("src") != '\\')
    //     {
    //         $('#add_house_real').click();
    //     }else{
    //         alert('請先選擇圖片');
    //     }
    // });

    $('#s_ImageLabelfile').on('change',function(event){
        //圖片上傳
        //更換圖片
        const curFile = event.target.files[0]; // 透過 input 取得的 file object
        const objectURL = URL.createObjectURL(curFile);
        console.log('objectURL', objectURL);
        //檔案存起來
        var Formdata = new FormData();
        Formdata.append('file', curFile);
        console.log('Formdata : ', Formdata);
        //更換區塊圖片
        //取得區域
        let area = AllLabeljson['List']['area'];
        let areavalue = document.querySelector('[data-status="area"]');
        let buildingvalue = document.querySelector('[data-status="building"]');
        let floorvalue = document.querySelector('[data-status="floor"]');
        let householdvalue = document.querySelector('[data-status="household"]');
        //區域(展示用)
        if(buildingvalue.value == 0 && floorvalue.value == 0 && householdvalue.value == 0) // 判斷是否為區(沒有選棟、樓、戶舊是區)
        {
            let areaindex = FindIndexPotion(area,areavalue.value);
            let areajson = area[areaindex];
            areajson.img = objectURL;
            let areaImage = areajson.img;
            $('[data-imgidbox="'+areavalue.value+'"]').find('img').attr('src',areaImage); // 把圖片放進<img>裡
            console.log(areajson);
        }

        //棟(展示用)
        if(buildingvalue.value != 0 && floorvalue.value == 0 && householdvalue.value == 0)
        {
            let areaindex = FindIndexPotion(area,areavalue.value);
            let areajson = area[areaindex]['building'];
            let buildingkey = areavalue.value+"_"+buildingvalue.value;
            let buildingindex = FindIndexPotion(areajson,buildingkey);
            let buildingjson = areajson[buildingindex];
            let buildingImage = buildingjson.img = objectURL;
            $('[data-imgidbox="'+buildingkey+'"]').find('img').attr('src',buildingImage);
        }

        //樓(展示用)
        if(buildingvalue.value != 0 && floorvalue.value != 0 && householdvalue.value == 0)
        {
            let areaindex = FindIndexPotion(area,areavalue.value);
            let areajson = area[areaindex]['building'];
            let buildingkey = areavalue.value+"_"+buildingvalue.value;
            let buildingindex = FindIndexPotion(areajson,buildingkey);
            let buildingjson = areajson[buildingindex]['floor'];
            let floorkey = areavalue.value+"_"+buildingvalue.value+"_"+floorvalue.value;
            let floorindex = FindIndexPotion(buildingjson,floorkey);
            let floorjson = buildingjson[floorindex];
            let floorImage = floorjson.img = objectURL;
            $('[data-imgidbox="'+floorkey+'"]').find('img').attr('src',floorImage);
        }
        //戶(展示用)
        if(buildingvalue.value != 0 && floorvalue.value != 0 && householdvalue.value != 0)
        {
            let areaindex = FindIndexPotion(area,areavalue.value);
            let areajson = area[areaindex]['building'];
            let buildingkey = areavalue.value+"_"+buildingvalue.value;
            let buildingindex = FindIndexPotion(areajson,buildingkey);
            let buildingjson = areajson[buildingindex]['floor'];
            let floorkey = areavalue.value+"_"+buildingvalue.value+"_"+floorvalue.value;
            let floorindex = FindIndexPotion(buildingjson,floorkey);
            let floorjson = buildingjson[floorindex]['household'];
            let householdkey = areavalue.value+"_"+buildingvalue.value+"_"+floorvalue.value+"_"+householdvalue.value;
            let householdindex = FindIndexPotion(floorjson,householdkey);
            let householdjson = floorjson[householdindex];
            let householdImage = householdjson.img = objectURL;
            $('[data-imgidbox="'+householdkey+'"]').find('img').attr('src',householdImage);
        }

        // let other_carry_data = $('form').serializeArray();
        // $.each(other_data,function(key,input){
        //     fd.append(input.name,input.value);
        // });
        Formdata.append('caseid', $("#case").val());
        Formdata.append('comid', $("#company").val());

        $.ajax({
            url:'/ajax_changeBg',
            method: "post",
            headers: {
                // 'Content-Type': 'multipart/form-data',
                // 'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:Formdata,
            cache: false,
            contentType: false,
            processData: false,
            success:function(data){
                console.log(data.status, data.result);
                if(data.status=="success")
                {
                    if(data.result=="ok"){
                        alert("更改成功");
                    }else if(data.result=="no_data"){
                        alert("更改失敗, 資料不存在。");
                    }
                }
                console.log("success");
            },
            error: function(data){
                console.log("error");
                console.log(Formdata);
            }
        })

    });

    //移除標籤
    function DelSpanLabel()
    {
        let activeTag = document.querySelector('.s_img_labeBox.active');
        if(activeTag)
        {
            //移除span標籤
            let spanAll = activeTag.querySelectorAll('[data-labelname="span"]');
            spanAll.forEach(item=>{
                item.remove();
            });
            //移除json內的span標籤
            JsonDelSpanLabel();
        }
        else
        {
            consle.error('沒有被選擇的圖片外框 s_img_labeBox 缺少 active');
        }
    }

    function JsonDelSpanLabel()
    {
        let area = document.querySelector('[data-status="area"]');
        let building = document.querySelector('[data-status="building"]');
        let floor = document.querySelector('[data-status="floor"]');
        let household = document.querySelector('[data-status="household"]');

        let areajson = AllLabeljson['List']['area'];
        //區域移除span
        if(building.value == 0 && floor.value == 0 && household.value == 0)
        {
            let areavalue = area.value;
            let areaindex = FindIndexPotion(areajson,areavalue);
            let areafilter = areajson[areaindex];
            areafilter.label = [];
        }
        //棟(展示用)
        if(building.value != 0 && floor.value == 0 && household.value == 0)
        {
            let areaindex = FindIndexPotion(areajson,area.value);
            let areafilter = areajson[areaindex]['building'];
            let buildingkey = area.value+"_"+building.value;
            let buildingindex = FindIndexPotion(areafilter,buildingkey);
            let buildingfilter = areafilter[buildingindex];
            buildingfilter.label = [];
        }
        //樓(展示用)
        if(building.value != 0 && floor.value != 0 && household.value == 0)
        {
            let areaindex = FindIndexPotion(areajson,area.value);
            let areafilter = areajson[areaindex]['building'];
            let buildingkey = area.value+"_"+building.value;
            let buildingindex = FindIndexPotion(areafilter,buildingkey);
            let buildingfilter = areafilter[buildingindex]['floor'];
            let floorkey = buildingkey+"_"+floor.value;
            let floorindex = FindIndexPotion(buildingfilter,floorkey);
            let floorfilter = buildingfilter[floorindex];
            floorfilter.label = [];
        }
        //戶(展示用)
        if(building.value != 0 && floor.value != 0 && household.value != 0)
        {
            let areaindex = FindIndexPotion(areajson,area.value);
            let areafilter = areajson[areaindex]['building'];
            let buildingkey = area.value+"_"+building.value;
            let buildingindex = FindIndexPotion(areafilter,buildingkey);
            let buildingfilter = areafilter[buildingindex]['floor'];
            let floorkey = buildingkey+"_"+floor.value;
            let floorindex = FindIndexPotion(buildingfilter,floorkey);
            let floorfilter = buildingfilter[floorindex]['household'];
            let householdkey = floorkey + "_"+household.value;
            let householdindex = FindIndexPotion(floorfilter,householdkey);
            let householdfilter = floorfilter[householdindex];
            householdfilter.label = [];
        }

        console.log(AllLabeljson);
    }


    //存儲標籤
    $('#s_SavebuttonLabel').on('click',function(event){
        let typeid = $('#type').val();
        //存擋後的動作
        let tags_json = AllLabeljson['List']['area'][0]['label'];
        console.log(tags_json);

        $.ajax({
                url:'/ajax_save_tags',
                method: "post",
                data:
                {
                    _token: $("meta[name='csrf-token']").attr('content'),
                    tags_json
                },
                success: function (res) {
                    if (res.status == "success") {
                        alert('{{__('src.儲存成功')}}');
                    }else{
                        alert('{{__('src.發生錯誤')}}');
                    }
                },
                error: function () {
                    alert('{{__('src.存儲失敗')}}');

                },
            });

    });

    //尋找所在位置回傳index
    function FindIndexPotion(List, Loadid)
    {
        var index = $.map(List, function(item, index) {
            return item.tag;
        }).indexOf(Loadid);

        return index;
    }

    </script>

@endpush