<?php

namespace App\Http\Controllers;

use App\Models\Doctors;
use App\Models\BloodBank;
use App\Models\Appointments;
use App\Models\TestAppointment;
use App\Models\Tests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function redirect(){
        if(Auth::id()){
            if(Auth::user()->usertype=='0'){
                $doctors= Doctors::all();
                $tests=Tests::all();
                return view('user.home',compact(['doctors','tests']));
            }else{
                return view('admin.home');
            }
        }else{
            return redirect()->back();
        }
    }
    public function index(){
        if(Auth::id()){
            return redirect('home');
        }else{
            $doctors= Doctors::all();
            $tests=Tests::all();
            return view('user.home',compact(['doctors','tests']));
        }
    }
    public function donateBlood(Request $req){
        $info= new BloodBank();
        $info->name=$req->name;
        $info->email=$req->email;
        $info->phone=$req->number;
        $info->date=$req->date;
        $info->bloodgroup=$req->bloodgroup;
        if(Auth::id()){
            $info->userID=Auth::user()->id;
        }
        $info->save();
        return redirect()->back()->with('message','Donation booking submitted!');
    }
    public function requestAppointment(Request $req){
        $info= new Appointments();
        $info->name=$req->name;
        $info->email=$req->email;
        $info->phone=$req->number;
        $info->date=$req->date;
        $info->doctor=$req->doctor;
        $info->userID=Auth::user()->id;
        $info->speciality=(Doctors::firstWhere('name',$req->doctor))->speciality;
        $info->fee=(Doctors::firstWhere('name',$req->doctor))->fee;
        $info->save();
        return redirect()->back()->with('message','Appointment request submitted!');
    }
    public function requestTestAppointment(Request $req){
        $info= new TestAppointment();
        $info->patientName=$req->name;
        $info->email=$req->email;
        $info->phone=$req->number;
        $info->date=$req->date;
        $info->testName=$req->test;
        $info->userID=Auth::user()->id;
        $info->fee=(Tests::firstWhere('name',$req->test))->fee;
        $info->save();
        return redirect()->back()->with('message','Appointment request submitted!');
    }
    public function myDashboard(){
        if(Auth::id()){
            $userid=Auth::user()->id;
            $bloodDonations=BloodBank::where('userID',$userid)->orderBy('date')->get();
            $appointments=Appointments::where('userID',$userid)->orderBy('date')->get();
            $testAppointments=TestAppointment::where('userID',$userid)->orderBy('date')->get();
            return view("user.mydashboard",compact(['bloodDonations','appointments','testAppointments']));
        }else{
            return redirect()->back();
        }
    }
    public function cancelDonation($id){
        $donorData= BloodBank::find($id);
        $donorData->delete();
        return redirect()->back();
    }
    public function cancelAppointment($id){
        $appointment= Appointments::find($id);
        $appointment->delete();
        return redirect()->back();
    }
    public function deleteTestReq($id){
        $info= TestAppointment::find($id);
        $info->delete();
        return redirect()->back();
    }
}
