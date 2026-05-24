@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.path.website-builder.index', $restaurant->slug) }}" class="text-blue-600 hover:text-blue-800 font-bold">← Back to Website Builder</a>
            <h1 class="text-4xl font-bold text-gray-900 border-b-4 border-black pb-4 mt-4">Design Settings</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form -->
            <div class="lg:col-span-2">
                <div class="border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
                    <form id="designForm" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Colors Section -->
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-gray-300">Colors</h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Primary Color -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-900 mb-2">Primary Color</label>
                                    <div class="flex gap-2">
                                        <input type="color" name="primary_color" value="{{ $websiteSetting->primary_color }}" class="w-16 h-10 border-2 border-gray-300 cursor-pointer">
                                        <input type="text" name="primary_color_text" value="{{ $websiteSetting->primary_color }}" class="flex-1 px-3 py-2 border-2 border-gray-300 font-mono text-sm" readonly>
                                    </div>
                                </div>

                                <!-- Secondary Color -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-900 mb-2">Secondary Color</label>
                                    <div class="flex gap-2">
                                        <input type="color" name="secondary_color" value="{{ $websiteSetting->secondary_color }}" class="w-16 h-10 border-2 border-gray-300 cursor-pointer">
                                        <input type="text" name="secondary_color_text" value="{{ $websiteSetting->secondary_color }}" class="flex-1 px-3 py-2 border-2 border-gray-300 font-mono text-sm" readonly>
                                    </div>
                                </div>

                                <!-- Accent Color -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-900 mb-2">Accent Color</label>
                                    <div class="flex gap-2">
                                        <input type="color" name="accent_color" value="{{ $websiteSetting->accent_color }}" class="w-16 h-10 border-2 border-gray-300 cursor-pointer">
                                        <input type="text" name="accent_color_text" value="{{ $websiteSetting->accent_color }}" class="flex-1 px-3 py-2 border-2 border-gray-300 font-mono text-sm" readonly>
                                    </div>
                                </div>

                                <!-- Text Color -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-900 mb-2">Text Color</label>
                                    <div class="flex gap-2">
                                        <input type="color" name="text_color" value="{{ $websiteSetting->text_color }}" class="w-16 h-10 border-2 border-gray-300 cursor-pointer">
                                        <input type="text" name="text_color_text" value="{{ $websiteSetting->text_color }}" class="flex-1 px-3 py-2 border-2 border-gray-300 font-mono text-sm" readonly>
                                    </div>
                                </div>

                                <!-- Background Color -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-900 mb-2">Background Color</label>
                                    <div class="flex gap-2">
                                        <input type="color" name="background_color" value="{{ $websiteSetting->background_color }}" class="w-16 h-10 border-2 border-gray-300 cursor-pointer">
                                        <input type="text" name="background_color_text" value="{{ $websiteSetting->background_color }}" class="flex-1 px-3 py-2 border-2 border-gray-300 font-mono text-sm" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Typography Section -->
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-gray-300">Typography</h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Font Family -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-900 mb-2">Body Font</label>
                                    <select name="font_family" class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                                        <option value="Inter" {{ $websiteSetting->font_family === 'Inter' ? 'selected' : '' }}>Inter</option>
                                        <option value="Poppins" {{ $websiteSetting->font_family === 'Poppins' ? 'selected' : '' }}>Poppins</option>
                                        <option value="Roboto" {{ $websiteSetting->font_family === 'Roboto' ? 'selected' : '' }}>Roboto</option>
                                        <option value="Playfair Display" {{ $websiteSetting->font_family === 'Playfair Display' ? 'selected' : '' }}>Playfair Display</option>
                                        <option value="Montserrat" {{ $websiteSetting->font_family === 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
                                        <option value="Lato" {{ $websiteSetting->font_family === 'Lato' ? 'selected' : '' }}>Lato</option>
                                        <option value="Open Sans" {{ $websiteSetting->font_family === 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                                    </select>
                                </div>

                                <!-- Heading Font -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-900 mb-2">Heading Font</label>
                                    <select name="heading_font" class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                                        <option value="Inter" {{ $websiteSetting->heading_font === 'Inter' ? 'selected' : '' }}>Inter</option>
                                        <option value="Poppins" {{ $websiteSetting->heading_font === 'Poppins' ? 'selected' : '' }}>Poppins</option>
                                        <option value="Roboto" {{ $websiteSetting->heading_font === 'Roboto' ? 'selected' : '' }}>Roboto</option>
                                        <option value="Playfair Display" {{ $websiteSetting->heading_font === 'Playfair Display' ? 'selected' : '' }}>Playfair Display</option>
                                        <option value="Montserrat" {{ $websiteSetting->heading_font === 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
                                        <option value="Lato" {{ $websiteSetting->heading_font === 'Lato' ? 'selected' : '' }}>Lato</option>
                                        <option value="Open Sans" {{ $websiteSetting->heading_font === 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-bold border-2 border-blue-800 hover:bg-blue-700">
                                Save Design Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview -->
            <div>
                <div class="border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6 sticky top-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Preview</h3>
                    
                    <div id="preview" class="border-2 border-gray-300 p-4 rounded" style="background-color: var(--preview-bg);">
                        <div style="color: var(--preview-text);">
                            <h2 class="text-2xl font-bold mb-2" style="font-family: var(--preview-heading);">Heading Example</h2>
                            <p class="mb-4" style="font-family: var(--preview-body);">This is how your website will look with the selected colors and fonts.</p>
                            <button class="px-4 py-2 font-bold" style="background-color: var(--preview-primary); color: white; border: 2px solid var(--preview-secondary);">
                                Call to Action
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update preview colors
const colorInputs = document.querySelectorAll('input[type="color"]');
const preview = document.getElementById('preview');

function updatePreview() {
    const primaryColor = document.querySelector('input[name="primary_color"]').value;
    const secondaryColor = document.querySelector('input[name="secondary_color"]').value;
    const textColor = document.querySelector('input[name="text_color"]').value;
    const backgroundColor = document.querySelector('input[name="background_color"]').value;
    const fontFamily = document.querySelector('select[name="font_family"]').value;
    const headingFont = document.querySelector('select[name="heading_font"]').value;

    preview.style.setProperty('--preview-primary', primaryColor);
    preview.style.setProperty('--preview-secondary', secondaryColor);
    preview.style.setProperty('--preview-text', textColor);
    preview.style.setProperty('--preview-bg', backgroundColor);
    preview.style.setProperty('--preview-body', fontFamily);
    preview.style.setProperty('--preview-heading', headingFont);
}

colorInputs.forEach(input => {
    input.addEventListener('change', updatePreview);
});

document.querySelectorAll('select').forEach(select => {
    select.addEventListener('change', updatePreview);
});

// Update text inputs when color changes
document.querySelectorAll('input[type="color"]').forEach(input => {
    input.addEventListener('change', function() {
        const textInput = this.parentElement.querySelector('input[type="text"]');
        if (textInput) {
            textInput.value = this.value;
        }
    });
});

// Form submission
document.getElementById('designForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    try {
        const response = await fetch('{{ route("admin.path.website-builder.update-design", $restaurant->slug) }}', {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();
        if (result.success) {
            alert('Design settings saved successfully!');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error saving design settings');
    }
});

// Initialize preview
updatePreview();
</script>
@endsection
