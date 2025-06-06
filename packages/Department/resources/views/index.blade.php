@extends('layouts.user.main')

@section('content')
    <div class="container-xl">
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-body">
                        <div class="table-responsive">
                            <table class="table table-striped gy-7 gs-7 table-hover">
                                <thead>
                                <tr class="fw-bolder fs-6 text-gray-800">
                                    <th style="width: 50px">STT</th>
                                    <th>Tên</th>
                                    <th>Slug</th>
                                    @if((auth()->user()->is_super == 1 || auth()->user()->hasAnyPermission('department_update', 'department_delete')))
                                        <th style="width: 120px">Chức năng</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $key => $item)
                                    <tr id="row-{{ $item->id }}">
                                        <td class="align-middle">{{ ($data->currentPage() - 1) * $data->perPage() + $key + 1 }}</td>
                                        <td class="align-middle">{{ $item->name }}</td>
                                        <td class="align-middle">{{ $item->slug }}</td>
                                        @if((auth()->user()->is_super == 1 || auth()->user()->hasAnyPermission('department_update', 'department_delete')))
                                            <td class="text-center action d-flex gap-2">
                                                @if(auth()->user()->is_super == 1 || (auth()->user()->hasPermissionTo('department_update')))
                                                    <a href="{{route('departments.show', $item->id)}}"
                                                       data-toggle="tooltip" data-original-title="Edit">
                                            <span>
                                                  <label
                                                          data-kt-image-input-action="change"
                                                          data-bs-toggle="tooltip"
                                                          data-bs-dismiss="click" title=""
                                                          data-bs-original-title="Chi tiết"
                                                          aria-label="Chi tiết">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="icon icon-tabler icons-tabler-outline icon-tabler-pencil"><path
                                                            stroke="none" d="M0 0h24v24H0z" fill="none"/><path
                                                            d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"/><path
                                                            d="M13.5 6.5l4 4"/></svg>
                                                  </label>
                                            </span>
                                                    </a>
                                                    <a href="{{route('departments.employees', $item->id)}}"
                                                       data-toggle="tooltip" data-original-title="Nhân viên">
                                            <span>
                                                  <label
                                                          data-kt-image-input-action="change"
                                                          data-bs-toggle="tooltip"
                                                          data-bs-dismiss="click" title=""
                                                          data-bs-original-title="Nhân viên"
                                                          aria-label="Nhân viên">
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                       viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                       stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                       class="icon icon-tabler icons-tabler-outline icon-tabler-home-share"><path
                                                              stroke="none" d="M0 0h24v24H0z" fill="none"/><path
                                                              d="M9 21v-6a2 2 0 0 1 2 -2h2c.247 0 .484 .045 .702 .127"/><path
                                                              d="M19 12h2l-9 -9l-9 9h2v7a2 2 0 0 0 2 2h5"/><path
                                                              d="M16 22l5 -5"/><path d="M21 21.5v-4.5h-4.5"/></svg>
                                                  </label>
                                            </span>
                                                    </a>
                                                @endif
                                                @if(auth()->user()->is_super == 1 || auth()->user()->hasPermissionTo('department_delete'))
                                                    <a class="btn-delete deleteContainer"
                                                       data-id="{{$item->id}}"
                                                       data-url="{{route('departments.destroy', $item->id)}}"
                                                       data-row-id="row-{{$item->id}}"
                                                       data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal">
                                            <span class="text-red icon-remove">
                                                <label
                                                        data-kt-image-input-action="change"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-dismiss="click" title=""
                                                        data-bs-original-title="Xoá"
                                                        aria-label="Xoá">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                                                     class="icon icon-tabler icons-tabler-outline icon-tabler-x"><path
                                                            stroke="none" d="M0 0h24v24H0z" fill="none"/><path
                                                            d="M18 6l-12 12"/><path d="M6 6l12 12"/></svg>
                                                </label>
                                            </span>
                                                    </a>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination mt-3">
                            {!! $data->appends(request()->all())->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class=" container-xxl  d-flex flex-stack flex-wrap">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column">
                <h3 class="d-flex text-white fw-bold fs-2qx my-1 me-5 text-capitalize">
                    {{$title}}
                </h3>

                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-white opacity-75">
                        <a href="{{route('home')}}" class="text-white text-hover-primary">
                            Trang Chủ
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>

                    <li class="breadcrumb-item text-white opacity-75">
                        {{$title}}
                    </li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->

            <!--begin::Actions-->
            <form class="d-flex align-items-center flex-wrap py-2 mw-350px mw-lg-700px w-100 gap-5"
                  action="{{route('departments.index')}}" method="get">
                <div id="kt_header_search"
                     class="header-search d-flex align-items-center w-100 md-flex-1">
                    <div class="search w-100 position-relative" autocomplete="off">
                        <!--begin::Hidden input(Added to disable form autocomplete)-->
                        <input type="hidden">
                        <!--end::Hidden input-->
                        <!--begin::Icon-->
                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                        <span class="svg-icon svg-icon-2 svg-icon-lg-1 svg-icon-white position-absolute top-50 translate-middle-y ms-5">
											<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                 viewBox="0 0 24 24" fill="none">
												<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                                      rx="1" transform="rotate(45 17.0365 15.1223)" fill="black"></rect>
												<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                      fill="black"></path>
											</svg>
										</span>
                        <!--end::Svg Icon-->
                        <!--end::Icon-->
                        <!--begin::Input-->
                        <input type="text" class="form-control ps-15" name="search"
                               data-kt-search-element="input"
                               placeholder="Tìm kiếm theo tên"
                               value="{{request()->input('search')}}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                @if((auth()->user()->is_super == 1 || auth()->user()->hasPermissionTo('department_add')))
                    <a href="{{route('departments.create')}}" class="btn btn-success my-2">
                        <!-- Download SVG icon from http://tabler.io/icons/icon/plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" class="icon icon-2">
                            <path d="M12 5l0 14"></path>
                            <path d="M5 12l14 0"></path>
                        </svg>
                        Thêm Mới
                    </a>
                @endif
            </form>
            <!--end::Actions-->
        </div>
        <!--end::Container-->
    </div>
@endsection
@if(auth()->user()->is_super == 1 || auth()->user()->hasPermissionTo('department_delete'))
    @section('modal')
        @include('components.confirm-delete', ['titleDelete' => 'Nếu xóa thì tất cả khóa học và bài học cũng bị xóa.</br> Bạn có chắc chắn muốn xóa dữ liệu này không?'])
    @endsection
@endif

