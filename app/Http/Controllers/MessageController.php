<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\User;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function user_list()
    {
        $users = User::latest()->where('id', '!=', auth()->user()->id)->get();

        if(\Request::ajax()) {
            return response()->json($users, 200);
        }

        return abort(404);

    }

    public function user_message($id=null)
    {
        if(!\Request::ajax()) {
            return abort(404);
        }
        
        $user = User::findOrFail($id);

        $messages = Message::where(function ($q) use ($id) {
            $q->where('from', auth()->user()->id);
            $q->where('to', $id);
            $q->where('type', 0);
        })->orWhere(function($q) use ($id) {
            $q->where('from', $id);
            $q->where('to', auth()->user()->id);
            $q->where('type', 1);
        })->with('user')->get();

        return response()->json([
            'messages' => $messages,
            'user' => $user,
        ]);
    }

    public function send_message(Request $request)
    {
        if(!$request->ajax()) {
            abort(404);
        }

        $messages = Message::create([
            'message' => $request->message,
            'from' =>  auth()->user()->id,
            'to' => $request->user_id,
            'type' => 0
        ]);
        $messages = Message::create([
            'message' => $request->message,
            'from' =>  auth()->user()->id,
            'to' => $request->user_id,
            'type' => 1
        ]);

        return response()->json($messages, 201);
    }
}
