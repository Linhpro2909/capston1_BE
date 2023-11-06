<?php

namespace App\Http\Controllers;

use App\Models\SinhVien;
use Illuminate\Http\Request;

class SinhVienController extends Controller
{
    public function createData(Request $request){
        $data=$request->all();
       SinhVien::create($data);
        return response()->json([
            'status'        => 1,
            'message'       => "Đã thêm sinh viên thành công!",
             'data'       => $request->all(),
        ]);
    }
    public function getData(){
        $data =SinhVien::get();
        return response()->json([
            'status'    => 1,
            'data'      => $data,
        ]);
    }
    public function getDataid(){
        $datadetail =SinhVien::where('id', $request->id)->first();
        if ($datadetail) {
            $datadetail->get($request->all());
            return response()->json([
                'status'    => 1,
                'message'   => 'Đã lấy chi tiết sinh viên thành công!',
                'data'      => $data,
                
            ]);
        } else {
            return response()->json([
                'status'    => 0,
                'message'   => 'Lấy chi tiết sinh viên thất bại',
            ]);
        }
    }
    public function updateData(Request $request)
{
    $sinh_vien  = SinhVien::where('id', $request->id)->first();
        if ($sinh_vien) {
            $sinh_vien->update($request->all());
            return response()->json([
                'status'    => 1,
                'message'   => 'Đã cập nhật thành công!',
                
            ]);
        } else {
            return response()->json([
                'status'    => 0,
                'message'   => 'Sinh viên không tồn tại',
            ]);
        }
}
}
