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
            'service_images.*' => 'nullable|image|max:2048',
            'business_info' => 'required|json',
            'ai_config' => 'nullable|json',
            'fb_page_id' => 'nullable|string|unique:mongodb.clients,fb_page_id',
            'insta_account_id' => 'nullable|string|unique:mongodb.clients,insta_account_id',
        ]);

        $servicesData = json_decode($validated['services'], true);
        if (is_array($servicesData)) {
            foreach ($servicesData as &$service) {
                unset($service['imagePreview']);
            }
        }
        $businessInfo = json_decode($validated['business_info'], true);
        $aiConfig = isset($validated['ai_config']) ? json_decode($validated['ai_config'], true) : [];
        $serviceImages = $request->file('service_images', []);
        
        $this->clientService->createClient([
            'name' => $validated['name'],
            'business_category' => $validated['business_category'],
            'services' => $servicesData,
            'business_info' => $businessInfo,
            'ai_config' => $aiConfig,
            'fb_page_id' => $validated['fb_page_id'] ?? null,
            'insta_account_id' => $validated['insta_account_id'] ?? null,
        ], $request->file('brand_logo'), $serviceImages);

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
            'service_images.*' => 'nullable|image|max:2048',
            'business_info' => 'required|json',
            'ai_config' => 'nullable|json',
            'fb_page_id' => 'nullable|string|unique:mongodb.clients,fb_page_id,' . $id . ',_id',
            'insta_account_id' => 'nullable|string|unique:mongodb.clients,insta_account_id,' . $id . ',_id',
        ]);

        $servicesData = json_decode($validated['services'], true);
        if (is_array($servicesData)) {
            foreach ($servicesData as &$service) {
                unset($service['imagePreview']);
            }
        }
        $businessInfo = json_decode($validated['business_info'], true);
        $aiConfig = isset($validated['ai_config']) ? json_decode($validated['ai_config'], true) : [];
        $serviceImages = $request->file('service_images', []);

        $this->clientService->updateClient($id, [
            'name' => $validated['name'],
            'business_category' => $validated['business_category'],
            'services' => $servicesData,
            'business_info' => $businessInfo,
            'ai_config' => $aiConfig,
            'fb_page_id' => $validated['fb_page_id'] ?? null,
            'insta_account_id' => $validated['insta_account_id'] ?? null,
        ], $request->file('brand_logo'), $serviceImages);

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
