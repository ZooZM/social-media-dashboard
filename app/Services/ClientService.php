<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ClientService
{
    /**
     * Create a new client.
     *
     * @param array $data
     * @param UploadedFile|null $image
     * @return Client
     */
    public function createClient(array $data, ?UploadedFile $logo = null, array $serviceImages = []): Client
    {
        if ($logo) {
            $data['brand_logo'] = $this->uploadBrandLogo($logo);
        }

        // Handle service images if provided
        if (!empty($serviceImages) && isset($data['services'])) {
            $data['services'] = $this->processServiceImages($data['services'], $serviceImages);
        }

        return Client::create($data);
    }

    /**
     * Update an existing client.
     *
     * @param string $id
     * @param array $data
     * @param UploadedFile|null $image
     * @return Client
     */
    public function updateClient(string $id, array $data, ?UploadedFile $logo = null, array $serviceImages = []): Client
    {
        $client = Client::findOrFail($id);

        if ($logo) {
            // Delete old logo if exists
            if ($client->brand_logo) {
                Storage::disk('public')->delete($client->brand_logo);
            }
            $data['brand_logo'] = $this->uploadBrandLogo($logo);
        }

        // Handle service images if provided
        if (!empty($serviceImages) && isset($data['services'])) {
            $data['services'] = $this->processServiceImages($data['services'], $serviceImages);
        }

        $client->update($data);
        return $client;
    }

    /**
     * Delete a client.
     *
     * @param string $id
     * @return bool
     */
    public function deleteClient(string $id): bool
    {
        $client = Client::findOrFail($id);

        // Delete brand logo if exists
        if ($client->brand_logo) {
            Storage::disk('public')->delete($client->brand_logo);
        }

        // Delete service images if exist
        if ($client->services) {
            foreach ($client->services as $service) {
                if (isset($service['image']) && $service['image']) {
                    Storage::disk('public')->delete($service['image']);
                }
            }
        }

        return $client->delete();
    }

    /**
     * Get client data formatted for n8n.
     *
     * @param string $id
     * @return array
     */
    public function getClientForN8n(string $id): array
    {
        $client = Client::findOrFail($id);

        return [
            'id' => (string) $client->_id,
            'name' => $client->name,
            'business_category' => $client->business_category,
            'services' => $client->services,
            'business_info' => $client->business_info,
            'ai_config' => $client->ai_config,
        ];
    }

    /**
     * Upload brand logo.
     *
     * @param UploadedFile $logo
     * @return string
     */
    private function uploadBrandLogo(UploadedFile $logo): string
    {
        return $logo->store('brands', 'public');
    }

    /**
     * Upload service image.
     *
     * @param UploadedFile $image
     * @return string
     */
    private function uploadServiceImage(UploadedFile $image): string
    {
        return $image->store('services', 'public');
    }

    /**
     * Process service images from uploaded files.
     *
     * @param array $services
     * @param array $serviceImages
     * @return array
     */
    private function processServiceImages(array $services, array $serviceImages): array
    {
        foreach ($services as $index => &$service) {
            if (isset($serviceImages[$index]) && $serviceImages[$index] instanceof UploadedFile) {
                $service['image'] = $this->uploadServiceImage($serviceImages[$index]);
            }
        }
        
        return $services;
    }
}
