<?php

namespace App\Http\Controllers;

use App\Models\NotificationSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationSettingController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = Auth::user();

        $notificationSettings = NotificationSetting::where('user_id', $user->id)->first();

        if (!$notificationSettings) {
            $notificationSettings = new NotificationSetting([
                'user_id' => $user->id,
                'sales' => 0,
                'new_arrivals' => 0,
                'delivery_status_changes' => 0,
            ]);
            $notificationSettings->save();
        }

        return response()->json($notificationSettings);
    }
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'sales' => 'boolean',
            'new_arrivals' => 'boolean',
            'delivery_status_changes' => 'boolean',
        ]);

        $settings = Auth::user()->notificationSetting;
        $settings->update($request->only('sales', 'new_arrivals', 'delivery_status_changes'));

        return response()->json(['message' => 'Notification settings updated successfully']);
    }
}
