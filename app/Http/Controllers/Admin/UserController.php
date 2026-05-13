<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // gán role admin (tạo nếu chưa có)
        $role = Role::firstOrCreate(['name' => 'admin']);
        $user->assignRole($role);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Đã tạo Admin mới thành công.');
    }

    
    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'id');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    
    public function update(Request $request, User $user)
    {
        $request->validate([
            'roles'   => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $user->syncRoles(collect($request->input('roles', []))->filter()->values()->all());

        return redirect()->route('admin.users.index')->with('status', 'Cập nhật vai trò thành công');
    }

    
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('status', 'Bạn không thể tự xoá chính mình.');
        }

        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            return back()->with('status', 'Không thể xoá admin cuối cùng.');
        }

        $user->delete();

        return back()->with('status', 'Đã xoá người dùng.');
    }
}
