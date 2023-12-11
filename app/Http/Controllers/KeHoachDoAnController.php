<?php

namespace App\Http\Controllers;
use App\Models\KeHoachDoAn;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;

class KeHoachDoAnController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'file' => 'required',
            'ten_ke_hoach' => 'required',
            'thoi_gian' => 'required|date',
            'mo_ta' => 'required',
            'tinh_trang' => 'required',
            // Add validation rules for other fields as needed
        ]);
        $file = $request->file('file');
        $filePath = $file->store('/uploads','public');
        $fileName = $file->getClientOriginalName();
        $data = $request->all();
       
        $data['file'] = $fileName;
        $data['filepath'] = $filePath;
        $data = KeHoachDoAn::create($data);
        return response()->json(['data' => $data,'message' => 'File uploaded successfully']);

        // $file = $request->file('file');
        // $fileName = time() . '_' . $file->getClientOriginalName();
        // $filePath=$file->move(public_path('uploads'), $fileName);

        // $data = $request->all();
        // $data['file'] = $fileName;
        // $data['filepath'] = $filePath;

        // $data = KeHoachDoAn::create($data);

        // return response()->json(['data' => $data,'message' => 'File uploaded successfully']);
    }
    public function getData()
    {
        // Fetch all plans from the database
        $getdata = KeHoachDoAn::all();

        // Return the plans as JSON response
        return response()->json($getdata);
    }
    // public function downloadFile($fileName)
    // {
    //     // console.log($fileName);
    //     $filePath = public_path('uploads/' . $fileName);
        
    //     if (file_exists($filePath)) {
    //         return response()->download($filePath, $fileName);
    //     } else {
    //         return response()->json(['error' => 'File not found'], 404);
    //     }
    // }
    public function downloadFile($fileName)
    {
        $filePath = storage_path("uploads/{$fileName}");
        \Log::info('File Size: ' . filesize($filePath));
        // Kiểm tra xem file có tồn tại không
        if (file_exists($filePath)) {
            
            // Lấy tên file từ đường dẫn
            $name = basename($filePath);

            // Tạo response để trả về file
            return response()->download($filePath, $name);
        } else {
            // Nếu file không tồn tại, có thể trả về một thông báo lỗi hoặc chuyển hướng đến trang 404
            return response()->json(['error' => 'File not found.'], 404);
        }
    }
    
    
}
