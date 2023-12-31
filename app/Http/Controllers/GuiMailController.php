<?php

namespace App\Http\Controllers;

use App\Mail\SendStudentEmail;
use App\Models\SinhVien;

use App\Models\GuiMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GuiMailController extends Controller
{
    public function sendEmailToAllStudents(Request $request)
    {
        // Validate the request data
        $request->validate([
            'noi_dung_gui' => 'required|string',
        ]);

        // Get all students with email
        $students = SinhVien::whereNotNull('gmail')->get();

        // Save the email content to the database
        $guiMail = GuiMail::create(['noi_dung_gui' => $request->input('noi_dung_gui')]);

        // Send email to each student
        foreach ($students as $student) {
            Mail::to($student->gmail)->send(new SendStudentEmail(['noi_dung_gui' => $request->input('noi_dung_gui')]));
        }

        return response()->json(['message' => 'Emails sent successfully!']);
    }
}
