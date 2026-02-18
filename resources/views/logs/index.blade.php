@extends('layouts.app')

@section('title', 'Interaction Logs')
@section('header', 'Interaction Logs')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    @if($logs->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Platform</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Response</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($logs as $log)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        @if($log->client)
                        <div class="flex items-center">
                            @if($log->client->profile_image)
                            <img src="{{ asset('storage/' . $log->client->profile_image) }}" alt="{{ $log->client->name }}" class="w-8 h-8 rounded-full object-cover">
                            @else
                            <div class="w-8 h-8 rounded-full bg-brand-100 flex items-center justify-center">
                                <span class="text-brand-600 font-semibold text-xs">{{ strtoupper(substr($log->client->name, 0, 1)) }}</span>
                            </div>
                            @endif
                            <span class="ml-2 text-sm font-medium text-gray-900">{{ $log->client->name }}</span>
                        </div>
                        @else
                        <span class="text-sm text-gray-500">N/A</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($log->platform === 'whatsapp') bg-green-100 text-green-800
                            @elseif($log->platform === 'instagram') bg-pink-100 text-pink-800
                            @elseif($log->platform === 'facebook') bg-blue-100 text-blue-800
                            @elseif($log->platform === 'telegram') bg-cyan-100 text-cyan-800
                            @else bg-gray-100 text-gray-800
                            @endif
                        ">
                            {{ ucfirst($log->platform) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 max-w-xs">
                        <p class="text-sm text-gray-900 truncate">{{ Str::limit($log->message, 60) }}</p>
                    </td>
                    <td class="px-6 py-4 max-w-xs">
                        <p class="text-sm text-gray-600 truncate">{{ Str::limit($log->response, 60) }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $log->created_at->diffForHumans() }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No logs yet</h3>
        <p class="mt-1 text-sm text-gray-500">Interaction logs from n8n will appear here.</p>
    </div>
    @endif
</div>

@if($logs->count() > 0)
<div class="mt-4 text-sm text-gray-600">
    Showing latest {{ $logs->count() }} interactions
</div>
@endif
@endsection
