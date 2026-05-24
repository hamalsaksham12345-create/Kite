@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.path.website-builder.index', $restaurant->slug) }}" class="text-blue-600 hover:text-blue-800 font-bold">← Back to Website Builder</a>
            <h1 class="text-4xl font-bold text-gray-900 border-b-4 border-black pb-4 mt-4">Contact Details</h1>
        </div>

        <div class="border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
            <form id="contactForm" class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Contact Information -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-gray-300">Contact Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Phone</label>
                            <input type="tel" name="phone" value="{{ $websiteSetting->phone }}" class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Email</label>
                            <input type="email" name="email" value="{{ $websiteSetting->email }}" class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Address</label>
                            <input type="text" name="address" value="{{ $websiteSetting->address }}" class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">City</label>
                            <input type="text" name="city" value="{{ $websiteSetting->city }}" class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">State/Province</label>
                            <input type="text" name="state" value="{{ $websiteSetting->state }}" class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Postal Code</label>
                            <input type="text" name="postal_code" value="{{ $websiteSetting->postal_code }}" class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Country</label>
                            <input type="text" name="country" value="{{ $websiteSetting->country }}" class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">WhatsApp Number</label>
                            <input type="tel" name="whatsapp_number" value="{{ $websiteSetting->whatsapp_number }}" class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-gray-300">Social Media</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Facebook URL</label>
                            <input type="url" name="facebook_url" value="{{ $websiteSetting->facebook_url }}" placeholder="https://facebook.com/..." class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Instagram URL</label>
                            <input type="url" name="instagram_url" value="{{ $websiteSetting->instagram_url }}" placeholder="https://instagram.com/..." class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Twitter URL</label>
                            <input type="url" name="twitter_url" value="{{ $websiteSetting->twitter_url }}" placeholder="https://twitter.com/..." class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">LinkedIn URL</label>
                            <input type="url" name="linkedin_url" value="{{ $websiteSetting->linkedin_url }}" placeholder="https://linkedin.com/..." class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">YouTube URL</label>
                            <input type="url" name="youtube_url" value="{{ $websiteSetting->youtube_url }}" placeholder="https://youtube.com/..." class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-bold border-2 border-blue-800 hover:bg-blue-700">
                        Save Contact Details
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('contactForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    try {
        const response = await fetch('{{ route("admin.path.website-builder.update-contact", $restaurant->slug) }}', {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();
        if (result.success) {
            alert('Contact details saved successfully!');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error saving contact details');
    }
});
</script>
@endsection
