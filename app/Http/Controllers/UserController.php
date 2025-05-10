<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of users with pagination and search functionality.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $users = User::query()
            ->when($search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->with('address')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();
        
        return Inertia::render('users/Index', [
            'users' => $users,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return Inertia::render('users/Create');
    }

    /**
     * Store a newly created user in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'address.country' => 'required|string|max:255',
            'address.city' => 'required|string|max:255',
            'address.post_code' => 'required|string|max:20',
            'address.street' => 'required|string|max:255',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $user->address()->create([
            'country' => $validated['address']['country'],
            'city' => $validated['address']['city'],
            'post_code' => $validated['address']['post_code'],
            'street' => $validated['address']['street'],
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $user->load('address');
        
        return Inertia::render('Users/Edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified user in the database.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'address.country' => 'required|string|max:255',
            'address.city' => 'required|string|max:255',
            'address.post_code' => 'required|string|max:20',
            'address.street' => 'required|string|max:255',
        ]);

        $user->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
        ]);

        if ($user->address) {
            $user->address->update([
                'country' => $validated['address']['country'],
                'city' => $validated['address']['city'],
                'post_code' => $validated['address']['post_code'],
                'street' => $validated['address']['street'],
            ]);
        } else {
            $user->address()->create([
                'country' => $validated['address']['country'],
                'city' => $validated['address']['city'],
                'post_code' => $validated['address']['post_code'],
                'street' => $validated['address']['street'],
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
}