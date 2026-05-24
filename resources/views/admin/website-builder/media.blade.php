@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.path.website-builder.index', $restaurant->slug) }}" class="text-blue-600 hover:text-blue-800 font-bold">← Back to Website Builder</a>
            <h1 class="text-4xl font-bold text-gray-900 border-b-4 border-black pb-4 mt-4">Media Manager</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Logo Upload -->
            <div class="border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Logo</h3>
                <p class="text-gray-600 text-sm mb-4">Recommended: 200x200px, PNG or JPG (Max 2MB)</p>
                
                <div class="mb-4">
                    @if($websiteSetting->logo_path)
                        <img src="{{ Storage::url($websiteSetting->logo_path) }}" alt="Logo" class="max-w-xs h-auto border-2 border-gray-300">
                    @else
                        <div class="w-32 h-32 border-4 border-dashed border-gray-300 flex items-center justify-center bg-gray-50">
                            <span class="text-gray-400 text-sm">No logo</span>
                        </div>
                    @endif
                </div>

                <form id="logoForm" class="space-y-4">
                    @csrf
                    <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-2 file:border-gray-300 file:rounded file:text-sm file:font-bold file:bg-gray-50 hover:file:bg-gray-100">
                    <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-bold border-2 border-blue-800 hover:bg-blue-700">
                        Upload Logo
                    </button>
                </form>
            </div>

            <!-- Favicon Upload -->
            <div class="border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Favicon</h3>
                <p class="text-gray-600 text-sm mb-4">Recommended: 32x32px, PNG or ICO (Max 1MB)</p>
                
                <div class="mb-4">
                    @if($websiteSetting->favicon_path)
                        <img src="{{ Storage::url($websiteSetting->favicon_path) }}" alt="Favicon" class="w-16 h-16 border-2 border-gray-300">
                    @else
                        <div class="w-16 h-16 border-4 border-dashed border-gray-300 flex items-center justify-center bg-gray-50">
                            <span class="text-gray-400 text-xs">No favicon</span>
                        </div>
                    @endif
                </div>

                <form id="faviconForm" class="space-y-4">
                    @csrf
                    <input type="file" name="favicon" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-2 file:border-gray-300 file:rounded file:text-sm file:font-bold file:bg-gray-50 hover:file:bg-gray-100">
                    <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-bold border-2 border-blue-800 hover:bg-blue-700">
                        Upload Favicon
                    </button>
                </form>
            </div>

            <!-- Banner Upload -->
            <div class="border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6 lg:col-span-2">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Banner</h3>
                <p class="text-gray-600 text-sm mb-4">Recommended: 1200x400px, JPG or PNG (Max 5MB)</p>
                
                <div class="mb-4">
                    @if($websiteSetting->banner_path)
                        <img src="{{ Storage::url($websiteSetting->banner_path) }}" alt="Banner" class="w-full h-auto border-2 border-gray-300 max-h-64 object-cover">
                    @else
                        <div class="w-full h-48 border-4 border-dashed border-gray-300 flex items-center justify-center bg-gray-50">
                            <span class="text-gray-400">No banner</span>
                        </div>
                    @endif
                </div>

                <form id="bannerForm" class="space-y-4">
                    @csrf
                    <input type="file" name="banner" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-2 file:border-gray-300 file:rounded file:text-sm file:font-bold file:bg-gray-50 hover:file:bg-gray-100">
                    <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-bold border-2 border-blue-800 hover:bg-blue-700">
                        Upload Banner
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Logo upload
document.getElementById('logoForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("admin.path.website-builder.upload-logo", $restaurant->slug) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: formData,
        });

        const result = await response.json();
        if (result.success) {
            alert('Logo uploaded successfully!');
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error uploading logo');
    }
});

// Favicon upload
document.getElementById('faviconForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("admin.path.website-builder.upload-favicon", $restaurant->slug) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: formData,
        });

        const result = await response.json();
        if (result.success) {
            alert('Favicon uploaded successfully!');
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error uploading favicon');
    }
});

// Banner upload
document.getElementById('bannerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("admin.path.website-builder.upload-banner", $restaurant->slug) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: formData,
        });

        const result = await response.json();
        if (result.success) {
            alert('Banner uploaded successfully!');
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error uploading banner');
    }
});
</script>
@endsection
