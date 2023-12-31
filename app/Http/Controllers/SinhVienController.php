<?php

namespace App\Http\Controllers;

use App\Http\Requests\SinhVien\CreateSinhVienRequest;
use App\Http\Requests\SinhVien\SinhVienLoginRequest;
use App\Models\Diem;
use App\Models\SinhVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SinhVienController extends Controller
{
    public function actionApiLoginSinhVien(SinhVienLoginRequest $request)
    {
        $data['ma_sinh_vien']      = $request->ma_sinh_vien;
        $data['password']          = $request->password;
        $check = Auth::guard('sinh_vien')->attempt($data);
        if ($check) {
            $user = Auth::guard('sinh_vien')->user();
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

    public function getData()
    {
        $data = SinhVien::get();
        return response()->json([
            'data'      => $data,
        ]);
    }

    public function createData(CreateSinhVienRequest $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $x = SinhVien::create($data);

        Diem::create([
            'id_sinh_vien'  => $x->id,
        ]);

        return response()->json([
            'status'        => 1,
            'message'       => "Đã thêm sinh viên thành công!",
        ]);
    }
    public function importSinhvien(Request $request)
    {
        try {
            // Kiểm tra xem có tệp được tải lên không
            if (!$request->hasFile('excel_file')) {
                return response()->json(['error' => 'Không có tệp được tải lên.'], 400);
            }

            $file = $request->file('excel_file');

            // Kiểm tra định dạng của tệp (xls hoặc xlsx)
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, ['xls', 'xlsx'])) {
                return response()->json(['error' => 'Định dạng tệp không hợp lệ. Vui lòng tải lên tệp Excel.'], 400);
            }

            $spreadsheet = IOFactory::load($file);

            $data = $spreadsheet->getActiveSheet()->toArray();
            foreach ($data as $row) {
                $sinhVien = SinhVien::create([
                    'ten_sinh_vien' => $row[0] ?? null,
                    'ma_sinh_vien' => $row[1] ?? null,
                    'so_dien_thoai' => $row[2] ?? null,
                    'id_nien_khoa' => $row[3] ?? null,
                    'diem_gpa' => $row[4] ?? null,
                    'tinh_trang' => $row[5] ?? null,
                    'password' => bcrypt($row[6] ?? null),
                    // Thêm các trường khác tương ứng với cấu trúc Excel của bạn
                ]);

                Diem::create([
                    'id_sinh_vien' => $sinhVien->id,
                    'id_casptone' => $row[7] ?? null,
                    // Thêm các trường khác tương ứng với cấu trúc Excel của bạn
                ]);
            }

            return response()->json(['message' => 'Dữ liệu đã được nhập thành công!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi khi nhập dữ liệu từ tệp Excel: ' . $e->getMessage()], 500);
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
                $sinh_vien = SinhVien::where('id', $v);

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
        $ten_can_tim    = '%' . $request->ten_sinh_vien . '%';
        $data   = SinhVien::where('ten_sinh_vien', 'like', $ten_can_tim)->get();

        return response()->json([
            'data'          => $data,
        ]);
    }
}
