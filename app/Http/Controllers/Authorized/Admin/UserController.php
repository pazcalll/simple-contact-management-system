<?php

namespace App\Http\Controllers\Authorized\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\StoreUserRequest;
use App\Http\Requests\Admins\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService = new UserService(),
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = User::latest()->with('roles')->paginate();

        if (request()->header('X-Request-Format') == 'json') {
            return new JsonResponse($users);
        }

        return Inertia::render('authorized/admin/Users', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $roles = Role::with('upline')->get();
        return Inertia::render('authorized/admin/AddUser', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        unset($validated['password_confirmation']);

        $role = Role::find($validated['role_id']);

        $user = User::create($validated);
        $user->assignRole($role);

        return redirect()->route('admins.users.index')->with('success', 'User data has been created');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
        $upline = $user->upline;
        $possibleUplines = User::query()->role($upline->roles()->pluck('name')[0])->get();
        $roles = Role::get();
        return Inertia::render('authorized/admin/UserDetails', [
            'user' => $user->load('roles'),
            'upline' => $upline,
            'roles' => $roles,
            'possibleUplines' => $possibleUplines,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        //
        try {
            $validated = $request->validated();
    
            if ($validated['password'] !== null || $validated['password'] !== '') {
                $validated['password'] = bcrypt($validated['password']);
                unset($validated['password_confirmation']);
            } else {
                unset($validated['password']);
                unset($validated['password_confirmation']);
            }
    
            $role = Role::find($validated['role_id']);
    
            $user = User::where('id', $id)->firstOrFail();
            if (!$user) {
                return redirect()->back()->with('error', 'User not found');
            }
    
            unset($validated['role_id']);
            $user->update($validated);
            $user->assignRole($role);
    
            return redirect()->route('admins.users.index')->with('success', 'User data has been updated');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to update user data');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getUsersByRole(Role $role)
    {
        $users = User::role($role->name)->get();
        return response()->json(['data' => $users]);
    }

    public function getUsersByUpline(User $upline)
    {
        $users = User::where('upline_id', $upline->id)->get();
        return response()->json(['data'=> $users]);
    }
}
