<?php

namespace App\Http\Controllers;

use App\Models\DeTaiSinhVien;
use Illuminate\Http\Request;

class DeTaiSinhVienController extends Controller
{
    public function createData(Request $request)
    {
        $data = $request->all();
        DeTaiSinhVien::create($data);
        return response()->json([
            'status'        => 1,
            'message'       => "Đã tạo đề tài thành công!",
            // 'data'       => $request->all(),
        ]);
    }
    public function getData()
    {
        $data = DeTaiSinhVien::get();
        return response()->json([
            'status'    => 1,
            'data'      => $data,
        ]);
    }
    public function trangthai(Request $request)
    {

        $de_tai = DeTaiSinhVien::where('id', $request->id)->first();
        if($de_tai){
            $de_tai->tinh_trang = !$de_tai->tinh_trang;
            $de_tai->save();
        }
    }
    public function trangthai1(Request $request)
    {

        $de_tai = DeTaiSinhVien::where('id', $request->id)->first();
        if($de_tai){
            $de_tai->tinh_trang = 2;
            $de_tai->save();
        }
    }
}
