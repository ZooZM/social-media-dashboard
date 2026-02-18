<aside 
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
    class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-brand-800 to-brand-900 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0"
>
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-16 px-6 bg-brand-900/50">
        <h2 class="text-xl font-bold">n8n Dashboard</h2>
        <button 
            @click="sidebarOpen = false" 
            class="lg:hidden text-white hover:text-gray-300"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 px-4">
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 mb-2 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-brand-700 text-white' : 'text-brand-100 hover:bg-brand-700/50' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('clients.index') }}" class="flex items-center px-4 py-3 mb-2 rounded-lg transition-colors {{ request()->routeIs('clients.*') ? 'bg-brand-700 text-white' : 'text-brand-100 hover:bg-brand-700/50' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Clients
        </a>

        <a href="{{ route('logs.index') }}" class="flex items-center px-4 py-3 mb-2 rounded-lg transition-colors {{ request()->routeIs('logs.*') ? 'bg-brand-700 text-white' : 'text-brand-100 hover:bg-brand-700/50' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Interaction Logs
        </a>
    </nav>

    <!-- Footer -->
    <div class="absolute bottom-0 left-0 right-0 p-4 bg-brand-900/50">
        <p class="text-xs text-brand-300 text-center">Powered by n8n & Laravel</p>
    </div>
</aside>

<!-- Overlay for mobile -->
<div 
    x-show="sidebarOpen" 
    @click="sidebarOpen = false" 
    class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
></div>
