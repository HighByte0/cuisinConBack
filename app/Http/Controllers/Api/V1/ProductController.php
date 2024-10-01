<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;

class ProductController extends Controller
{
        
    public function get_popular_products(Request $request)
    {
        $list = Food::where('type_id', 2)
                    ->where('status', 1) // Check if status is 1
                    ->take(10)
                    ->with('user')
                    ->orderby('created_at', 'DESC')
                    ->get();
        
        foreach ($list as $item) {
            $item['description'] = strip_tags($item['description']);
            $item['description'] = preg_replace("/&#?[a-z0-9]+;/i", " ", $item['description']); 
            unset($item['selected_people']);
            unset($item['people']);
        }
        
        $data = [
            'total_size' => $list->count(),
            'type_id' => 2,
            'offset' => 0,
            'products' => $list
        ];
        
        return response()->json($data, 200);
    }
    
    public function get_recommended_products(Request $request)
    {
        // Fetch recommended products with the related user
        $list = Food::where('type_id', 3)
                    ->where('status', 1) // Check if status is 1
                    ->take(10)
                    ->with('user') // Eager load the user relationship
                    ->orderBy('created_at', 'DESC')
                    ->get();
        
        foreach ($list as $item) {
            // Clean up description
            $item['description'] = strip_tags($item['description']);
            $item['description'] = preg_replace("/&#?[a-z0-9]+;/i", " ", $item['description']); 
            
            // Remove unwanted fields
            unset($item['selected_people']);
            unset($item['people']);
            
            // Add user info to each item
         
        }
        
        $data = [
            'total_size' => $list->count(),
            'type_id' => 3,
            'offset' => 0,
            'products' => $list
        ];
        
        return response()->json($data, 200);
    }   
    
    
    public function test_get_recommended_products(Request $request)
    {
        $list = Food::where('status', 1) 
        ->with('user')
                    ->skip(5)
                    ->take(2)
                    ->get();
      
        foreach ($list as $item) {
            $item['description'] = strip_tags($item['description']);
            $item['description'] = preg_replace("/&#?[a-z0-9]+;/i", " ", $item['description']); 
        }
        
        $data = [
            'total_size' => $list->count(),
            'limit' => 5,
            'offset' => 0,
            'products' => $list
        ];
        
        return response()->json($data, 200);
    }
    public function nearBy(Request $request)
    {
        $list = Food::where('status', '>',0) 
        ->with('user')
                 
                    ->take(100)
                    ->get();
      
        foreach ($list as $item) {
            $item['description'] = strip_tags($item['description']);
            $item['description'] = preg_replace("/&#?[a-z0-9]+;/i", " ", $item['description']); 
        }
        
        $data = [
            'total_size' => $list->count(),
            'limit' => 5,
            'offset' => 0,
            'products' => $list
        ];
        
        return response()->json($data, 200);
    }
    
    

}
