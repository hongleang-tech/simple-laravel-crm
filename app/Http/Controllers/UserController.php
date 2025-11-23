<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserListResource;
use App\Http\Resources\UserResource;
use App\Models\Address;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ResourceCollection
    {
        $users = User::with(['address', 'roles'])->paginate();

        return UserListResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): UserResource
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                ...$request->safe([
                    'first_name',
                    'last_name',
                    'email',
                    'phone_number',
                ]),
                'password' => Hash::make(Str::random(10)),
            ]);

            $user->address()->create($request->safe([
                'address_1',
                'address_2',
                'suburb',
                'postcode',
                'state',
                'country',
            ]));

            DB::commit();

            $user->load(['address']);

            return new UserResource($user);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): UserResource
    {
        Gate::authorize('view', $user);

        $user->load(['address']);

        return new UserResource($user);
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
            'phone_number',
        ]));

        $address = $user->address ?: new Address;

        $address->fill($request->safe()->only([
            'address_1',
            'address_2',
            'suburb',
            'postcode',
            'state',
            'country',
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
