<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TelegramUser;

class UserController extends Controller
{
    public function index() {
        $users = TelegramUser::all();
        return view('admin.users.users', compact('users'));
    }
}
