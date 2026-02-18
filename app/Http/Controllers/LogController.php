<?php

namespace App\Http\Controllers;

use App\Services\InteractionLogService;
use Illuminate\View\View;

class LogController extends Controller
{
    public function __construct(
        private InteractionLogService $logService
    ) {}

    /**
     * Display interaction logs.
     *
     * @return View
     */
    public function index(): View
    {
        $logs = $this->logService->getLatestLogs(100);
        return view('logs.index', compact('logs'));
    }
}
