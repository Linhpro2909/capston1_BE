<?php

namespace App\Http\Controllers;

use App\Models\HoiDong;
use App\Models\NhatKy;
use App\Models\Nhom;
use App\Models\SinhVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TienDoController extends Controller
{
    public function getDataTienDo()
    {
        $data = Nhom::join('giang_viens', 'giang_viens.id', 'nhoms.id_giang_vien')
                    ->select('nhoms.id as id_nhom', 'nhoms.ten_nhom', 'nhoms.ma_nhom', 'nhoms.id_giang_vien', 'giang_viens.ten_giang_vien')
                    ->get();
        foreach ($data as $key => $value) {
            $check         = HoiDong::whereRaw("FIND_IN_SET('" . $value['ma_nhom'] . "', list_ma_nhom)")->first();

            if ($check) {
                $value['check'] = true;
            } else {
                $value['check'] = false;
            }

            $list_tv = SinhVien::join('diems', 'diems.id_sinh_vien', 'sinh_viens.id')
                                ->where('diems.id_nhom', $value['id_nhom'])
                                ->select('diems.id_sinh_vien', 'diems.id_nhom', 'sinh_viens.ten_sinh_vien', 'diems.id_casptone', 'diems.id_nhom')
                                ->get();

            $value['list'] = $list_tv;
            $value['id_casptone'] = $list_tv[0]['id_casptone'];
        }

        return response()->json([
            'data'   => $data,
        ]);
    }

    public function chiTietNhom(Request $request)
    {
        $nhat_ky_nhom = NhatKy::where('id_nhom', $request->id_nhom)
                                    ->select('nhat_kys.*', DB::raw ("DATE_FORMAT(thoi_gian,'%d/%m/%Y') as thoi_gian "))->get();
        return response()->json([
            'data'   => $nhat_ky_nhom,
        ]);
    }
}
