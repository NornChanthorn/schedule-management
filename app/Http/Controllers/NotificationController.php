<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function index($id)
    {
        // Retrieve notifications by user ID
        $notifications = Notification::where('user_id', $id)->get();

        return response()->json($notifications);
    }
}

