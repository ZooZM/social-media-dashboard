<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ClientService;
use App\Services\InteractionLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class N8nController extends Controller
{
    public function __construct(
        private ClientService $clientService,
        private InteractionLogService $logService
    ) {}

    /**
     * Get client data for n8n workflow.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function getClient(string $id): JsonResponse
    {
        try {
            $clientData = $this->clientService->getClientForN8n($id);

            return response()->json([
                'success' => true,
                'data' => $clientData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found.',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Lookup client by social media ID.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function lookupClient(Request $request): JsonResponse
    {
        $fbPageId = $request->query('fb_page_id');
        $instaAccountId = $request->query('insta_account_id');

        if (!$fbPageId && !$instaAccountId) {
            return response()->json([
                'success' => false,
                'message' => 'Either fb_page_id or insta_account_id is required.',
            ], 400);
        }

        try {
            $clientData = $this->clientService->lookupClient($fbPageId, $instaAccountId);

            if ($clientData) {
                return response()->json([
                    'success' => true,
                    'data' => $clientData,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Client not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error looking up client.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Log interaction from n8n.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logInteraction(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|string',
            'message' => 'required|string',
            'response' => 'required|string',
            'platform' => 'required|string',
            'metadata' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $log = $this->logService->logInteraction($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Interaction logged successfully.',
                'log_id' => (string) $log->_id,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to log interaction.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Get all clients.
     *
     * @return JsonResponse
     */
    public function getClients(): JsonResponse
    {
        try {
            $clients = $this->clientService->getAllClients();

            return response()->json([
                'success' => true,
                'data' => $clients,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve clients.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get interaction logs.
     *
     * @return JsonResponse
     */
    public function getLogs(): JsonResponse
    {
        try {
            $logs = $this->logService->getLatestLogs();

            return response()->json([
                'success' => true,
                'data' => $logs,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve logs.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get interaction statistics.
     *
     * @return JsonResponse
     */
    public function getStats(): JsonResponse
    {
        try {
            $stats = $this->logService->getStatistics();

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
