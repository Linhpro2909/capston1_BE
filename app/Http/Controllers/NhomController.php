<?php

namespace App\Http\Controllers;

use App\Models\Diem;
use App\Models\HoiDong;
use App\Models\Nhom;
use App\Models\SinhVien;
use App\Models\TmpNhom;
use Illuminate\Http\Request;

class NhomController extends Controller
{
    public function createData(Request $request)
    {
        $data = $request->all();
        $nhom = Nhom::create([
            "ten_nhom"      => $request->ten_nhom,
            "ma_nhom"       => $request->ma_nhom,
            "id_giang_vien" => $request->id_giang_vien,
        ]);

        foreach ($data['list'] as $key => $value) {
            $diem = Diem::where('id', $value['id_diem'])->first();
            $diem->id_nhom = $nhom->id;
            $diem->save();
            TmpNhom::find($value['id'])->delete();
        }

        return response()->json([
            'status' => 1,
            'message' => "Đã tạo nhóm " . $request->ten_nhom . " thành công!"
        ]);
    }

    public function getDataNhom()
    {
        $data = Nhom::join('giang_viens', 'giang_viens.id', 'nhoms.id_giang_vien')
            ->select('nhoms.id as id_nhom', 'nhoms.ten_nhom', 'nhoms.ma_nhom', 'nhoms.id_giang_vien', 'giang_viens.ten_giang_vien', 'nhoms.id_hoi_dong')
            ->get();
        foreach ($data as $key => $value) {
            if ($value['id_hoi_dong'] != null) {
                $value['check'] = true;
            } else {
                $value['check'] = false;
            }
            $list_tv = SinhVien::join('diems', 'diems.id_sinh_vien', 'sinh_viens.id')
                ->where('diems.id_nhom', $value['id_nhom'])
                ->select('diems.id as id_diem','diems.id_sinh_vien', 'diems.id_nhom', 'sinh_viens.ten_sinh_vien', 'diems.id_casptone', 'diems.id_nhom')
                ->get();

            $value['list'] = $list_tv;
            $value['id_casptone'] = $list_tv[0]['id_casptone'];
        }
        return response()->json([
            'data' => $data,
        ]);
    }

    public function getSinhVienNhom(Request $request)
    {
        $data = SinhVien::select('diems.id', 'ten_sinh_vien', 'diem_gpa', 'diems.id_casptone', 'diems.is_hoan_thanh', 'diems.id_sinh_vien')
            ->join('diems', 'sinh_viens.id', '=', 'diems.id_sinh_vien')
            ->join('casptones', 'diems.id_casptone', '=', 'casptones.id')
            ->leftJoin('tmp_nhoms', 'sinh_viens.id', '=', 'tmp_nhoms.id_sinh_vien')
            ->where('diems.is_hoan_thanh', 0)
            ->whereNull('diems.id_nhom')
            ->where('diems.id_casptone', $request->id_casptone)
            ->whereNull('tmp_nhoms.id_sinh_vien')
            ->get();
        return response()->json([
            'data' => $data,
        ]);
    }

    public function thayDoiSinhVienNhom(Request $request)
    {
        $check         = HoiDong::whereRaw("FIND_IN_SET('" . $request->ma_nhom . "', list_ma_nhom)")->first();
        if ($check) {
            return response()->json([
                'status'    => 0,
                'message'   => "Nhóm đã vào hội đồng không thể đổi!"
            ]);
        } else {
            $diem = Diem::where('id', $request->id_sinh_moi)->first();
            $diem_id_cu = Diem::where('id', $request->id_sinh_vien_doi)->first();
            $diem->id_nhom = $request->id_nhom;
            $diem->save();

            $diem_id_cu->id_nhom = null;
            $diem_id_cu->save();
            $ket_qua = SinhVien::select('sinh_viens.id', 'sinh_viens.ten_sinh_vien', 'sinh_viens.id_nien_khoa', 'sinh_viens.tinh_trang', 'diems.id_casptone')
                                ->join('diems', 'sinh_viens.id', '=', 'diems.id_sinh_vien')
                                ->leftJoin('tmp_nhoms', 'sinh_viens.id', '=', 'tmp_nhoms.id_sinh_vien')
                                ->where('diems.is_hoan_thanh', 0)
                                ->whereNull('diems.id_nhom')
                                ->where('diems.id_casptone', $request->id_casptone)
                                ->whereNull('tmp_nhoms.id_sinh_vien')
                                ->get();

            return response()->json([
                'status'    => 1,
                'data'      => $ket_qua,
                'message'   => "Đã thay đổi thành viên nhóm thành công!"
            ]);
        }
    }

    public function deleteData(Request $request)
    {
        Diem::where('id_nhom', $request->id_nhom)->update(["id_nhom" => null]);
        Nhom::where('id', $request->id_nhom)->delete();
        return response()->json([
            'status'    => 1,
            'message'   => 'Đã xóa thành công nhóm ' . $request->ten_nhom . '!',
        ]);
    }
}
