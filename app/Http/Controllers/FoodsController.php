<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class FoodsController extends Controller
{
    public function create()
    {
        return view('food.create');
    }
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stars' => 'nullable|numeric',
            'type_id' => 'required|exists:food_types,id',
            'img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'lat' => 'nullable|string', // Latitude
            'lon' => 'nullable|string', // Longitude
            'location'=>'nullable|string'
        ]);
    
        // Create a new Food instance
        $food = new Food();
        $food->name = $validated['name'];
        $food->description = $validated['description'] ?? null;
        $food->price = $validated['price'];
        $food->stars = $validated['stars'] ?? 3;
        $food->type_id = $validated['type_id'];
        $food->user_id = Auth::user()->id;
    
        // Store latitude and longitude
        $food->lat = $validated['lat'] ?? null;
        $food->lon = $validated['lon'] ?? null;
        $food->location= $validated['location'] ?? null;
    
        // Handle file upload
        if ($request->hasFile('img')) {
            $food->img = $request->file('img')->store('uploads/images', 'public');
        }
    
        // Save the Food instance
        $food->save();
    
        // Redirect to a specific route after saving
        return redirect()->route('welcome')->with('success', 'Food item added successfully!');
    }
    
    
    public function updateStatus(Request $request, $id)
{
    $food = Food::find($id);
    if ($food) {
        // Toggle the status
        $food->admin_status = !$food->admin_status;
        $food->save();

        return redirect()->back();
    } else {
        return redirect()->back()->with('error', 'Food item not found!');
    }
}
public function updateStatusAdmin(Request $request, $id)
{
    $food = Food::find($id);
    if ($food&&$food->admin_status) {
        
        $food->status = !$food->status;
        $food->save();

        return redirect()->back();
    } else {
        return redirect()->back()->with('message', 'the admin block your annonce');
    }
}
}
