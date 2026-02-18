@extends('layouts.app')

@section('title', 'Edit Client')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Client</h1>
        <p class="text-gray-600 mt-1">Update client profile, services, and business information</p>
    </div>

    <form action="{{ route('clients.update', $client->_id) }}" method="POST" enctype="multipart/form-data" 
        x-data="clientEditForm({{ json_encode($client) }})">
        @csrf
        @method('PUT')

        <!-- Basic Information Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Basic Information</h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Client Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Client Name *</label>
                    <input type="text" name="name" id="name" x-model="name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Business Category -->
                <div>
                    <label for="business_category" class="block text-sm font-medium text-gray-700 mb-2">Business Category *</label>
                    <input type="text" name="business_category" id="business_category" x-model="businessCategory" required
                        placeholder="e.g., Real Estate, Restaurant, Fashion"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    @error('business_category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Brand Logo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Brand Logo</label>
                    <div class="flex items-center space-x-4">
                        <div class="w-20 h-20 rounded-lg overflow-hidden border-2 border-gray-200">
                            <img x-bind:src="logoPreview || (currentLogo ? '/storage/' + currentLogo : '')" 
                                alt="Logo" class="w-full h-full object-cover">
                        </div>
                        <label class="flex-1 flex items-center justify-center px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-brand-500 transition-colors">
                            <div class="text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="mt-2 block text-sm text-gray-600">Replace logo</span>
                            </div>
                            <input type="file" name="brand_logo" accept="image/*" class="hidden" @change="previewLogo">
                        </label>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Maximum file size: 2MB</p>
                </div>
            </div>
        </div>

        <!-- Services Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Services</h2>
                    <p class="text-sm text-gray-600 mt-0.5">Manage services your business offers</p>
                </div>
                <button type="button" @click="addService" 
                    class="px-4 py-2 bg-brand-600 text-white rounded-lg hover:bg-brand-700 transition-colors text-sm font-medium">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Service
                    </span>
                </button>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <template x-for="(service, index) in services" :key="index">
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="font-medium text-gray-900" x-text="'Service ' + (index + 1)"></h3>
                                <button type="button" @click="removeService(index)" 
                                    class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Service Name *</label>
                                    <input type="text" x-model="service.name" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
                                    <input type="text" x-model="service.price" placeholder="e.g., 500 SAR" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm">
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                                <textarea x-model="service.description" rows="2" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm"></textarea>
                            </div>

                            <!-- Variants -->
                            <div class="mt-3">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-medium text-gray-700">Variants (Optional)</label>
                                    <button type="button" @click="addVariant(index)" 
                                        class="text-xs text-brand-600 hover:text-brand-700 font-medium">+ Add Variant</button>
                                </div>
                                <template x-if="service.variants && service.variants.length > 0">
                                    <div class="space-y-2">
                                        <template x-for="(variant, vIndex) in service.variants" :key="vIndex">
                                            <div class="flex items-center gap-2 bg-white p-2 rounded border border-gray-200">
                                                <input type="text" x-model="variant.name" placeholder="Variant name" 
                                                    class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm">
                                                <input type="text" x-model="variant.price" placeholder="Price" 
                                                    class="w-24 px-2 py-1 border border-gray-300 rounded text-sm">
                                                <button type="button" @click="removeVariant(index, vIndex)" 
                                                    class="text-red-600 hover:text-red-800 text-sm">×</button>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <div x-show="services.length === 0" class="text-center py-8 text-gray-500">
                        <p>No services added yet. Click "Add Service" to get started.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Business Information</h2>
                <p class="text-sm text-gray-600 mt-0.5">Contact details and social media presence</p>
            </div>
            <div class="p-6 space-y-10">
                <!-- Social Media Accounts -->
                <div>
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 text-brand-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                        </svg>
                        <h3 class="text-sm font-semibold text-gray-900">Social Media Accounts</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">📷 Instagram</label>
                            <input type="text" x-model="businessInfo.social_media.instagram" placeholder="@username or full URL"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">🐦 Twitter/X</label>
                            <input type="text" x-model="businessInfo.social_media.twitter" placeholder="@username or full URL"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">👥 Facebook</label>
                            <input type="text" x-model="businessInfo.social_media.facebook" placeholder="Page name or full URL"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">💼 LinkedIn</label>
                            <input type="text" x-model="businessInfo.social_media.linkedin" placeholder="Company name or full URL"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">🎵 TikTok</label>
                            <input type="text" x-model="businessInfo.social_media.tiktok" placeholder="@username or full URL"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">👻 Snapchat</label>
                            <input type="text" x-model="businessInfo.social_media.snapchat" placeholder="Username or full URL"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div>
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 text-brand-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-sm font-semibold text-gray-900">Contact Information</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" x-model="businessInfo.contact.phone" placeholder="966501234567"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" x-model="businessInfo.contact.email" placeholder="info@brand.com"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                            <input type="text" x-model="businessInfo.contact.whatsapp" placeholder="966501234567"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                            <input type="url" x-model="businessInfo.contact.website" placeholder="https://example.com"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                        </div>
                    </div>
                </div>

                <!-- Business Details -->
                <div>
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 text-brand-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="text-sm font-semibold text-gray-900">Business Details</h3>
                    </div>
                    
                    <!-- Working Hours (Time Slots) -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-xs font-medium text-gray-700">Working Hours</h4>
                            <button type="button" @click="addTimeSlot" 
                                class="text-xs text-brand-600 hover:text-brand-700 font-medium">+ Add Time Slot</button>
                        </div>

                        <template x-if="businessInfo.working_hours.length > 0">
                            <div class="space-y-3">
                                <template x-for="(slot, slotIndex) in businessInfo.working_hours" :key="slotIndex">
                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                        <div class="flex items-start justify-between mb-3">
                                            <span class="text-sm font-medium text-gray-700" x-text="'Slot ' + (slotIndex + 1)"></span>
                                            <button type="button" @click="removeTimeSlot(slotIndex)" 
                                                class="text-red-600 hover:text-red-800 text-xs">Remove</button>
                        </div>

                                        <!-- Time Range -->
                                        <div class="grid grid-cols-2 gap-3 mb-3">
                                            <div>
                                                <label class="block text-xs text-gray-600 mb-1">From</label>
                                                <input type="time" x-model="slot.start_time" 
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs text-gray-600 mb-1">To</label>
                                                <input type="time" x-model="slot.end_time" 
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                                            </div>
                                        </div>

                                        <!-- Days Selection -->
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-2">Apply to Days</label>
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                                <label class="flex items-center space-x-1 cursor-pointer">
                                                    <input type="checkbox" value="saturday" x-model="slot.days" 
                                                        class="rounded text-brand-600 focus:ring-brand-500">
                                                    <span class="text-xs text-gray-700">Sat</span>
                                                </label>
                                                <label class="flex items-center space-x-1 cursor-pointer">
                                                    <input type="checkbox" value="sunday" x-model="slot.days" 
                                                        class="rounded text-brand-600 focus:ring-brand-500">
                                                    <span class="text-xs text-gray-700">Sun</span>
                                                </label>
                                                <label class="flex items-center space-x-1 cursor-pointer">
                                                    <input type="checkbox" value="monday" x-model="slot.days" 
                                                        class="rounded text-brand-600 focus:ring-brand-500">
                                                    <span class="text-xs text-gray-700">Mon</span>
                                                </label>
                                                <label class="flex items-center space-x-1 cursor-pointer">
                                                    <input type="checkbox" value="tuesday" x-model="slot.days" 
                                                        class="rounded text-brand-600 focus:ring-brand-500">
                                                    <span class="text-xs text-gray-700">Tue</span>
                                                </label>
                                                <label class="flex items-center space-x-1 cursor-pointer">
                                                    <input type="checkbox" value="wednesday" x-model="slot.days" 
                                                        class="rounded text-brand-600 focus:ring-brand-500">
                                                    <span class="text-xs text-gray-700">Wed</span>
                                                </label>
                                                <label class="flex items-center space-x-1 cursor-pointer">
                                                    <input type="checkbox" value="thursday" x-model="slot.days" 
                                                        class="rounded text-brand-600 focus:ring-brand-500">
                                                    <span class="text-xs text-gray-700">Thu</span>
                                                </label>
                                                <label class="flex items-center space-x-1 cursor-pointer">
                                                    <input type="checkbox" value="friday" x-model="slot.days" 
                                                        class="rounded text-brand-600 focus:ring-brand-500">
                                                    <span class="text-xs text-gray-700">Fri</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="businessInfo.working_hours.length === 0">
                            <div class="text-center py-6 bg-gray-50 border border-gray-200 border-dashed rounded-lg">
                                <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm text-gray-500">No working hours defined. Click "Add Time Slot" to get started.</p>
                            </div>
                        </template>
                    </div>

                    <!-- Location/Address -->
                    <div class="mb-4">
                        <h4 class="text-xs font-medium text-gray-700 mb-2">Location</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">City</label>
                                <input type="text" x-model="businessInfo.location.city" placeholder="e.g., Riyadh"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">District</label>
                                <input type="text" x-model="businessInfo.location.district" placeholder="e.g., Al Olaya"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs text-gray-600 mb-1">Full Address (Optional)</label>
                                <textarea x-model="businessInfo.location.full_address" rows="2" placeholder="Street name, building number, etc."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <!-- Payment Methods -->
                    <div>
                        <h4 class="text-xs font-medium text-gray-700 mb-3">💰 Payment Methods</h4>
                        
                        <!-- Payment Type Selection -->
                        <div class="mb-4 space-y-2">
                            <label class="flex items-center space-x-3 cursor-pointer p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition"
                                   :class="businessInfo.payment_methods.type === 'cash' ? 'bg-brand-50 border-brand-500' : ''">
                                <input type="radio" value="cash" x-model="businessInfo.payment_methods.type" 
                                       class="text-brand-600 focus:ring-brand-500">
                                <span class="text-sm font-medium text-gray-700">💵 Cash Only (نقدي فقط)</span>
                            </label>
                            
                            <label class="flex items-center space-x-3 cursor-pointer p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition"
                                   :class="businessInfo.payment_methods.type === 'specific' ? 'bg-brand-50 border-brand-500' : ''">
                                <input type="radio" value="specific" x-model="businessInfo.payment_methods.type" 
                                       class="text-brand-600 focus:ring-brand-500">
                                <span class="text-sm font-medium text-gray-700">💳 Specific Payment Methods (طرق دفع محددة)</span>
                            </label>
                            
                            <label class="flex items-center space-x-3 cursor-pointer p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition"
                                   :class="businessInfo.payment_methods.type === 'installment' ? 'bg-brand-50 border-brand-500' : ''">
                                <input type="radio" value="installment" x-model="businessInfo.payment_methods.type" 
                                       class="text-brand-600 focus:ring-brand-500">
                                <span class="text-sm font-medium text-gray-700">📱 Installment Available (متاح التقسيط)</span>
                            </label>
                        </div>

                        <!-- Specific Payment Methods (shown only if selected) -->
                        <div x-show="businessInfo.payment_methods.type === 'specific'" x-transition class="mb-4 p-4 bg-gray-50 rounded-lg">
                            <h5 class="text-xs font-medium text-gray-700 mb-3">Select Payment Methods:</h5>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" value="visa" x-model="businessInfo.payment_methods.specific_methods" 
                                           class="rounded text-brand-600 focus:ring-brand-500">
                                    <span class="text-sm text-gray-700">💳 Visa/Mastercard</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" value="vodafone_cash" x-model="businessInfo.payment_methods.specific_methods" 
                                           class="rounded text-brand-600 focus:ring-brand-500">
                                    <span class="text-sm text-gray-700">📱 Vodafone Cash</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" value="fawry" x-model="businessInfo.payment_methods.specific_methods" 
                                           class="rounded text-brand-600 focus:ring-brand-500">
                                    <span class="text-sm text-gray-700">💰 Fawry</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" value="bank_transfer" x-model="businessInfo.payment_methods.specific_methods" 
                                           class="rounded text-brand-600 focus:ring-brand-500">
                                    <span class="text-sm text-gray-700">🏦 Bank Transfer</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" value="instapay" x-model="businessInfo.payment_methods.specific_methods" 
                                           class="rounded text-brand-600 focus:ring-brand-500">
                                    <span class="text-sm text-gray-700">⚡ InstaPay</span>
                                </label>
                            </div>
                            <div class="mt-3">
                                <label class="block text-xs text-gray-600 mb-1">Other (أخرى)</label>
                                <input type="text" x-model="businessInfo.payment_methods.other" 
                                       placeholder="e.g., PayPal, Stripe, etc."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                            </div>
                        </div>

                        <!-- Installment Plans (shown only if selected) -->
                        <div x-show="businessInfo.payment_methods.type === 'installment'" x-transition class="p-4 bg-gray-50 rounded-lg">
                            <h5 class="text-xs font-medium text-gray-700 mb-3">Select Installment Services:</h5>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" value="valu" x-model="businessInfo.payment_methods.installments" 
                                           class="rounded text-brand-600 focus:ring-brand-500">
                                    <span class="text-sm text-gray-700">💳 Valu (القيمة)</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" value="sympl" x-model="businessInfo.payment_methods.installments" 
                                           class="rounded text-brand-600 focus:ring-brand-500">
                                    <span class="text-sm text-gray-700">💳 Sympl</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" value="souhoola" x-model="businessInfo.payment_methods.installments" 
                                           class="rounded text-brand-600 focus:ring-brand-500">
                                    <span class="text-sm text-gray-700">💳 Souhoola (سهولة)</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" value="contact" x-model="businessInfo.payment_methods.installments" 
                                           class="rounded text-brand-600 focus:ring-brand-500">
                                    <span class="text-sm text-gray-700">💳 Contact</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" value="premium_card" x-model="businessInfo.payment_methods.installments" 
                                           class="rounded text-brand-600 focus:ring-brand-500">
                                    <span class="text-sm text-gray-700">💳 Premium Card</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" value="forsa" x-model="businessInfo.payment_methods.installments" 
                                           class="rounded text-brand-600 focus:ring-brand-500">
                                    <span class="text-sm text-gray-700">💳 Forsa (فرصة)</span>
                                </label>
                            </div>
                            <div class="mt-3">
                                <label class="block text-xs text-gray-600 mb-1">Other (أخرى)</label>
                                <input type="text" x-model="businessInfo.payment_methods.other" 
                                       placeholder="e.g., Other installment service"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<!-- Offers & Promotions Card -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Offers & Promotions</h2>
            <p class="text-sm text-gray-600 mt-0.5">Create time-limited promotional offers for your services</p>
        </div>
        <button type="button" @click="addOffer" 
            class="inline-flex items-center px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add Offer
        </button>
    </div>
    <div class="p-6">
        <div class="space-y-4" id="offers-container">
            <template x-for="(offer, index) in offers" :key="index">
                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <div class="flex items-start justify-between mb-3">
                        <h3 class="font-medium text-gray-900" x-text="'Offer ' + (index + 1)"></h3>
                        <button type="button" @click="removeOffer(index)" 
                            class="text-red-600 hover:text-red-800 text-sm font-medium">Remove</button>
                    </div>

                    <!-- Offer Title & Description -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Offer Title *</label>
                            <input type="text" x-model="offer.title" placeholder="e.g., Summer Sale" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <input type="text" x-model="offer.description" placeholder="Brief description of the offer"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                        </div>
                    </div>

                    <!-- Discount Settings -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type *</label>
                            <select x-model="offer.discount_type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed Amount (ج.م)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <span x-text="offer.discount_type === 'percentage' ? 'Discount %' : 'Discount Amount'"></span> *
                            </label>
                            <input type="number" x-model="offer.discount_value" placeholder="0" min="0" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                        </div>
                    </div>

                    <!-- Date Range -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                            <input type="date" x-model="offer.start_date" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date *</label>
                            <input type="date" x-model="offer.end_date" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                        </div>
                    </div>

                    <!-- Applicable Services -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Apply to Services *</label>
                        <template x-if="services.length > 0">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 p-3 bg-white rounded-lg border border-gray-200">
                                <template x-for="(service, sIndex) in services" :key="sIndex">
                                    <label class="flex items-start space-x-2 cursor-pointer p-2 hover:bg-gray-50 rounded">
                                        <input type="checkbox" :value="sIndex" x-model="offer.applicable_services"
                                            class="mt-1 rounded text-brand-600 focus:ring-brand-500">
                                        <div class="flex-1">
                                            <span class="text-sm font-medium text-gray-900" x-text="service.name"></span>
                                            <span class="text-xs text-gray-500 block" x-text="'Price: ' + service.price"></span>
                                        </div>
                                    </label>
                                </template>
                            </div>
                        </template>
                        <template x-if="services.length === 0">
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-center">
                                <svg class="w-8 h-8 text-yellow-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <p class="text-sm text-yellow-800">Add services first to apply offers</p>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <div x-show="offers.length === 0" class="text-center py-8 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                </svg>
                <p>No offers added yet. Click "Add Offer" to create promotional offers.</p>
            </div>
        </div>
    </div>
</div>
        </div>

        <!-- AI Configuration Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">AI Configuration</h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Brand Voice</label>
                    <input type="text" x-model="aiConfig.brand_voice" placeholder="e.g., Professional and friendly"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                    <p class="mt-1 text-xs text-gray-500">Describe how the AI should communicate</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                    <select x-model="aiConfig.language" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 text-sm">
                        <option value="ar">Arabic</option>
                        <option value="en">English</option>
                        <option value="both">Both</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Hidden inputs for JSON data -->
        <input type="hidden" name="services" x-bind:value="JSON.stringify(services)">
        <input type="hidden" name="business_info" x-bind:value="JSON.stringify(businessInfo)">
        <input type="hidden" name="ai_config" x-bind:value="JSON.stringify(aiConfig)">

        <!-- Action Buttons -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('clients.index') }}" 
                class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" 
                class="px-6 py-2 bg-brand-600 text-white rounded-lg hover:bg-brand-700 transition-colors font-medium">
                Update Client
            </button>
        </div>
    </form>
