@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.path.website-builder.index', $restaurant->slug) }}" class="text-blue-600 hover:text-blue-800 font-bold">← Back to Website Builder</a>
            <h1 class="text-4xl font-bold text-gray-900 border-b-4 border-black pb-4 mt-4">Content Editor</h1>
        </div>

        <div class="border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
            <form id="contentForm" class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Hero Section -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-gray-300">Hero Section</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Hero Title</label>
                            <input type="text" name="hero_title" value="{{ $websiteSetting->hero_title }}" placeholder="Welcome to our restaurant" class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                            <p class="text-gray-600 text-sm mt-1">Main headline on your homepage</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Hero Subtitle</label>
                            <textarea name="hero_subtitle" placeholder="Discover amazing food and great service" class="w-full px-3 py-2 border-2 border-gray-300 font-bold h-20">{{ $websiteSetting->hero_subtitle }}</textarea>
                            <p class="text-gray-600 text-sm mt-1">Secondary text below the main headline</p>
                        </div>
                    </div>
                </div>

                <!-- About Section -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-gray-300">About Section</h3>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">About Your Restaurant</label>
                        <textarea name="about_section" placeholder="Tell your customers about your restaurant..." class="w-full px-3 py-2 border-2 border-gray-300 font-bold h-32">{{ $websiteSetting->about_section }}</textarea>
                        <p class="text-gray-600 text-sm mt-1">Share your story, mission, and what makes you special</p>
                    </div>
                </div>

                <!-- Features Section -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-gray-300">Features Section</h3>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Key Features</label>
                        <textarea name="features_section" placeholder="List your key features and highlights..." class="w-full px-3 py-2 border-2 border-gray-300 font-bold h-32">{{ $websiteSetting->features_section }}</textarea>
                        <p class="text-gray-600 text-sm mt-1">Highlight what makes your restaurant unique</p>
                    </div>
                </div>

                <!-- SEO Section -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-gray-300">SEO Settings</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Meta Title</label>
                            <input type="text" name="meta_title" value="{{ $websiteSetting->meta_title }}" placeholder="Your Restaurant - Best Food in Town" class="w-full px-3 py-2 border-2 border-gray-300 font-bold" maxlength="60">
                            <p class="text-gray-600 text-sm mt-1">Appears in search results (max 60 characters)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Meta Description</label>
                            <textarea name="meta_description" placeholder="Discover delicious food and great service at our restaurant..." class="w-full px-3 py-2 border-2 border-gray-300 font-bold h-20" maxlength="160">{{ $websiteSetting->meta_description }}</textarea>
                            <p class="text-gray-600 text-sm mt-1">Appears below title in search results (max 160 characters)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ $websiteSetting->meta_keywords }}" placeholder="restaurant, food, dining, cuisine" class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                            <p class="text-gray-600 text-sm mt-1">Comma-separated keywords for search engines</p>
                        </div>
                    </div>
                </div>

                <!-- Display Options -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-gray-300">Display Options</h3>
                    
                    <div class="space-y-3">
                        <label class="flex items-center p-3 border-2 border-gray-300 cursor-pointer hover:bg-gray-50">
                            <input type="checkbox" name="show_menu_preview" value="1" {{ $websiteSetting->show_menu_preview ? 'checked' : '' }} class="w-4 h-4">
                            <span class="ml-3 font-bold text-gray-900">Show Menu Preview</span>
                        </label>

                        <label class="flex items-center p-3 border-2 border-gray-300 cursor-pointer hover:bg-gray-50">
                            <input type="checkbox" name="show_testimonials" value="1" {{ $websiteSetting->show_testimonials ? 'checked' : '' }} class="w-4 h-4">
                            <span class="ml-3 font-bold text-gray-900">Show Testimonials</span>
                        </label>

                        <label class="flex items-center p-3 border-2 border-gray-300 cursor-pointer hover:bg-gray-50">
                            <input type="checkbox" name="show_gallery" value="1" {{ $websiteSetting->show_gallery ? 'checked' : '' }} class="w-4 h-4">
                            <span class="ml-3 font-bold text-gray-900">Show Gallery</span>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-bold border-2 border-blue-800 hover:bg-blue-700">
                        Save Content
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('contentForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    // Convert checkboxes to boolean
    data.show_menu_preview = data.show_menu_preview === '1';
    data.show_testimonials = data.show_testimonials === '1';
    data.show_gallery = data.show_gallery === '1';

    try {
        const response = await fetch('{{ route("admin.path.website-builder.update-content", $restaurant->slug) }}', {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();
        if (result.success) {
            alert('Content saved successfully!');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error saving content');
    }
});
</script>
@endsection
