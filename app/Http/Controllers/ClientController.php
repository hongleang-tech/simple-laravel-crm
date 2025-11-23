<?php

namespace App\Http\Controllers;

use App\Enums\ClientStatus;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Contracts\View\View as ViewView;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $clients = Client::with(['address'])->paginate();

        return view('clients.index', [
            'clients' => $clients,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('clients.create', [
            'statusOptions' => ClientStatus::options(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request): RedirectResponse
    {
        $client = Client::create($request->safe()->only([
            'name',
            'email',
            'phone',
            'company',
            'status',
        ]));

        $client->address()->create($request->safe()->only([
            'address_1',
            'address_2',
            'suburb',
            'postcode',
            'state',
            'country',
        ]));

        $client->save();

        return redirect()->route('clients.show', ['client' => $client])->with('success', 'Client has been created successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client): ViewView
    {
        return view('clients.show', [
            'client' => $client,
            'statusOptions' => ClientStatus::options(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $client->update($request->safe()->only([
            'name',
            'email',
            'phone',
            'company',
            'status',
        ]));

        $client->address()->update($request->safe()->only([
            'address_1',
            'address_2',
            'suburb',
            'postcode',
            'state',
            'country',
        ]));

        return back()->with('success', 'Client has been successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')->with('sucess', 'Client has been successfully deleted.');
    }
}
