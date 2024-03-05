<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve notifications by user ID
        $notifications = Notification::where('user_id', $request)->get();

        return response()->json($notifications);
    }
}

