<?php
namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller{
    
     public function address_list(Request $request)
    {
        return response()->json(CustomerAddress::where('user_id', $request->user()->id)->latest()->get(), 200);
    }

    public function info(Request $request)
    {
        $data = $request->user();
        
        $data['order_count'] =0;//(integer)$request->user()->orders->count();
        $data['member_since_days'] =(integer)$request->user()->created_at->diffInDays();
        //unset($data['orders']);
        return response()->json($data, 200);
    }

    


        public function add_new_address(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact_person_name' => 'required',
            'contact_person_number' => 'required',
            'address' => 'required',
          
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => "Error with the address"], 403);
        }


        $address = [
            'user_id' => $request->user()->id,
            'contact_person_name' => $request->contact_person_name,
            'contact_person_number' => $request->contact_person_number,
            'address' => $request->address,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'address_type'=>$request->address_type,
            'created_at' => now(),
            'updated_at' => now()
        ];
        DB::table('customer_addresses')->insert($address);
        return response()->json(['message' => trans('messages.successfully_added')], 200);
    }
        public function update_address(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'contact_person_name' => 'required',
            'address_type' => 'required',
            'contact_person_number' => 'required',
            'address' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        /*$point = new Point($request->latitude,$request->latitude);
        $zone = Zone::contains('coordinates', $point)->first();
        if(!$zone)
        {
            $errors = [];
            array_push($errors, ['code' => 'coordinates', 'message' => trans('messages.out_of_coverage')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }*/
        $address = [
            'user_id' => $request->user()->id,
            'contact_person_name' => $request->contact_person_name,
            'contact_person_number' => $request->contact_person_number,
            'address_type' => $request->address_type, 
            'address' => $request->address,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'zone_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ];
        DB::table('customer_addresses')->where('user_id', $request->user()->id)->update($address);
        return response()->json(['message' => trans('messages.updated_successfully')], 200);
    }

public function update_cm_firebase_token(Request $request)
{
    // Validate the request
    $validator = Validator::make($request->all(), [
        'cm_firebase_token' => 'required|string',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors(),
        ], 403);
    }

    // Update the Firebase token in the database
    DB::table('users')
        ->where('id', $request->user()->id)
        ->update([
            'cm_firebase_token' => $request->input('m_firebase_token'),
        ]);

    // Return success response
    return response()->json([
        'message' => trans('messages.updated_successfully'),
    ], 200);
}
public function storeReport(Request $request){
    $report = new Report();
    $report->prod_id = $request->input('prod_id');
    $report->reason = $request->input('reason');
    $report->user_id = $request->input('user_id');
    $report->save();
    $food=food::find($request->input('prod_id'));
    if($food){
        $food->count=$food->count+1;
        $food->save();
    }
    

    return response()->json(['message' => 'Signalement reçu.'], 200);

}
}