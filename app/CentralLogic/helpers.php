<?php

namespace App\CentralLogics;


use Exception;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Helpers
{
    public static function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            array_push($err_keeper, ['code' => $index, 'message' => $error[0]]);
        }
        return $err_keeper;
    }
    public static function sendOrderNotification($order, $token) {
        try {
            $status = $order->order_status;
            $value = self::orderStatusUpdateMessage($status);
    
            if ($value) {
                $data = [
                    'title' => trans('messages.order_push_title'),
                    'description' => $value,
                    'order_id' => $order->id,
                    'image' => '', // Add image URL or path if available
                    'type' => 'order_status',
                ];
    
                // Send push notification to device
                self::sendPushNotifToDevice($token, $data);
    
                try {
                    // Insert notification into the database
                    DB::table('user_notifications')->insert([
                        'data' => json_encode($data),
                        'user_id' => $order->user_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } catch (Exception $e) {
                    // Log exception and return error response
                    info($e);
                    return response()->json(['error' => $e->getMessage()], 403);
                }
    
                return true;
            }
        } catch (Exception $e) {
            // Log exception and return false
            info($e);
            return false;
        }
    }

    //orderStatusUpdateMessage
    
    public static function orderStatusUpdateMessage($status) {
        try {
            // Map status to the corresponding key in the database
            $keyMapping = [
                'pending' => 'order_pending_message',
                'confirmed' => 'order_confirmation_message',
                'processing' => 'order_processing_message',
                'picked_up' => 'out_for_delivery_message',
                'handover' => 'order_handover_message',
                'delivered' => 'order_delivered_message',
                'delivery_boy_delivered' => 'delivery_boy_delivered_message',
                'accepted' => 'delivery_boy_assign_message',
                'canceled' => 'order_canceled_message',
                'refunded' => 'order_refunded_message',
            ];
    
            // Get the key based on the status
            $key = $keyMapping[$status] ?? null;
    
            if ($key) {
                // Query the database to get the message for the given key
                $data = DB::table('business_settings')
                    ->where('key', $key)
                    ->first();
    
                // Return the message if found
                return $data ? $data->value : 'No message available';
            } else {
                return 'Invalid status';
            }
    
        } catch (Exception $e) {
            // Handle exceptions
            info($e);
            return 'Error retrieving message';
        }
    }

    //sendPushNotifToDevice
    public static function sendPushNotifToDevice($fcmToken, $data) {
        try {
            // Get the Firebase Cloud Messaging server key
            $key = DB::table('business_settings')
                ->where('key', 'push_notification_key')
                ->first();
            
            $serverKey = $key ? $key->value : null;
            if (!$serverKey) {
                throw new Exception('Server key not found.');
            }
    
            // FCM endpoint
            $url = "https://fcm.googleapis.com/fcm/send";
    
            // Set up the headers
            $headers = [
                "Authorization: key={$serverKey}",
                "Content-Type: application/json"
            ];
    
            // Prepare the payload
            $payload = [
                "to" => $fcmToken,
                "mutable_content" => true,
                "data" => [
                    "title" => $data['title'],
                    "body" => $data['description'],
                    "order_id" => $data['order_id'],
                    "type" => $data['type']
                ],
                "notification" => [
                    "title" => $data['title'],
                    "body" => $data['description'],
                    "order_id" => $data['order_id'],
                    "title_loc_key" => $data['order_id'],
                    "body_loc_key" => $data['type'],
                    "type" => $data['type'],
                    "is_read" => 0,
                    "icon" => "new",
                    "android_channel_id" => "dbfood"
                ]
            ];
    
            $postData = json_encode($payload);
    
            // Initialize cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
            // Execute cURL request
            $result = curl_exec($ch);
    
            // Check for cURL errors
            if ($result === false) {
                throw new Exception(curl_error($ch));
            }
    
            // Close cURL
            curl_close($ch);
    
            return $result;
        } catch (Exception $e) {
            // Log or handle the exception
            info($e->getMessage());
            return response()->json(['error' => 'Failed to send notification.'], 500);
        }
    }
    
    
    
}