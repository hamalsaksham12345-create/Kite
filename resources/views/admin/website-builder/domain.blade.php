@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.path.website-builder.index', $restaurant->slug) }}" class="text-blue-600 hover:text-blue-800 font-bold">← Back to Website Builder</a>
            <h1 class="text-4xl font-bold text-gray-900 border-b-4 border-black pb-4 mt-4">Domain Settings</h1>
        </div>

        <div class="border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
            <form id="domainForm" class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Domain Type Selection -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-gray-300">Choose Your Domain</h3>
                    
                    <div class="space-y-4">
                        <!-- Subdomain Option -->
                        <label class="flex items-start p-4 border-2 border-gray-300 cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="use_custom_domain" value="0" {{ !$websiteSetting->use_custom_domain ? 'checked' : '' }} class="mt-1 w-4 h-4">
                            <div class="ml-4 flex-1">
                                <p class="font-bold text-gray-900">Subdomain (Free)</p>
                                <p class="text-gray-600 text-sm">Your website will be available at:</p>
                                <p class="text-lg font-mono font-bold text-blue-600 mt-1">{{ $websiteSetting->subdomain }}.kite.test</p>
                                <p class="text-gray-600 text-sm mt-2">Perfect for getting started quickly</p>
                            </div>
                        </label>

                        <!-- Custom Domain Option -->
                        <label class="flex items-start p-4 border-2 border-gray-300 cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="use_custom_domain" value="1" {{ $websiteSetting->use_custom_domain ? 'checked' : '' }} class="mt-1 w-4 h-4">
                            <div class="ml-4 flex-1">
                                <p class="font-bold text-gray-900">Custom Domain</p>
                                <p class="text-gray-600 text-sm">Use your own domain name:</p>
                                <input type="text" name="custom_domain" value="{{ $websiteSetting->custom_domain }}" placeholder="example.com" class="w-full px-3 py-2 border-2 border-gray-300 font-bold mt-2">
                                <p class="text-gray-600 text-sm mt-2">You'll need to update your domain's DNS settings</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Subdomain Customization -->
                <div id="subdomainSection" class="{{ $websiteSetting->use_custom_domain ? 'hidden' : '' }}">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-gray-300">Subdomain Settings</h3>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Subdomain</label>
                        <div class="flex items-center gap-2">
                            <input type="text" name="subdomain" value="{{ $websiteSetting->subdomain }}" class="flex-1 px-3 py-2 border-2 border-gray-300 font-bold">
                            <span class="font-bold text-gray-900">.kite.test</span>
                        </div>
                        <p class="text-gray-600 text-sm mt-2">Use lowercase letters, numbers, and hyphens only</p>
                    </div>
                </div>

                <!-- DNS Instructions -->
                <div id="dnsSection" class="{{ !$websiteSetting->use_custom_domain ? 'hidden' : '' }} border-4 border-yellow-400 bg-yellow-50 p-4">
                    <h3 class="font-bold text-gray-900 mb-2">DNS Configuration Required</h3>
                    <p class="text-gray-700 text-sm mb-3">To use your custom domain, add this DNS record to your domain provider:</p>
                    <div class="bg-white border-2 border-gray-300 p-3 font-mono text-sm mb-3">
                        <p><strong>Type:</strong> CNAME</p>
                        <p><strong>Name:</strong> @ (or your domain)</p>
                        <p><strong>Value:</strong> kite.test</p>
                    </div>
                    <p class="text-gray-600 text-sm">Changes may take 24-48 hours to propagate</p>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-bold border-2 border-blue-800 hover:bg-blue-700">
                        Save Domain Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Current Domain Info -->
        <div class="mt-8 border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Your Website URL</h3>
            <p class="text-lg font-mono font-bold text-blue-600">{{ $websiteSetting->getDomain() }}</p>
            <p class="text-gray-600 text-sm mt-2">
                @if($websiteSetting->is_published)
                    Your website is <span class="font-bold text-green-600">LIVE</span> and accessible to customers
                @else
                    Your website is <span class="font-bold text-yellow-600">DRAFT</span> - publish it to make it visible
                @endif
            </p>
        </div>
    </div>
</div>

<script>
const useCustomDomainRadios = document.querySelectorAll('input[name="use_custom_domain"]');
const subdomainSection = document.getElementById('subdomainSection');
const dnsSection = document.getElementById('dnsSection');

useCustomDomainRadios.forEach(radio => {
    radio.addEventListener('change', function() {
        if (this.value === '1') {
            subdomainSection.classList.add('hidden');
            dnsSection.classList.remove('hidden');
        } else {
            subdomainSection.classList.remove('hidden');
            dnsSection.classList.add('hidden');
        }
    });
});

document.getElementById('domainForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    data.use_custom_domain = data.use_custom_domain === '1';

    try {
        const response = await fetch('{{ route("admin.path.website-builder.update-domain", $restaurant->slug) }}', {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();
        if (result.success) {
            alert('Domain settings saved successfully!');
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error saving domain settings');
    }
});
</script>
@endsection
