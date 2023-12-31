<?php

namespace App\Http\Controllers;

use App\Models\TmpNhom;
use Illuminate\Http\Request;

class TmpNhomController extends Controller
{
    public function getData(Request $request)
    {
        // Lấy tất cả dữ liệu mà ko có điều kiện gì cả
        $data   =   TmpNhom::join('sinh_viens', 'sinh_viens.id', 'tmp_nhoms.id_sinh_vien')
                            ->where('tmp_nhoms.id_casptone', $request->id_casptone)
                            ->select('tmp_nhoms.id', 'tmp_nhoms.id_diem','sinh_viens.id as id_sinh_vien', 'sinh_viens.ten_sinh_vien', 'sinh_viens.diem_gpa')
                            ->get();
        return response()->json([
            'data'      => $data,
        ]);
    }

    public function createData(Request $request)
    {
        TmpNhom::create([
            'ten_sinh_vien' =>  $request->ten_sinh_vien,
            'id_sinh_vien'  =>  $request->id_sinh_vien,
            'id_casptone'   =>  $request->id_casptone,
            'id_diem'       =>  $request->id,
        ]);

        return response()->json([
            'status'=> 1,
            'message'=>'Thành Công'
        ]);
    }

    public function deleteData(Request $request)
    {
        $tmpnhom = TmpNhom::where('id', $request->id)->first();

        if ($tmpnhom) {
            $tmpnhom->delete();
            return response()->json([
                'status'        => 1,
                'message'       => "Đã xóa thành công",
            ]);
        } else {
            return response()->json([
                'status'        => 0,
                'message'       => "Không tìm thấy ",
            ]);
        }
    }
}
