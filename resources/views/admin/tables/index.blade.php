<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Tables - {{ $currentRestaurant->name ?? 'Restaurant' }} Admin</title>

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
                        <a href="{{ route('restaurant.admin.dashboard.path', $currentRestaurant->slug) }}" 
                           class="text-lg font-bold text-gray-900 hover:text-emerald-600">
                            ← Dashboard
                        </a>
                        <h1 class="text-2xl font-black text-black">Restaurant Tables</h1>
                    </div>
                    <div class="flex items-center">
                        <a href="{{ route('admin.path.tables.create', $currentRestaurant->slug) }}" 
                           class="bg-emerald-600 hover:bg-emerald-700 text-white font-black py-2 px-4 border-2 border-black hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                            + Add Table
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white border-4 border-black p-6">
                    <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-2">Total Tables</p>
                    <p class="text-4xl font-black text-black">{{ $stats['total_tables'] }}</p>
                </div>
                <div class="bg-white border-4 border-black p-6">
                    <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-2">Available</p>
                    <p class="text-4xl font-black text-green-600">{{ $stats['available'] }}</p>
                </div>
                <div class="bg-white border-4 border-black p-6">
                    <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-2">Occupied</p>
                    <p class="text-4xl font-black text-red-600">{{ $stats['occupied'] }}</p>
                </div>
                <div class="bg-white border-4 border-black p-6">
                    <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-2">Reserved</p>
                    <p class="text-4xl font-black text-blue-600">{{ $stats['reserved'] }}</p>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-2 border-green-600">
                    <p class="text-sm font-bold text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if($tables->count() > 0)
                <!-- Tables Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($tables as $table)
                        <div class="bg-white border-4 border-black p-6 hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                            <!-- Table Number -->
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-3xl font-black text-black">{{ $table->table_number }}</h3>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black border-2 
                                    @if($table->status === 'available')
                                        bg-green-100 text-green-800 border-green-600
                                    @elseif($table->status === 'occupied')
                                        bg-red-100 text-red-800 border-red-600
                                    @elseif($table->status === 'reserved')
                                        bg-blue-100 text-blue-800 border-blue-600
                                    @else
                                        bg-gray-100 text-gray-800 border-gray-600
                                    @endif
                                ">
                                    {{ ucfirst($table->status) }}
                                </span>
                            </div>

                            <!-- Capacity -->
                            <div class="mb-4">
                                <p class="text-sm font-bold text-gray-600">Capacity</p>
                                <p class="text-2xl font-black text-black">{{ $table->capacity }} Seats</p>
                            </div>

                            <!-- Notes -->
                            @if($table->notes)
                                <div class="mb-4 p-3 bg-gray-50 border-2 border-gray-300">
                                    <p class="text-xs font-bold text-gray-600 uppercase mb-1">Notes</p>
                                    <p class="text-sm text-gray-700">{{ $table->notes }}</p>
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.path.tables.edit', [$currentRestaurant->slug, $table]) }}" 
                                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-black py-2 px-3 border-2 border-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all duration-200 text-center">
                                    Edit
                                </a>

                                <a href="{{ route('admin.path.tables.qr', [$currentRestaurant->slug, $table]) }}" 
                                   class="flex-1 bg-purple-600 hover:bg-purple-700 text-white text-xs font-black py-2 px-3 border-2 border-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all duration-200 text-center">
                                    QR Code
                                </a>

                                <form action="{{ route('admin.path.tables.destroy', [$currentRestaurant->slug, $table]) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this table?')"
                                      class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full bg-red-600 hover:bg-red-700 text-white text-xs font-black py-2 px-3 border-2 border-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                                        Delete
                                    </button>
                                </form>
                            </div>

                            <!-- Status Change -->
                            <form action="{{ route('admin.path.tables.change-status', [$currentRestaurant->slug, $table]) }}" 
                                  method="POST" class="mt-4">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="w-full px-3 py-2 border-2 border-black text-sm font-bold">
                                    <option value="available" {{ $table->status === 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="occupied" {{ $table->status === 'occupied' ? 'selected' : '' }}>Occupied</option>
                                    <option value="reserved" {{ $table->status === 'reserved' ? 'selected' : '' }}>Reserved</option>
                                    <option value="maintenance" {{ $table->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                                <button type="submit" class="w-full mt-2 bg-gray-600 hover:bg-gray-700 text-white text-xs font-black py-2 px-3 border-2 border-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                                    Update Status
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="bg-white border-2 border-black p-8 inline-block hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                        <div class="text-6xl mb-4">TABLE</div>
                        <h3 class="text-xl font-black text-black mb-2">No Tables Yet</h3>
                        <p class="text-gray-600 mb-6">Start managing your restaurant by creating your first table.</p>
                        <a href="{{ route('admin.path.tables.create', $currentRestaurant->slug) }}" 
                           class="bg-emerald-600 hover:bg-emerald-700 text-white font-black py-3 px-6 border-2 border-black hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                            Create First Table
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
