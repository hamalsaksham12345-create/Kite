<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Edit Table {{ $table->table_number }} - {{ $currentRestaurant->name ?? 'Restaurant' }} Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Restaurant Header -->
    <x-restaurant-header />

    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b-2 border-black">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('admin.path.tables.index', $currentRestaurant->slug) }}" 
                           class="text-lg font-bold text-gray-900 hover:text-emerald-600">
                            ← Back to Tables
                        </a>
                        <h1 class="text-2xl font-black text-black">Edit Table {{ $table->table_number }}</h1>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="bg-white border-4 border-black hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <div class="p-8">
                    <form action="{{ route('admin.path.tables.update', [$currentRestaurant->slug, $table]) }}" 
                          method="POST" 
                          class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Table Number -->
                        <div>
                            <label for="table_number" class="block text-lg font-black text-black mb-3">Table Number</label>
                            <input id="table_number" 
                                   name="table_number" 
                                   type="text" 
                                   required 
                                   value="{{ old('table_number', $table->table_number) }}"
                                   class="appearance-none block w-full px-4 py-4 border-4 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('table_number') border-red-600 @enderror"
                                   placeholder="e.g., T1, Table 1, A1">
                            @error('table_number')
                                <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Capacity -->
                        <div>
                            <label for="capacity" class="block text-lg font-black text-black mb-3">Seating Capacity</label>
                            <input id="capacity" 
                                   name="capacity" 
                                   type="number" 
                                   min="1"
                                   max="20"
                                   required 
                                   value="{{ old('capacity', $table->capacity) }}"
                                   class="appearance-none block w-full px-4 py-4 border-4 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('capacity') border-red-600 @enderror"
                                   placeholder="Number of seats">
                            @error('capacity')
                                <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-lg font-black text-black mb-3">Notes (Optional)</label>
                            <textarea id="notes" 
                                      name="notes" 
                                      rows="3"
                                      class="appearance-none block w-full px-4 py-4 border-4 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('notes') border-red-600 @enderror"
                                      placeholder="e.g., Near window, Corner table, etc.">{{ old('notes', $table->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex gap-4 pt-6">
                            <button type="submit" 
                                    class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 px-6 border-4 border-black hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200 text-xl">
                                UPDATE TABLE
                            </button>
                            <a href="{{ route('admin.path.tables.index', $currentRestaurant->slug) }}" 
                               class="flex-1 bg-gray-200 hover:bg-gray-300 text-black font-black py-4 px-6 border-4 border-black hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200 text-center text-xl">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
