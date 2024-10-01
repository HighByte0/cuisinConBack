<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Food;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Mail\OrderStatusChanged;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
        public function place_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_amount' => 'required',
            'address' => 'required_if:order_type,delivery',
            //'longitude' => 'required_if:order_type,delivery',
           // 'latitude' => 'required_if:order_type,delivery',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $address = [
            'contact_person_name' => $request->contact_person_name?$request->contact_person_name:$request->user()->f_name.' '.$request->user()->f_name,
            'contact_person_number' => $request->contact_person_number?$request->contact_person_number:$request->user()->phone,
            'address' => $request->address,
            'longitude' => (string)$request->longitude,
            'latitude' => (string)$request->latitude,
        ];

        $product_price = 0;

        $order = new Order();
        $order->id = 100000 + Order::all()->count() + 1; //checked
        $order->user_id = $request->user()->id; //checked 
        $order->order_amount = $request['order_amount']; //checked 
        $order->order_note = $request['order_note']; //checked
        $order->delivery_address = json_encode($address); //checked
        $order->otp = rand(1000, 9999); //checked
        $order->pending = now(); //checked
        $order->created_at = now(); //checked
        $order->updated_at = now();//checked
        $order->payment_status = $request->payment_method == 'cash_on_delivery' ? 'pending' : 'confirmed';
        $order->payment_method=$request->payment_method;
        
        foreach ($request['cart'] as $c) {
     
                $product = Food::find($c['id']); //checked
                if ($product) {
            
                    $price = $product['price']; //checked 
                        
                    $or_d = [
                        'food_id' => $c['id'], //checked
                        'food_details' => json_encode($product), 
                        'quantity' => $c['quantity'], //checked
                        'price' => $price, //checked
                        'created_at' => now(), //checked
                        'updated_at' => now(), //checked 
                        'tax_amount' => 10.0
                    ];
                    
                    $product_price += $price*$or_d['quantity'];
                    $order_details[] = $or_d;
                } else {
                    return response()->json([
                        'errors' => [
                            ['code' => 'food', 'message' => 'not found!']
                        ]
                    ], 401);
                }
        }


        try {
            $save_order= $order->id;
            $total_price= $product_price;
            $order->order_amount = $total_price;
            $order->save();
            
            foreach ($order_details as $key => $item) {
                $order_details[$key]['order_id'] = $order->id;
            }
            /*
            insert method takes array of arrays and insert each array in the database as a record.
            insert method is part of query builder
            */
            OrderDetail::insert($order_details);

            Helpers::sendOrderNotification($order,$request->user()->m_firebase_token);

            return response()->json([
                'message' => trans('messages.order_placed_successfully'),
                'order_id' =>  $save_order,
                'total_ammount' => $total_price,
                
            ], 200);
        } catch (\Exception $e) {
            return response()->json([$e], 403);
        }

        return response()->json([
            'errors' => [
                ['code' => 'order_time', 'message' => trans('messages.failed_to_place_order')]
            ]
        ], 403);
    }

    public function get_order_list(Request $request)
    {
        $orders = Order::withCount('details')->where(['user_id' => $request->user()->id])->get()->map(function ($data) {
            $data['delivery_address'] = $data['delivery_address']?json_decode($data['delivery_address']):$data['delivery_address'];   

            return $data;
        });
        return response()->json($orders, 200);
    }

    
    public function show()
    {
        return view('cartBancaire', [
            'customerId' => request('customer_id'),
            'orderId' => request('order_id')
        ]);
    }
    

        
    public function orderController($foodUserId)
    {
        // Fetch order details along with the relevant information and delivery address
        $orderDetails = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('foods', 'order_details.food_id', '=', 'foods.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select(
                'order_details.quantity',
                'orders.id as order_id',    
                'orders.order_status',
                'orders.order_note',
                'orders.payment_status',
                'users.f_name',
                'orders.delivery_address' ,
                'order_details.food_details'//this when lon,lan exist
                
            )
            ->where('foods.user_id', $foodUserId)
            ->get();
    
        // Decode delivery_address JSON field
        foreach ($orderDetails as $order) {
            $order->delivery_address = json_decode($order->delivery_address);
        }
    
        // Return the data to the view
        return view('orders.details', compact('orderDetails'));
    }
    

    
    
        
    
        public function updateOrderStatus(Request $request, $orderId)
        {
              // Validate the request
    $validated = $request->validate([
        'order_status' => 'required|string|in:pending,accepted,processing,handover,picked_up',
    ]);

    // Find the order
    $order = Order::find($orderId);

    if (!$order) {
        return redirect()->back()->with('error', 'Order not found.');
    }

    // Update the order status
    $order->order_status = $validated['order_status'];
    $order->save();

    // Send email if status is processing or ready for handover
    if (in_array($order->order_status, ['processing', 'handover','accepted'])) {
        // Fetch customer email
        $customerEmail = $order->user->email; // Assuming you have a relationship set up

        // Send email
        Mail::to($customerEmail)->send(new OrderStatusChanged($order));
    }

    return redirect()->back()->with('success', 'Order status updated successfully!');

        }



        public function menu($id)
        {
            // Retrieve a specific food item by ID
         $foods=Food::where('user_id',$id)->get(); // Replace 5 with the actual ID or criteria you need
        
            if ($foods) {
                return view('food.show', compact('foods'));
            } else {
                // Handle the case where the food item is not found
                return redirect()->route('welcome')->with('error', 'Food item not found.');
            }
        }

public function destroy($id){
    Food::destroy($id);
    return redirect()->back();


}


        
}
