<?php

namespace App\Http\Controllers;

use App\Services\ClientService;
use App\Services\InteractionLogService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private ClientService $clientService,
        private InteractionLogService $logService
    ) {}

    /**
     * Show the dashboard.
     *
     * @return View
     */
    public function index(): View
    {
        $stats = $this->logService->getStatistics();
        $stats['total_clients'] = \App\Models\Client::count();

        return view('dashboard.index', compact('stats'));
    }
}
