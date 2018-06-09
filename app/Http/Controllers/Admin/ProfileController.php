<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DB;
use Response;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    public function index()	
    {	
    	$profile = DB::table('profile')->select('*')->where('id', 1)->first();
        return view('admin.profile')->with('profile', $profile); 
    }

    public function edit(Request $request)
    { 
    	$rules = array(
        'branch_name' => 'required',
        'address' => 'required',
        'contact_number' => 'required|digits_between:7,11',
        'email' => 'required|email',
        'tin' => 'required',
        'vat' => 'required|numeric'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        else
        {
        	$profile = DB::table('profile')->where('id',1)->update([
    		'branch_name' => $request->branch_name,
    		'address' => $request->address,
    		'contact_number' => $request->contact_number,
    		'email' => $request->email,
    		'tin' => $request->tin,
    		'vat' => $request->vat
    		]);
        }
    }
}