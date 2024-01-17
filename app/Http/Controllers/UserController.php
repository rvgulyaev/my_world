<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserSharedResource;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $search = $request->has('search_user_fio') ? $request->input('search_user_fio') : null;
        if ($search !== null) {
            $users = UserResource::collection(User::where('name', 'LIKE', '%'.$search.'%')->get()->sortBy('name'));
        } else {
            $users = UserResource::collection(User::all()->sortBy('name'));
            $search = '';
        }
        return Inertia::render('Users/UsersIndex', [
            'users' => $users,
            'search_user' => $search
        ]);
    }

     /**
     * Display a listing of the soft deleted items.
     */
    public function trashed(Request $request): Response
    {
        $search = $request->has('search_user_fio') ? $request->input('search_user_fio') : null;
        if ($search !== null) {
            $users = UserResource::collection(User::onlyTrashed()->where('name', 'LIKE', '%'.$search.'%')->get()->sortBy('name'));
        } else {
            $users = UserResource::collection(User::onlyTrashed()->get()->sortBy('name'));
            $search = '';
        }
        return Inertia::render('Users/UsersTrashed', [
            'users' => $users,
            'search_user' => $search
        ]);
    }

    public function restore(Request $request) {
        User::onlyTrashed()->where('id', $request->user_id)->restore();
        return to_route('admin.users.trashed');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Users/UsersAdd', [
            'roles' => RoleResource::collection(Role::all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|' . Rule::unique('users', 'name'),
            'username' => 'required|string|max:255|' . Rule::unique('users', 'username'),
            'phone' => 'required',
            'specialist' => 'required',
            'password' => 'required|min:8|confirmed',
            'roles' => ['sometimes', 'array'],
        ]);
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'phone' => $request->phone,
            'specialist' => $request->specialist,
            'password' => Hash::make($request->password)
        ]);
        $user->syncRoles($request->input('roles.*.name'));
        return to_route('admin.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): Response
    {
        $user->load(['roles']);
        return Inertia::render('Users/UsersEdit', [
            'user' => new UserSharedResource($user),
            'roles' => RoleResource::collection(Role::all()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|' . Rule::unique('users')->ignore($user),
            'username' => 'required|string|max:255|' . Rule::unique('users')->ignore($user),
            'phone' => 'required',
            'specialist' => 'required',
            'password' => 'nullable|min:8|confirmed',
            'roles' => ['sometimes', 'array'],
        ]);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'phone' => $request->phone,
            'specialist' => $request->specialist,
        ]);
        if (!empty($request->password)) $user->update(['password' => Hash::make($request->password)]);

        $user->syncRoles($request->input('roles.*.name'));

        return to_route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return to_route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage permanent.
     */
    public function terminate(Request $request)
    {
        User::where('id', $request->user_id)->forceDelete();
        return to_route('admin.users.trashed');
    }

    public function ban(Request $request) {
        $u = $request->user_id;
        User::where('id', $request->user_id)->update(['banned_at' => now()]);
        return to_route('admin.users.index');
    }

    public function unBan(Request $request) {
        User::where('id', $request->user_id)->update(['banned_at' => null]);
        return to_route('admin.users.index');
    }
}
