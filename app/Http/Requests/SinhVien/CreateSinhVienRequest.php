<?php

namespace App\Http\Requests\SinhVien;

use Illuminate\Foundation\Http\FormRequest;

class CreateSinhVienRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ten_sinh_vien'     => "required",
            'ma_sinh_vien'      => "required|unique:sinh_viens,ma_sinh_vien",
            'so_dien_thoai'     => "required|numeric|min:10",
            'id_nien_khoa'      => "required|",
            'diem_gpa'          => "required|numeric|min:0|max:10",
            'password'          => "required|min:3",
        ];
    }

    public function messages()
    {
        return [
            'ten_sinh_vien.required'     => "Yêu cầu phải nhập tên sinh viên!",
            'ma_sinh_vien.required'      => "Yêu cầu phải nhập mã sinh viên!",
            'ma_sinh_vien.unique'        => "Mã sinh viên đã tồn tại!",
            'so_dien_thoai.required'     => "Yêu cầu nhập số điện thoại!",
            'so_dien_thoai.numeric'      => "Yêu cầu số điện thoại phải là số!",
            'so_dien_thoai.min'          => "Yêu cầu số điện thoại phải đủ 10 số!",
            'id_nien_khoa.required'      => "Yêu cầu phải chọn khóa!",
            'diem_gpa.required'          => "Yêu cầu nhập điểm GPA!",
            'diem_gpa.numeric'           => "Yêu điểm GPA phải là số!",
            'diem_gpa.max'               => "Yêu điểm GPA phải trong khoảng 0 - 10!",
            'diem_gpa.min'               => "Yêu điểm GPA trong khoảng 0 - 10 !",
            'password.required'          => "Yêu cầu phải nhập mật khẩu!",
            'password.required'          => "Yêu cầu phải nhập mật khẩu!",
            'password.min'               => "Mật khẩu phải từ 3 ký tự trở lên!",
        ];
    }
}
