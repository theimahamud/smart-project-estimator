<?php

/**
 * ClientController
 * 
 * Manages client CRUD operations and relationships
 * 
 * @author Rubel Mahamud <rubelmahamud9997@gmail.com>
 * @version 1.0
 * @since 2025-12-16
 */

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ClientController extends Controller implements HasMiddleware
{
    use AuthorizesRequests;

    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function index(): View
    {
        $clients = Client::query()
            ->where('user_id', Auth::id())
            ->withCount(['projects', 'projects as active_projects_count' => function ($query) {
                $query->whereHas('estimates', function ($query) {
                    $query->where('status', 'completed');
                });
            }])
            ->orderBy('name')
            ->paginate(15);

        return view('clients.index', compact('clients'));
    }

    public function create(): View
    {
        return view('clients.create');
    }

    public function store(ClientRequest $request): RedirectResponse
    {
        $client = Client::create([
            'user_id' => Auth::id(),
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'phone' => $request->validated('phone'),
            'company' => $request->validated('company'),
            'address' => $request->validated('address'),
            'notes' => $request->validated('notes'),
        ]);

        return to_route('clients.show', $client)
            ->with('status', 'Client created successfully');
    }

    public function show(Client $client): View
    {
        $this->authorize('view', $client);

        $client->load(['projects.estimates' => function ($query) {
            $query->latest();
        }]);

        return view('clients.show', compact('client'));
    }

    public function edit(Client $client): View
    {
        $this->authorize('update', $client);

        return view('clients.edit', compact('client'));
    }

    public function update(ClientRequest $request, Client $client): RedirectResponse
    {
        $this->authorize('update', $client);

        $client->update([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'phone' => $request->validated('phone'),
            'company' => $request->validated('company'),
            'address' => $request->validated('address'),
            'notes' => $request->validated('notes'),
        ]);

        return to_route('clients.show', $client)
            ->with('status', 'Client updated successfully');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $this->authorize('delete', $client);

        // Check if client has any projects
        if ($client->projects()->count() > 0) {
            return back()->with('error', 'Cannot delete client with existing projects');
        }

        $client->delete();

        return to_route('clients.index')
            ->with('status', 'Client deleted successfully');
    }
}
