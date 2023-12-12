<?php

namespace App\Http\Controllers;

use App\Models\DeTaiSinhVien;
use App\Models\Nhom;
use Illuminate\Http\Request;

class DeTaiSinhVienController extends Controller
{
    public function createData(Request $request)
    {
        $data = $request->all();
        $id_user = $request->id_user;
        $nhom = Nhom::where('id_sinh_vien', $id_user)->first(); //mã nhóm
        $data['ma_nhom'] = $nhom->ma_nhom;
        $data['ten_nhom'] = $nhom->ten_nhom;

        // Kiểm tra tên đề tài trùng khoảng 80%
        $tenDeTaiMoi = $data['ten_de_tai'];
        $tenDeTaiDaTonTai = DeTaiSinhVien::where('ma_nhom', $data['ma_nhom'])
            ->pluck('ten_de_tai')
            ->toArray();

        foreach ($tenDeTaiDaTonTai as $tenDaTonTai) {
            similar_text($tenDeTaiMoi, $tenDaTonTai, $percent);
            if ($percent >= 80) {
                return response()->json([
                    'status'  => 0,
                    'message' => 'Tên đề tài của bạn dường như đã trùng lặp với một đề tài khác.',
                ]);
            }
        }

        // Nếu không có sự trùng lặp, tạo đề tài mới
        DeTaiSinhVien::create($data);

        return response()->json([
            'status'  => 1,
            'message' => 'Đã tạo đề tài thành công!',
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
