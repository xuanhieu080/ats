@extends('layouts.user.main')
@section('content')
    <div class="container-xl">
        <div class="page-info m-auto">
            <form action="{{route('departments.store')}}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1 me-5">
                                <div>
                                    {{$title}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-5 p-lg-10">
                        <div class="mb-3 row">
                            <div class="col-12 mb-3">
                                <label class="required form-label">Tên</label>
                                <input type="text" class="form-control" placeholder="Nhập tên" required name="name"
                                       value="{{old('name')}}">
                                @error('name') <span class="text-danger error small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label class="required form-label">Slug</label>
                                <input type="text" class="form-control" placeholder="Nhập slug" required name="slug"
                                       value="{{old('slug')}}">
                                @error('slug') <span class="text-danger error small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Mô Tả</label>
                                <textarea class="form-control" name="description" data-bs-toggle="autosize"
                                          placeholder="Mô tả">{{old('description')}}</textarea>
                                @error('description') <span
                                        class="text-danger error small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label class="control-label col-form-label">Hiển Thị</label>
                                <label class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value="1" name="is_active" checked/>
                                    <span class="form-check-label">Hiển Thị</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Thêm Mới</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


@section('css')
    <style>
        .page-info {
            max-width: 500px;
        }
    </style>
@endsection
