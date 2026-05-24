@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 border-b-4 border-black pb-4">Website Builder</h1>
            <p class="text-gray-600 mt-2">Customize your restaurant's public website without coding</p>
        </div>

        <!-- Status Card -->
        <div class="mb-8 border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Website Status</h2>
                        <p class="text-gray-600 mt-1">
                            @if($websiteSetting->is_published)
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 font-bold border-2 border-green-800">PUBLISHED</span>
                            @else
                                <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 font-bold border-2 border-yellow-800">DRAFT</span>
                            @endif
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Your website URL:</p>
                        <p class="text-lg font-bold text-gray-900">{{ $websiteSetting->getDomain() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Builder Sections Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Design Section -->
            <a href="{{ route('admin.path.website-builder.design', $restaurant->slug) }}" class="border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] transition-all">
                <div class="p-6">
                    <div class="text-4xl font-bold text-gray-900 mb-2">🎨</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Design</h3>
                    <p class="text-gray-600 text-sm">Colors, fonts, and branding</p>
                </div>
            </a>

            <!-- Content Section -->
            <a href="{{ route('admin.path.website-builder.content', $restaurant->slug) }}" class="border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] transition-all">
                <div class="p-6">
                    <div class="text-4xl font-bold text-gray-900 mb-2">📝</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Content</h3>
                    <p class="text-gray-600 text-sm">Homepage text and sections</p>
                </div>
            </a>

            <!-- Media Section -->
            <a href="{{ route('admin.path.website-builder.media', $restaurant->slug) }}" class="border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] transition-all">
                <div class="p-6">
                    <div class="text-4xl font-bold text-gray-900 mb-2">🖼️</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Media</h3>
                    <p class="text-gray-600 text-sm">Logo, banner, and images</p>
                </div>
            </a>

            <!-- Contact Section -->
            <a href="{{ route('admin.path.website-builder.contact', $restaurant->slug) }}" class="border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] transition-all">
                <div class="p-6">
                    <div class="text-4xl font-bold text-gray-900 mb-2">📞</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Contact</h3>
                    <p class="text-gray-600 text-sm">Phone, email, and social media</p>
                </div>
            </a>

            <!-- Domain Section -->
            <a href="{{ route('admin.path.website-builder.domain', $restaurant->slug) }}" class="border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] transition-all">
                <div class="p-6">
                    <div class="text-4xl font-bold text-gray-900 mb-2">🌐</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Domain</h3>
                    <p class="text-gray-600 text-sm">Subdomain or custom domain</p>
                </div>
            </a>

            <!-- Preview Section -->
            <a href="{{ route('admin.path.website-builder.preview', $restaurant->slug) }}" target="_blank" class="border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] transition-all">
                <div class="p-6">
                    <div class="text-4xl font-bold text-gray-900 mb-2">👁️</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Preview</h3>
                    <p class="text-gray-600 text-sm">View your live website</p>
                </div>
            </a>
        </div>

        <!-- Publish Section -->
        <div class="mt-8 border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
            <div class="p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Publish Your Website</h3>
                <p class="text-gray-600 mb-6">Make your website live and visible to customers</p>
                
                <div class="flex gap-4">
                    @if($websiteSetting->is_published)
                        <button onclick="unpublishWebsite()" class="px-6 py-3 bg-red-500 text-white font-bold border-2 border-red-700 hover:bg-red-600">
                            Unpublish Website
                        </button>
                    @else
                        <button onclick="publishWebsite()" class="px-6 py-3 bg-green-500 text-white font-bold border-2 border-green-700 hover:bg-green-600">
                            Publish Website
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function publishWebsite() {
    if (confirm('Are you sure you want to publish your website? It will be visible to customers.')) {
        fetch('{{ route("admin.path.website-builder.publish", $restaurant->slug) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Website published successfully!');
                location.reload();
            }
        });
    }
}

function unpublishWebsite() {
    if (confirm('Are you sure you want to unpublish your website? It will no longer be visible to customers.')) {
        fetch('{{ route("admin.path.website-builder.unpublish", $restaurant->slug) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Website unpublished successfully!');
                location.reload();
            }
        });
    }
}
</script>
@endsection
