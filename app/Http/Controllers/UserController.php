<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Address;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = User::with(['address', 'roles'])->paginate();

        return view('users.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = new User($request->safe()->only([
            'first_name',
            'last_name',
            'email',
            'phone_number'
        ]));

        $user->password = Hash::make(Str::random(10));

        $user->save();

        $user->address()->create($request->safe()->only([
            'address_1',
            'address_2',
            'suburb',
            'postcode',
            'state',
            'country'
        ]));

        return redirect()->route('users.show', ['user' => $user])->with('success', 'User has been created successfully updated.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        Gate::authorize('view', $user);

        return view('users.show', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->fill($request->safe()->only([
            'first_name',
            'last_name',
            'email',
            'phone_number'
        ]));

        $address = $user->address ?: new Address();

        $address->fill($request->safe()->only([
            'address_1',
            'address_2',
            'suburb',
            'postcode',
            'state',
            'country'
        ]));

        $address->save();

        if ($address->wasRecentlyCreated) {
            $user->associate($address);
        }

        $user->save();

        return back()->with('success', 'User has been successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        abort_if($user->id === auth()->user()->id, 500, "Can't delete your own account from this screen.");

        $user->delete();

        return redirect(route('users.index'))->with('success', 'User has been deleted successfully.');
    }
}
