<?php

namespace App\Http\Controllers;

use App\Models\NienKhoa;
use Illuminate\Http\Request;

class NienKhoaController extends Controller
{
public function createData(Request $request){
    $data=$request->all();
    NienKhoa::create($data);
    return response()->json([
        'status'        => 1,
        'message'       => "Đã thêm niên khóa thành công!",
        // 'data'       => $request->all(),
    ]);
}
public function getData(){
    $data = NienKhoa::get();
    return response()->json([
        'status'    => 1,
        'data'      => $data,
    ]);
}
public function updateData(Request $request)
{
    $nien_khoa     = NienKhoa::where('id', $request->id)->first();
        if ($nien_khoa) {
            $nien_khoa->update($request->all());
            return response()->json([
                'status'    => 1,
                'message'   => 'Đã cập nhật  niên khóa thành công!',
            ]);
        } else {
            return response()->json([
                'status'    => 0,
                'message'   => 'niên khóa không tồn tại',
            ]);
        }
}
}
