<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotifications(): JsonResponse
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notification) {
                $order = Order::find($notification->order_id);
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'description' => $notification->description,
                    'read' => $notification->read,
                    'order_number' => $order ? $order->order_number : null,
                    'order_status' => $order ? $order->status : null,
                ];
            });

        return response()->json($notifications);
    }
    public function markAsRead($id): JsonResponse
    {
        $user = Auth::user();

        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if ($notification) {
            $notification->read = true;
            $notification->save();

            return response()->json(['message' => 'Notification marked as read']);
        } else {
            return response()->json(['error' => 'Notification not found or unauthorized'], 404);
        }
    }
}
