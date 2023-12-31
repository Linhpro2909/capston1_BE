<?php

namespace App\Http\Controllers;

use App\Models\DeTaiSinhVien;
use App\Models\Diem;
use App\Models\GiangVien;
use App\Models\HoiDong;
use App\Models\Nhom;
use App\Models\SinhVien;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GiangVienController extends Controller
{
    public function actionApiLoginGiangVien(Request $request)
    {
        $data['email']             = $request->email;
        $data['password']          = $request->password;
        $check = Auth::guard('giang_vien')->attempt($data);
        if ($check) {
            $user = Auth::guard('giang_vien')->user();
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'status'    => true,
                'message'   => 'Đăng Nhập Thành Công!',
                'token'     => $tokenResult,
                'token_type' => 'Bearer',
            ]);
        }
        return response()->json([
            'status'    => false,
            'message'   => 'Tài khoản hoặc mật khẩu không đúng!',
        ]);
    }

    public function Logout()
    {
        Auth::guard('giang_vien')->logout();
        return response()->json([
            'status'    => 1,
            'message'   => "Đã đăng xuất thành công!"
        ]);
    }

    public function createData(Request $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
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

    public function getNhomDoAn(Request $request)
    {
        $giang_vien = GiangVien::join('nhoms', 'nhoms.id_giang_vien', 'giang_viens.id')
            ->leftJoin('de_tai_sinh_viens', function ($join) {
                $join->on('de_tai_sinh_viens.id_nhom', '=', 'nhoms.id')
                    ->where(function ($query) {
                        $query->where('de_tai_sinh_viens.tinh_trang', 1)
                            ->orWhere('de_tai_sinh_viens.tinh_trang', 0);
                    });
            })
            ->leftJoin('hoi_dongs', 'hoi_dongs.id', 'nhoms.id_hoi_dong')
            ->where('nhoms.id', $request->id)
            ->select('nhoms.ten_nhom', 'nhoms.id as id_nhom', 'giang_viens.ten_giang_vien', 'de_tai_sinh_viens.ten_de_tai', 'de_tai_sinh_viens.tinh_trang', 'hoi_dongs.ten_hoi_dong')
            ->get();
        foreach ($giang_vien as $key => $value) {
            $list_sv = SinhVien::join('diems', 'diems.id_sinh_vien', 'sinh_viens.id')
                ->where('id_nhom', $value['id_nhom'])
                ->select('diems.id as id_diem', 'sinh_viens.ten_sinh_vien', 'diems.diem_mentor')
                ->get();
            foreach ($list_sv as $k => $v) {
                if ($v['diem_mentor'] == null) {
                    $v['diem_mentor'] = 0;
                } else {
                    $v['diem_mentor'] = $v['diem_mentor'];
                }
            }
            $value['list'] = $list_sv;
        }
        return response()->json([
            'data'   => $giang_vien,
        ]);
    }

    public function updateDiemMentor(Request $request)
    {
        $now = Carbon::today();
        $hoi_dong = Nhom::where('nhoms.id', $request->id_nhom)
            ->join('hoi_dongs', 'hoi_dongs.id', 'nhoms.id_hoi_dong')
            ->first();
        $errors = [];

        if ($hoi_dong && $now->gte(Carbon::parse($hoi_dong->thoi_gian))) {
            $errors[] = 'Quá thời hạn cho điểm!';
            return response()->json([
                'status'    => 0,
                'errors'   => $errors,
            ]);
        } else {

            DB::beginTransaction();

            foreach ($request->list as $key => $value) {
                if ($value['diem_mentor'] == 0) {
                    $errors[] = 'Vui lòng nhập điểm cho sinh viên ' . $value['ten_sinh_vien'] . "!";
                } else {
                    Diem::where('id', $value['id_diem'])
                        ->update(['diem_mentor' => $value['diem_mentor']]);
                }
            }

            if (!empty($errors)) {
                DB::rollBack();
                return response()->json([
                    'status'  => 0,
                    'errors'  => $errors,
                ]);
            } else {
                DB::commit();
                return response()->json([
                    'status'  => 1,
                    'message' => 'Cho điểm ' . $request->ten_nhom . ' thành công',
                ]);
            }
        }
    }

    public function getNhom(Request $request)
    {
        $hoiDong = HoiDong::whereRaw("FIND_IN_SET(?, list_id_hoi_dong)", [$request->id])
            ->join('nhoms', 'nhoms.id_hoi_dong', 'hoi_dongs.id')
            ->join('de_tai_sinh_viens', 'de_tai_sinh_viens.id_nhom', 'nhoms.id')
            ->select('nhoms.id as id_nhom', 'nhoms.ten_nhom', 'list_id_hoi_dong', 'de_tai_sinh_viens.ten_de_tai', 'hoi_dongs.ten_hoi_dong', 'de_tai_sinh_viens.id_casptone', 'de_tai_sinh_viens.tinh_trang')
            ->get();
        foreach ($hoiDong as $key => $value) {
            $idsArray = explode(',', $value['list_id_hoi_dong']);
            $positions = array_keys($idsArray, (string)$request->id);
            $positions = array_map(function ($pos) {
                return $pos;
            }, $positions);
            $value['vi_tri'] = $positions[0];
            $value['id_giang_vien'] = $idsArray[$positions[0]];
            $giang_vien = GiangVien::where('id', $value['id_giang_vien'])->first();
            $value['ten_giang_vien'] = $giang_vien->ten_giang_vien;
            $list_tv = Nhom::join('diems', 'diems.id_nhom', 'nhoms.id')
                ->join('sinh_viens', 'sinh_viens.id', 'diems.id_sinh_vien')
                ->where('diems.id_nhom', $value['id_nhom'])
                ->select('diems.id as id_diem', 'sinh_viens.ten_sinh_vien', 'diems.id_nhom', 'diems.diem_chu_tich', 'diems.diem_thu_ky', 'diems.diem_uy_vien')
                ->get();
            foreach ($list_tv as $k_2 => $v_2) {
                if ($positions[0] == 0) {
                    $v_2['diem_tong'] = round($v_2['diem_chu_tich'], 2);
                } else if ($positions[0] == 1) {
                    $v_2['diem_tong'] = round($v_2['diem_thu_ky'], 2);
                } else {
                    $v_2['diem_tong'] = round($v_2['diem_uy_vien'], 2);
                }
            }
            $value['list'] = $list_tv;
            // dd($list_tv->toArray());
        }
        // dd(($hoiDong->toArray()));
        return response()->json([
            'data'   => $hoiDong,
        ]);
    }

    // public function updateDiem(Request $request)
    // {
    //     $now = Carbon::today();
    //     $hoi_dong = Nhom::where('nhoms.id', $request->id_nhom)
    //         ->join('hoi_dongs', 'hoi_dongs.id', 'nhoms.id_hoi_dong')
    //         ->first();
    //     $errors = [];

    //     if ($hoi_dong && $now->isSameDay(Carbon::parse($hoi_dong->thoi_gian))) {
    //         foreach ($request->list as $key => $value) {
    //             $diem = Diem::where('id', $value['id_diem'])->first();
    //             // dd($diem->toArray());
    //             if ($diem) {
    //                 if ($request->vi_tri == 0) {
    //                     if ($value['diem_chu_tich'] < 0 || $value['diem_chu_tich'] > 10 || $value['diem_chu_tich'] == null) {
    //                         $errors[] = 'Yêu cầu phải nhập điểm lớn hơn 0 và nhỏ hơn bằng 10!';
    //                         return response()->json([
    //                             'status'    => 0,
    //                             'errors'    => $errors,
    //                         ]);
    //                     }
    //                     $diem->diem_chu_tich = $value['diem_chu_tich'];
    //                 } else if ($request->vi_tri == 1) {
    //                     if ($value['diem_thu_ky'] < 0 || $value['diem_thu_ky'] > 10 ||  $value['diem_thu_ky'] == null) {
    //                         $errors[] = 'Yêu cầu phải nhập điểm lớn hơn 0 và nhỏ hơn bằng 10!';
    //                         return response()->json([
    //                             'status'    => 0,
    //                             'errors'    => $errors,
    //                         ]);
    //                     }
    //                     $diem->diem_thu_ky = $value['diem_thu_ky'];
    //                 } else {
    //                     if ($value['diem_uy_vien'] < 0 || $value['diem_uy_vien'] > 10 || $value['diem_uy_vien'] == null) {
    //                         $errors[] = 'Yêu cầu phải nhập điểm lớn hơn 0 và nhỏ hơn bằng 10!';
    //                         return response()->json([
    //                             'status'    => 0,
    //                             'errors'    => $errors,
    //                         ]);
    //                     }
    //                     $diem->diem_uy_vien = $value['diem_uy_vien'];
    //                 }
    //                 $diem->save();
    //             }
    //             if ($diem->diem_chu_tich != null && $diem->diem_thu_ky != null && $diem->diem_uy_vien != null) {
    //                 $diem_tong = round(((($diem['diem_mentor'] * 0.3) + (($diem['diem_chu_tich'] + $diem['diem_thu_ky'] + $diem['diem_uy_vien']) / 3) * 0.7)), 2);
    //                 $is_done    = DeTaiSinhVien::where('id_nhom', $request->id_nhom)->first();
    //                 if ($diem_tong >= 5) {
    //                     $diem->is_hoan_thanh = 1;
    //                     $diem->save();
    //                     Diem::create([
    //                         'id_sinh_vien'  => $diem['id_sinh_vien'],
    //                         'id_casptone'   => 2,
    //                     ]);
    //                 } else {
    //                     $diem->is_hoan_thanh = 0;
    //                     $diem->save();
    //                     //create
    //                     Diem::create([
    //                         'id_sinh_vien'  => $diem['id_sinh_vien'],
    //                         'id_casptone'   => 1,
    //                     ]);
    //                 }
    //                 $is_done->is_done = 1;
    //                 $diem->save();
    //             }
    //         }
    //         return response()->json([
    //             'status'    => 1,
    //             'message'   => 'Đã cho điểm thành công!',
    //         ]);
    //     } else {
    //         $errors[] = 'Quá thời hạn cho điểm!';
    //         return response()->json([
    //             'status'    => 0,
    //             'errors'   => $errors,
    //         ]);
    //     }
    // }

    public function updateDiem(Request $request)
    {
        $now = Carbon::today();
        $hoi_dong = Nhom::where('nhoms.id', $request->id_nhom)
            ->join('hoi_dongs', 'hoi_dongs.id', 'nhoms.id_hoi_dong')
            ->first();
        $errors = [];

        if (!$hoi_dong || !$now->isSameDay(Carbon::parse($hoi_dong->thoi_gian))) {
            $errors[] = 'Quá thời hạn cho điểm!';
            return response()->json([
                'status'    => 0,
                'errors'   => $errors,
            ]);
        }

        foreach ($request->list as $key => $value) {
            $diem = Diem::where('id', $value['id_diem'])->first();
            if (!$diem) {
                continue; // Skip if diem is not found
            }

            $diemIsValid = true; // Flag to check if diem is valid

            if ($request->vi_tri == 0) {
                if (!isset($value['diem_chu_tich']) || $value['diem_chu_tich'] < 0 || $value['diem_chu_tich'] > 10) {
                    $errors[] = 'Yêu cầu chu tịch phải nhập điểm cho sinh viên '. $value['ten_sinh_vien'] .' và điểm từ 0 - 10!';
                    $diemIsValid = false;
                } else {
                    $diem->diem_chu_tich = $value['diem_chu_tich'];
                }
            }
            else if ($request->vi_tri == 1) {
                if (!isset($value['diem_thu_ky']) || $value['diem_thu_ky'] < 0 || $value['diem_thu_ky'] > 10) {
                    $errors[] = 'Yêu cầu thư ký phải nhập điểm cho sinh viên '. $value['ten_sinh_vien'] .' và điểm từ 0 - 10!';
                    $diemIsValid = false;
                } else {
                    $diem->diem_thu_ky = $value['diem_thu_ky'];
                }
            }
            else {
                if (!isset($value['diem_uy_vien']) || $value['diem_uy_vien'] < 0 || $value['diem_uy_vien'] > 10) {
                    $errors[] = 'Yêu cầu ủy viên phải nhập điểm cho sinh viên '. $value['ten_sinh_vien'] .' và điểm từ 0 - 10!';
                    $diemIsValid = false;
                } else {
                    $diem->diem_uy_vien = $value['diem_uy_vien'];
                }
            }
            if ($diemIsValid) {
                $diem->save();
                $diemSV = Diem::where('id', $value['id_diem'])->first();
                if ($diemSV->diem_chu_tich != null && $diemSV->diem_thu_ky != null && $diemSV->diem_uy_vien != null) {
                    $diem_tong = round(((($diemSV->diem_mentor * 0.3) + (($diemSV->diem_chu_tich + $diemSV->diem_thu_ky + $diemSV->diem_uy_vien) / 3) * 0.7)), 2);
                    $is_done    = DeTaiSinhVien::where('id_nhom', $request->id_nhom)->first();
                    if ($diem_tong >= 5) {
                        $diemSV->is_hoan_thanh = 1;
                        $diemSV->save();
                        $x = Diem::create([
                            'id_sinh_vien'  => $diemSV->id_sinh_vien,
                            'id_casptone'   => 2,
                        ]);

                    } else {
                        $diemSV->is_hoan_thanh = 1;
                        $diemSV->save();
                        //create
                        Diem::create([
                            'id_sinh_vien'  => $diemSV->id_sinh_vien,
                            'id_casptone'   => 1,
                        ]);
                    }

                    $is_done->is_done = 1;
                    $is_done->save();
                }
            }

        }

        if (!empty($errors)) {
            return response()->json([
                'status' => 0,
                'errors' => $errors,
            ]);
        }

        return response()->json([
            'status'  => 1,
            'message' => 'Đã cho điểm thành công!',
        ]);
    }

}
