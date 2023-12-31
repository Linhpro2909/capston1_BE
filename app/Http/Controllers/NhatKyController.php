<?php

namespace App\Http\Controllers;

use App\Models\Diem;
use App\Models\NhatKy;
use App\Models\SinhVien;
use Illuminate\Http\Request;

class NhatKyController extends Controller
{
    public function getNhomDoAn(Request $request)
    {
        $id_user = $request->id_user;
        $sinh_vien = SinhVien::join('diems', 'diems.id_sinh_vien', 'sinh_viens.id')
            ->join('nhoms', 'nhoms.id', 'diems.id_nhom')
            ->join('giang_viens', 'giang_viens.id', 'nhoms.id_giang_vien')
            ->leftJoin('de_tai_sinh_viens', function ($join) {
                $join->on('de_tai_sinh_viens.id_nhom', '=', 'nhoms.id')
                    ->where(function ($query) {
                        $query->where('de_tai_sinh_viens.tinh_trang', 1)
                            ->orWhere('de_tai_sinh_viens.tinh_trang', 0);
                    });
            })
            ->leftJoin('hoi_dongs', 'hoi_dongs.id', 'nhoms.id_hoi_dong')
            ->leftJoin('casptones', 'casptones.id', 'de_tai_sinh_viens.id_casptone')
            ->where('diems.id_sinh_vien', $id_user)
            ->select('diems.id_sinh_vien', 'diems.id_casptone', 'diems.id_nhom', 'nhoms.ten_nhom', 'giang_viens.ten_giang_vien', 'de_tai_sinh_viens.ten_de_tai', 'de_tai_sinh_viens.is_done', 'de_tai_sinh_viens.tinh_trang as tinh_trang_de_tai', 'hoi_dongs.ten_hoi_dong')
            ->get();
        foreach ($sinh_vien as $key => $value) {
            $list_tv = SinhVien::join('diems', 'diems.id_sinh_vien', 'sinh_viens.id')
                ->where('diems.id_nhom', $value['id_nhom'])
                ->select('diems.id', 'diems.id_sinh_vien', 'sinh_viens.ten_sinh_vien', 'diems.diem_mentor', 'diems.diem_chu_tich', 'diems.diem_thu_ky', 'diems.diem_uy_vien')
                ->get();
            foreach ($list_tv as $k => $v) {
                if ($v['diem_mentor'] != null && $v['diem_chu_tich'] != null && $v['diem_thu_ky'] != null && $v['diem_uy_vien'] != null) {
                    $v['diem_tong'] = round(($v['diem_mentor'] * 0.3) + ((($v['diem_chu_tich'] + $v['diem_thu_ky'] + $v['diem_uy_vien']) / 3) * 0.7), 2);
                } else {
                    $v['diem_tong'] = 0;
                }
            }
            // dd($list_tv->toArray());

            $value['list'] = $list_tv;
        }
        return response()->json([
            'data'   => $sinh_vien,
        ]);
    }
    public function getDatChiTiet()
    {
        $data = NhatKy::get();
        return response()->json([
            'status'    => 1,
            'data'      => $data,
        ]);
    }

    public function createNhatKy(Request $request)
    {
        if (!empty($request->list)) {
            foreach ($request->list as $key => $value) {
                if ($value['tinh_trang_de_tai'] == 1 && $value['is_done'] == 0) {
                    NhatKy::create([
                        "mo_ta" => "qsdsadasd",
                        "id_sinh_vien" => $request->id_user,
                        "ten_nhat_ky" => $request->ten_nhat_ky,
                        "thoi_gian" => "2023-12-29",
                        "tinh_trang" => $request->tinh_trang,
                        'id_nhom' => $value['id_nhom'],
                    ]);
                } else {
                    continue;
                }
            }

            return response()->json([
                'status'    => 1,
                'message'   => 'Đã tạo nhật ký thành công!',
            ]);
        } else {
            return response()->json([
                'status'    => 1,
                'message'   => 'Bạn chưa có nhóm hoặc đề tài chưa được duyệt!',
            ]);
        }
    }

    public function getIdNhom($arr)
    {
        if (!empty($arr)) {
            foreach ($arr as $key => $value) {
                if ($value['tinh_trang_de_tai'] == 1 && $value['is_done'] == 0) {
                    return $value['id_nhom'];
                }
            }
        }
        return null;
    }

    public function getData(Request $request)
    {
        $id_nhom = $this->getIdNhom($request->list);
        if (is_null($id_nhom)) {
            return response()->json([
                'status' => 0,
                'message' => 'Nhóm không có hoặc không đủ điều kiện.',
            ]);
        }

        $data = NhatKy::join('nhoms', 'nhat_kys.id_nhom', '=', 'nhoms.id')
            ->join('sinh_viens', 'nhat_kys.id_nhom', 'sinh_viens.id')
            ->join('de_tai_sinh_viens', 'de_tai_sinh_viens.id_nhom', '=', 'nhoms.id')
            ->join('casptones', 'casptones.id', '=', 'de_tai_sinh_viens.id_casptone')
            ->where('nhat_kys.id_nhom', $id_nhom)
            ->select(
                'nhat_kys.id',
                'nhat_kys.id_sinh_vien',
                'sinh_viens.ten_sinh_vien',
                'nhoms.ten_nhom',
                'de_tai_sinh_viens.ten_de_tai',
                'nhat_kys.ten_nhat_ky',
                'nhat_kys.ten_file',
                'nhat_kys.file',
                'nhat_kys.mo_ta',
                'nhat_kys.thoi_gian',
                'nhat_kys.tinh_trang',
                'casptones.ten_casptone'
            )
            ->get();
        return response()->json([
            'status' => 1,
            'data' => $data,
        ]);
    }

    public function uploadFile(Request $request)
    {

        try {

            if ($request->hasFile('file')) {
                $ke_hoach = NhatKy::find($request->id);
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/uploaded_files', $fileName);
                if ($ke_hoach) {
                    $ke_hoach->update([
                        'ten_file'      => $request->ten_file,
                        'file'          => $fileName
                    ]);
                    return response()->json(['ke_hoach' => $ke_hoach, 'message' => 'Đã upload file thành công!', 'status' => 1]);
                }
            } else {
                return response()->json(['error' => 'No file uploaded.'], 400);
            }
        } catch (\Exception $e) {
            return response()->json([$e, 'error' => 'File upload failed.'], 500);
        }
    }

    public function downloadFile($filename)
    {
        $filePath = storage_path("app/public/uploaded_files/{$filename}");
        if (file_exists($filePath)) {
            $name = basename($filePath);

            return response()->download($filePath, $name);
        } else {
            return response()->json(['error' => 'File not found.'], 404);
        }
    }

    public function deleteNhatKy(Request $request)
    {
        $ke_hoach = NhatKy::find($request->id);
        if ($ke_hoach) {
            $ke_hoach->delete();
            return response()->json([
                'status'    => 1,
                'message'   => 'Đã xóa thành công nhật ký!',
            ]);
        } else {
            return response()->json([
                'status'    => 0,
                'message'   => 'Nhật ký không tồn tại!!',
            ]);
        }
    }
}