</div>

<script>
function clientEditForm(client) {
    return {
        name: client.name || '',
        businessCategory: client.business_category || '',
        currentLogo: client.brand_logo || null,
        logoPreview: null,
        services: client.services || [],
        businessInfo: client.business_info || {
            social_media: {
                instagram: '',
                twitter: '',
                facebook: '',
                linkedin: '',
                tiktok: '',
                snapchat: ''
            },
            contact: { phone: '', email: '', whatsapp: '', website: '' },
            location: {
                city: '',
                district: '',
                full_address: ''
            },
            working_hours: client.business_info?.working_hours || []
            payment_methods: {
                type: 'cash',
                specific_methods: [],
                installments: [],
                other: ''
            }
        },
        offers: client.offers || [],
        aiConfig: client.ai_config || {
            brand_voice: '',
            language: 'ar'
        },

        previewLogo(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.logoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        addService() {
            this.services.push({
                name: '',
                description: '',
                price: '',
                variants: []
            });
        },

        removeService(index) {
            this.services.splice(index, 1);
        },

        addVariant(serviceIndex) {
            if (!this.services[serviceIndex].variants) {
                this.services[serviceIndex].variants = [];
            }
            this.services[serviceIndex].variants.push({
                name: '',
                price: ''
            });
        },

        removeVariant(serviceIndex, variantIndex) {
            this.services[serviceIndex].variants.splice(variantIndex, 1);
        },

        addOffer() {
            this.offers.push({
                title: '',
                description: '',
                discount_type: 'percentage',
                discount_value: 0,
                start_date: '',
                end_date: '',
                applicable_services: []
            });
        },

        removeOffer(index) {
            this.offers.splice(index, 1);
        },

        addTimeSlot() {
            this.businessInfo.working_hours.push({
                start_time: '',
                end_time: '',
                days: []
            });
        },

        removeTimeSlot(index) {
            this.businessInfo.working_hours.splice(index, 1);
        }
    }
}
</script>
@endsection
