<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $role = $request->input('role');

        // Query pengguna dengan filter
        $users = User::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($role, function ($query, $role) {
                return $query->where('role', $role);
            })
            ->paginate(10);

        // Semua role untuk dropdown filter
        $roles = ['admin', 'supervisor', 'direktur'];

        return view('user.index', compact('users', 'roles'));
    }

}
