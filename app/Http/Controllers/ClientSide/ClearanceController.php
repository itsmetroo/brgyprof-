<?php

namespace App\Http\Controllers\ClientSide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Certificate_list;
use App\Models\Certificate_request;
use App\Models\resident_account;

class ClearanceController extends Controller
{
    public function index()
    {
        if (!session()->has("resident")) {
            return redirect("/barangay/login");
        }

        $certificate = Certificate_list::all();
        return view('pages.ClientSide.userdashboard.certificate', compact('certificate'));
    }

    public function store(Request $request)
    {
        $resident = resident_account::find($request->resident_id);

        if (!$resident) {
            return redirect()->back()->withErrors([
                'resident_id' => 'Invalid resident session. Please log in again.'
            ])->withInput();
        }

        $validator = Validator::make($request->all(), [
            "resident_id" => "required|exists:resident_accounts,resident_account_id",
            "name" => "required|string|max:255",
            "gender" => "required|in:Male,Female",
            "age" => "required|integer|min:1|max:130",
            "address" => "required|string|max:255",
            "contact" => "required|string|max:20",
            "email" => "required|email|max:255",
            "request_type" => "required|exists:certificate_lists,certificate_type",
            "Description" => "required|string|min:10|max:500",
            "request_date" => "required|date|after_or_equal:today",
            "copies" => "required|integer|min:1|max:5",
            "employment_status" => "required|in:Yes,No",
            "voter_status" => "required|in:Yes,No",
            "terms_agree" => "accepted",
        ], [
            "resident_id.exists" => "Your session has expired. Please log in again.",
            "name.required" => "Please enter your full name.",
            "gender.required" => "Please select your gender.",
            "age.required" => "Please enter your age.",
            "address.required" => "Please enter your address.",
            "contact.required" => "Please enter your contact number.",
            "email.required" => "Please enter your email address.",
            "request_type.required" => "Please select a certificate type.",
            "Description.required" => "Please specify your purpose.",
            "request_date.required" => "Please provide a request date.",
            "copies.required" => "Please choose the number of copies.",
            "employment_status.required" => "Please select your employment status.",
            "voter_status.required" => "Please select your voter status.",
            "terms_agree.accepted" => "You must agree to the terms to proceed."
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $certificate = Certificate_list::where('certificate_type', $request->request_type)->first();

        if (!$certificate) {
            return redirect()->back()
                ->withErrors(['request_type' => 'Selected certificate type is not available.'])
                ->withInput();
        }

        Certificate_request::create([
            'resident_infos' => $request->resident_id,
            'name' => $request->name,
            'gender' => $request->gender,
            'age' => $request->age,
            'address' => $request->address,
            'contact' => $request->contact,
            'email' => $request->email,
            'description' => $request->Description,
            'request_type' => $request->request_type,
            'requested_date' => $request->request_date,
            'number_of_copies' => $request->copies,
            'employment_status' => $request->employment_status,
            'employer' => $request->employer ?? null,
            'voter_status' => $request->voter_status,
            'terms_agree' => true,
            'paid' => 'No',
            'price' => $certificate->price,
            'cert_id' => $certificate->certificate_list_id
        ]);

        return redirect('/barangay/schedule')->with([
            'status_message' => 'REQUEST SUCCESSFULLY SENT! Your certificate request is now being processed.',
            'success_certificate' => true
        ]);
    }
}
