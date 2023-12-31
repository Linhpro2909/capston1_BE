<?php

namespace App\Http\Controllers;

use App\Models\Casptone;
use App\Models\SinhVien;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class CasptoneController extends Controller
{
    public function getData()
    {
        $data = Casptone::get(['id', 'ten_casptone']);

        return response()->json([
            'data'   => $data,
        ]);
    }

    public function getDataSinhVien(Request $request)
    {
        $data = SinhVien::select('diems.id', 'ten_sinh_vien', 'diem_gpa', 'nien_khoas.ten_nien_khoa', 'nien_khoas.ma_nien_khoa', 'diems.id_casptone', 'diems.is_hoan_thanh', 'diems.id_sinh_vien')
            ->join('diems', 'sinh_viens.id', '=', 'diems.id_sinh_vien')
            ->join('casptones', 'diems.id_casptone', '=', 'casptones.id')
            ->join('nien_khoas', 'sinh_viens.id_nien_khoa', '=', 'nien_khoas.id')
            ->leftJoin('tmp_nhoms', 'sinh_viens.id', '=', 'tmp_nhoms.id_sinh_vien')
            ->where('diems.is_hoan_thanh', 0) //diểm hoàn thành chưa
            ->whereNull('diems.id_nhom') //chưa vào nhóm
            ->where('diems.id_casptone', $request->id_casptone) //lấy theo id_casptone
            ->where('nien_khoas.ma_nien_khoa', $request->ma_nien_khoa)
            ->whereNull('tmp_nhoms.id_sinh_vien')
            ->get();
        return response()->json([
            'data'   => $data,
        ]);
    }

    public function getSortDataSinhVien(Request $request)
    {
        // Xác định hướng sắp xếp dựa trên thông số 'type'
        if ($request->type == 1) {
            $sortColumn = 'diem_gpa';
            $sortDirection = 'ASC';
        } elseif ($request->type == 2) {
            $sortColumn = 'diem_gpa';
            $sortDirection = 'DESC';
        }

        // Xây dựng truy vấn
        $query = SinhVien::select('diems.id', 'ten_sinh_vien', 'diem_gpa', 'nien_khoas.ten_nien_khoa', 'nien_khoas.ma_nien_khoa', 'diems.id_casptone', 'diems.is_hoan_thanh', 'diems.id_sinh_vien')
            ->join('diems', 'sinh_viens.id', '=', 'diems.id_sinh_vien')
            ->join('casptones', 'diems.id_casptone', '=', 'casptones.id')
            ->join('nien_khoas', 'sinh_viens.id_nien_khoa', '=', 'nien_khoas.id')
            ->leftJoin('tmp_nhoms', 'sinh_viens.id', '=', 'tmp_nhoms.id_sinh_vien')
            ->where('diems.is_hoan_thanh', 0) // Diểm hoàn thành chưa
            ->whereNull('diems.id_nhom') // Chưa vào nhóm
            ->where('diems.id_casptone', $request->id_casptone) // Lấy theo id_casptone
            ->where('nien_khoas.ma_nien_khoa', $request->ma_nien_khoa)
            ->whereNull('tmp_nhoms.id_sinh_vien');

        // Áp dụng sắp xếp nếu cần
        if (isset($sortColumn) && isset($sortDirection)) {
            $query = $query->orderBy($sortColumn, $sortDirection);
        }

        // Thực hiện truy vấn và lấy kết quả
        $data = $query->get();

        return response()->json([
            'data' => $data,
        ]);
    }
}
