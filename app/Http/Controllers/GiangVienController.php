<?php

namespace App\Http\Controllers;

use App\Models\GiangVien;
use Illuminate\Http\Request;

class GiangVienController extends Controller
{
    public function createData(Request $request)
    {
        $data = $request->all();
        GiangVien::create($data);
        return response()->json([
            'status'        => 1,
            'message'       => "Đã thêm giảng viên thành công!",
            'data'       => $request->all(),
        ]);
    }
    public function getData()
    {
        $data = GiangVien::get();
        return response()->json([
            'status'    => 1,
            'data'      => $data,
        ]);
    }
    public function updateData(Request $request)
    {
        $sinh_vien  = GiangVien::where('id', $request->id)->first();
        if ($sinh_vien) {
            $sinh_vien->update($request->all());
            return response()->json([
                'status'    => 1,
                'message'   => 'Đã cập nhật thành công!',

            ]);
        } else {
            return response()->json([
                'status'    => 0,
                'message'   => 'Giảng viên không tồn tại',
            ]);
        }
    }
    public function deleteData(Request $request)
{

    $data = $request->all();

    $str = "";

    foreach ($data as $key => $value) {
        if (isset($value['check'])) {
            $str .= $value['id'] . ",";
        }

        $data_id = explode(",", rtrim($str, ","));

        foreach ($data_id as $k => $v) {
            $sinh_vien = GiangVien::where('id', $v);

            if ($sinh_vien) {
                $sinh_vien->delete();
            } else {
                return response()->json([
                    'status'    => false,
                    'message'   => 'Đã có lỗi sự cố!',
                ]);
            }
        }
    }

    return response()->json([
        'status'    => true,
        'message'   => 'Đã xóa thành công!',
    ]);
}
public function searchData(Request $request)
{
    $ten_can_tim    = '%' . $request->ten_giang_vien . '%';
    $data   = GiangVien::where('ten_giang_vien', 'like', $ten_can_tim)->get();

    return response()->json([
        'data'          => $data,
    ]);
}
}
