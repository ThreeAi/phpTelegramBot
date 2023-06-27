<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TelegramUser;

class UserController extends Controller
{
    public function index() {
        $users = TelegramUser::all();
        return view('users', compact('users'));
    }
}
