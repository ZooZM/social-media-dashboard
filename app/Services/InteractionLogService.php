<?php

namespace App\Services;

use App\Models\InteractionLog;
use Illuminate\Support\Collection;

class InteractionLogService
{
    /**
     * Log a new interaction from n8n.
     *
     * @param array $data
     * @return InteractionLog
     */
    public function logInteraction(array $data): InteractionLog
    {
        return InteractionLog::create($data);
    }

    /**
     * Get latest interaction logs.
     *
     * @param int $limit
     * @return Collection
     */
    public function getLatestLogs(int $limit = 50): Collection
    {
        return InteractionLog::with('client')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get statistics about interactions.
     *
     * @return array
     */
    public function getStatistics(): array
    {
        $totalMessages = InteractionLog::count();
        
        $messagesToday = InteractionLog::where('created_at', '>=', now()->startOfDay())->count();
        
        $platformStats = InteractionLog::raw(function($collection) {
            return $collection->aggregate([
                [
                    '$group' => [
                        '_id' => '$platform',
                        'count' => ['$sum' => 1]
                    ]
                ]
            ]);
        })->toArray();

        $activePlatforms = count($platformStats);

        return [
            'total_messages' => $totalMessages,
            'messages_today' => $messagesToday,
            'active_platforms' => $activePlatforms,
            'platform_breakdown' => collect($platformStats)->mapWithKeys(function($item) {
                return [$item['_id'] => $item['count']];
            })->toArray(),
        ];
    }
}
