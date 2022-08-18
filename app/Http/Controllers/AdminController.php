<?php

namespace App\Http\Controllers;

use App\Models\Tests;
use PHPUnit\Util\Test;
use App\Models\Doctors;
use App\Models\BloodBank;
use App\Models\Appointments;
use App\Models\TestAppointment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function addDoc(){
        return view('admin.addDoctor');
    }
    public function addTest(){
        return view('admin.addTest');
    }
    public function uploadDoc(Request $req){
        $doctor=new Doctors();
        $image=$req->img;
        $imgName=time().'.'.$image->getClientOriginalExtension();
        $req->img->move('doctorImages',$imgName);
        $doctor->image=$imgName;
        $doctor->name=$req->name;
        $doctor->phone=$req->number;
        $doctor->speciality=$req->speciality;
        $doctor->experience=$req->experience;
        $doctor->room=$req->roomnumber;
        $doctor->fee=$req->fee;

        $doctor->save();
        return redirect()->back()->with('message','Doctor added successfully!');
    }

    public function uploadtest(Request $req){
        $test=new Tests();
        $test->name=$req->name;
        $test->fee=$req->fee;
        $test->save();
        return redirect()->back()->with('message','Test added successfully!');
    }

    public function showAppointments(){
        $appointments=Appointments::all();
        return view('admin.showAppointments',compact('appointments'));
    }

    public function showTestAppointments(){
        $testAppointments=TestAppointment::all();
        return view('admin.showTestAppointments',compact('testAppointments'));
    }

    public function approveAppointment($id){
        $appointment=Appointments::find($id);
        $appointment->status='Approved';
        $appointment->save();
        return redirect()->back();
    }

    public function approveTestAppointment($id){
        $appointment=TestAppointment::find($id);
        $appointment->status='Done';
        $appointment->save();
        return redirect()->back();
    }

    public function cancelAppointmentAdmin($id){
        $appointment=Appointments::find($id);
        $appointment->status='Cancelled';
        $appointment->save();
        return redirect()->back();
    }

    public function cancelTestAppointmentAdmin($id){
        $appointment=TestAppointment::find($id);
        $appointment->status='Cancelled';
        $appointment->save();
        return redirect()->back();
    }

    public function showDoctors(){
        $doctors=Doctors::all();
        return view('admin.showDoctors',compact('doctors'));
    }

    public function fireDoctor($id){
        $doctor=Doctors::find($id);
        $doctor->delete();
        return redirect()->back();
    }

    public function updateDoctor($id){
        $doctor=Doctors::find($id);
        return view('admin.updateDoctorInfo',compact('doctor'));
    }

    public function saveDoctorChanges(Request $req, $id){
        $doctor=Doctors::find($id);
        $doctor->name=$req->name;
        $doctor->phone=$req->phone;
        $doctor->speciality=$req->speciality;
        $doctor->experience=$req->experience;
        $doctor->room=$req->room;
        $doctor->fee=$req->fee;
        $image=$req->image;
        if($image){
            $imgName=time().'.'.$image->getClientOriginalExtension();
            $req->image->move('doctorImages',$imgName);
            $doctor->image=$imgName;
        }
        $doctor->save();
        return redirect()->back()->with('message','Doctor details updated successfully!');
    }

    public function showBloodDonations(){
        $donors=BloodBank::all()->sortBy('date');
        return view('admin.showBloodDonations',compact('donors'));
    }
}
