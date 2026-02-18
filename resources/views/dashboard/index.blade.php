@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Clients Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Clients</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_clients'] ?? 0 }}</p>
            </div>
            <div class="p-3 bg-brand-100 rounded-lg">
                <svg class="w-8 h-8 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Messages Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Messages</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_messages'] ?? 0 }}</p>
            </div>
            <div class="p-3 bg-purple-100 rounded-lg">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Messages Today Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Messages Today</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['messages_today'] ?? 0 }}</p>
            </div>
            <div class="p-3 bg-green-100 rounded-lg">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Active Platforms Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Active Platforms</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_platforms'] ?? 0 }}</p>
            </div>
            <div class="p-3 bg-orange-100 rounded-lg">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Platform Breakdown -->
@if(isset($stats['platform_breakdown']) && count($stats['platform_breakdown']) > 0)
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Messages by Platform</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($stats['platform_breakdown'] as $platform => $count)
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <span class="text-sm font-medium text-gray-700 capitalize">{{ $platform }}</span>
            <span class="text-lg font-bold text-brand-600">{{ $count }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Quick Actions -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="space-y-3">
            <a href="{{ route('clients.create') }}" class="flex items-center px-4 py-3 bg-brand-50 hover:bg-brand-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-brand-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="text-brand-900 font-medium">Add New Client</span>
            </a>
            <a href="{{ route('logs.index') }}" class="flex items-center px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-gray-900 font-medium">View All Logs</span>
            </a>
        </div>
    </div>

    <div class="bg-gradient-to-br from-brand-500 to-brand-700 rounded-xl shadow-sm p-6 text-white">
        <h3 class="text-lg font-semibold mb-2">n8n Integration</h3>
        <p class="text-brand-100 text-sm mb-4">Use the API endpoints to connect your n8n workflows with this dashboard.</p>
        <div class="flex space-x-2">
            <span class="px-3 py-1 bg-white/20 rounded-md text-xs font-mono">GET /api/n8n/client/{id}</span>
            <span class="px-3 py-1 bg-white/20 rounded-md text-xs font-mono">POST /api/n8n/log</span>
        </div>
    </div>
</div>
@endsection
