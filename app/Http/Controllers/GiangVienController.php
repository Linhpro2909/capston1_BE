<?php

namespace App\Http\Controllers;

use App\Models\GiangVien;
use Illuminate\Http\Request;

class GiangVienController extends Controller
{
    
    public function createData(Request $request)
    {
        $data=$request->all();
        GiangVien::create($data);
        return response()->json([
            'status'    =>1,
            'message'   =>"Đã thêm giảng viên thành công!",
            'data'       => $request->all(),
        ]);
    }

    
    public function getData()
    {
        
        $data = GiangVien::get();
        return response()->json([
            'status'    =>1,
            'data'      =>$data,
        ]);
        
    }
    public  function updateData(Request $request)
    {
        $giangvien = GiangVien::where('id', $request->id)->first();
        if($giangvien){
            $giangvien->update($request->all());
            return response()->json([
                'status'    =>1,
                'message'   => 'Đã cập nhật thành công!',
            ]);
        }else{
            return response()->json([
                'status'    =>0,
                'message'   => 'Giảng viên không tồn tại',
            ]);
        }
        }
    }

    

