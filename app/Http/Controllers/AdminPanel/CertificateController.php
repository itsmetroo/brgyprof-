<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Certificate_layout;
use App\Models\Certificate_request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Certificate_list;
use App\Models\brgy_official;
use App\Models\resident_info;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        if (!session()->has("user") && !session()->has("resident")) {
            return redirect("login");
        }
        
        $brgy_official = brgy_official::where('position','!=','Punong Barangay')->get();
        $puno = brgy_official::where('position','=','Punong Barangay')->get();
        $content = Certificate_list::get();
        $layout = Certificate_layout::first();

        if ($request->ajax()) {
            $query = Certificate_request::with(['resident', 'certificateType']);
            
            if (session()->has("resident")) {
                $resident_id = session("resident.id");
                $query->where('resident_id', $resident_id);
            }
            
            $data = $query->latest()->get();

            return DataTables::of($data)
                ->addColumn('action', function($row) {
                    if (session()->has("resident")) {
                        return '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->request_id.'" data-original-title="View" class="btn btn-primary btn-sm viewrequest"><i class="fa fa-eye"></i> View</a>';
                    } else {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->request_id.'" data-original-title="Edit" class="btn btn-info btn-sm editrequest"><i class="fa fa-pencil"></i> Edit</a>';
                        $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->request_id.'" data-original-title="View" class="btn btn-primary btn-sm viewrequest"><i class="fa fa-eye"></i> View</a>';
                        return $btn;
                    }
                })
                ->addColumn('request_type', function($row) {
                    return $row->request_type;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.AdminPanel.certificate', [
            'brgy_official2' => $brgy_official,
            'brgy_official' => $brgy_official,
            'puno' => $puno,
            'puno2' => $puno,
            'approve' => $puno,
            'approve2' => $puno,
            'content' => $content,
            'layout' => $layout
        ]);
    }

    public function storerequest(Request $request)
    {
        // Check if resident is logged in
        if (!session()->has("resident")) {
            return response()->json([
                'status' => 0, 
                'error' => 'Invalid resident session. Please log in again.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'cert_id' => 'required|exists:certificate_lists,certificate_list_id',
            'description' => 'required',
            'request_type' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()]);
        }

        try {
            // Get resident info from session
            $resident_id = session('resident.id');
            $resident = resident_info::findOrFail($resident_id);
            
            // Create certificate request
            $certRequest = Certificate_request::create([
                'resident_id' => $resident_id,
                'name' => $resident->firstname . ' ' . $resident->lastname,
                'description' => $request->description,
                'age' => $resident->age,
                'gender' => $resident->gender,
                'cert_id' => $request->cert_id,
                'request_type' => $request->request_type
            ]);

            return response()->json([
                'status' => 1, 
                'success' => 'Certificate request submitted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'error' => 'Error creating certificate request. Please try again.'
            ]);
        }
    }

    public function deleterequest($request_id)
    {
        Certificate_request::find($request_id)->delete();
        return response()->json(["success" => "Certificate request deleted successfully"]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'province' => 'Required',
            'municipality' => 'Required',
            'office' => 'Required',
            'barangay' => 'Required',
            'punongbarangay' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:500||dimensions:max_width=500,max_height=500',
            'logo1' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:500||dimensions:max_width=500,max_height=500',
            'logo2' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:500||dimensions:max_width=500,max_height=500',
        ]);

        $deletefile = DB::table('certificate_layouts')
        ->where('layout_id','=',$request->certificate_id)
        ->first();
        if ($deletefile !== null) {
            $deletefile = DB::table('certificate_layouts')
        ->where('layout_id','=',$request->certificate_id)
        ->first();
        Storage::delete($deletefile->logo_1);
        Storage::delete($deletefile->logo_2);
        Storage::delete($deletefile->punongbarangay);
         }

        $path1 = $request->file('logo1')->store('public/images');
        $path2 = $request->file('logo2')->store('public/images');
        $path3 = $request->file('punongbarangay')->store('public/images');
        Certificate_layout::updateOrCreate(['layout_id' => $request->certificate_id],
        ['logo_1' => $path1,'logo_2' => $path2 ,'punongbarangay' => $path3,'province'=>$request->province,'municipality'=>$request->municipality,'barangay'=>$request->barangay,'office'=>$request->office]);

        return response()->json(['Success'=>'Data saved successfully']);
    }

    public function edit($request_id){
        $cert = Certificate_request::find($request_id);
        return response()->json($cert);
    }

    public function certificate_type(Request $request){
        $certrequest = DB::table('certificate_lists')->orderBy('certificate_list_id')->get();
        if ($request->ajax()) {
            $data = DB::table('certificate_lists')->orderBy('certificate_list_id')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->certificate_list_id.'" data-original-title="Edit" class="edit btn btn-info  btn-xs pr-4 pl-4 edittype"><i class="fa fa-pencil fa-lg"></i> </a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"   data-id="'.$row->certificate_list_id.'" data-original-title="Delete" class="btn btn-danger btn-xs pr-4 pl-4 deletetype"><i class="fa fa-trash fa-lg"></i> </a>';
                         return $btn;
                 })
                   ->rawColumns(['action'])
                    ->make(true);
        }
    }

    public function certtypesubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'certificate_list_id' => '',
            'content_1' => 'Required',
            'content_2' => 'Required',
            'content_3' => 'Required',
            'certificate_name'  => 'Required',
            'certificate_type' => 'Required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>$validator->errors()]);
        } else {
            Certificate_list::updateOrCreate(['certificate_list_id' => $request->certificate_list_id],
            ['content_1'=>$request->content_1,'content_2'=>$request->content_2,'content_3'=>$request->content_3,'certificate_type'=>$request->certificate_type,'certificate_name'=>$request->certificate_name]);

            return response()->json(['status'=>1,'Success'=>'Data saved successfully']);
        }
    }

    public function certtypeedit($request_id){
        $cert = Certificate_list::find($request_id);
        return response()->json($cert);
    }

    public function certtypedelete($request_id){
        $cert = Certificate_list::find($request_id)->delete();
        return response()->json(["success"=>"Data saved successfully"]);
    }

    public function show($id)
    {
        try {
            $certificate = Certificate_request::with(['resident', 'certificateType'])
                ->findOrFail($id);

            // Check if user has permission to view this certificate
            if (session()->has('resident')) {
                if ($certificate->resident_id != session('resident.id')) {
                    return redirect()->back()->with('error', 'You do not have permission to view this certificate');
                }
            }

            $brgy_official = brgy_official::where('position','!=','Punong Barangay')->get();
            $puno = brgy_official::where('position','=','Punong Barangay')->get();
            $layout = Certificate_layout::first();

            return view('pages.AdminPanel.certificate-view', [
                'certificate' => $certificate,
                'brgy_official' => $brgy_official,
                'puno' => $puno,
                'layout' => $layout
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Certificate not found');
        }
    }
}

