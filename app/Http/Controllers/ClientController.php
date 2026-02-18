<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function __construct(
        private ClientService $clientService
    ) {}

    /**
     * Display a listing of clients.
     *
     * @return View
     */
    public function index(): View
    {
        $clients = Client::orderBy('created_at', 'desc')->get();
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     *
     * @return View
     */
    public function create(): View
    {
        return view('clients.create');
    }

    /**
     * Store a newly created client.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'business_category' => 'required|string|max:255',
            'brand_logo' => 'nullable|image|max:2048',
            'services' => 'required|json',
            'business_info' => 'required|json',
            'ai_config' => 'nullable|json',
        ]);

        $servicesData = json_decode($validated['services'], true);
        $businessInfo = json_decode($validated['business_info'], true);
        $aiConfig = isset($validated['ai_config']) ? json_decode($validated['ai_config'], true) : [];
        
        $this->clientService->createClient([
            'name' => $validated['name'],
            'business_category' => $validated['business_category'],
            'services' => $servicesData,
            'business_info' => $businessInfo,
            'ai_config' => $aiConfig,
        ], $request->file('brand_logo'));

        return redirect()->route('clients.index')
            ->with('success', 'Client created successfully.');
    }

    /**
     * Show the form for editing the specified client.
     *
     * @param string $id
     * @return View
     */
    public function edit(string $id): View
    {
        $client = Client::findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified client.
     *
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'business_category' => 'required|string|max:255',
            'brand_logo' => 'nullable|image|max:2048',
            'services' => 'required|json',
            'business_info' => 'required|json',
            'ai_config' => 'nullable|json',
        ]);

        $servicesData = json_decode($validated['services'], true);
        $businessInfo = json_decode($validated['business_info'], true);
        $aiConfig = isset($validated['ai_config']) ? json_decode($validated['ai_config'], true) : [];

        $this->clientService->updateClient($id, [
            'name' => $validated['name'],
            'business_category' => $validated['business_category'],
            'services' => $servicesData,
            'business_info' => $businessInfo,
            'ai_config' => $aiConfig,
        ], $request->file('brand_logo'));

        return redirect()->route('clients.index')
            ->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified client.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $this->clientService->deleteClient($id);

        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully.');
    }
}
