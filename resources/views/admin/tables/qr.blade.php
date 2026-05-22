<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>QR Code - Table {{ $table->table_number }} - {{ $currentRestaurant->name ?? 'Restaurant' }} Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
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
                        <h1 class="text-2xl font-black text-black">QR Code - Table {{ $table->table_number }}</h1>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="bg-white border-4 border-black p-8">
                <!-- QR Code Container -->
                <div class="text-center mb-8">
                    <div id="qrcode" class="flex justify-center mb-6"></div>
                    <p class="text-lg font-black text-black mb-4">Table {{ $table->table_number }}</p>
                    <p class="text-sm text-gray-600 mb-6">Scan this QR code to view the menu and place orders</p>
                </div>

                <!-- Table Info -->
                <div class="bg-gray-50 border-2 border-black p-6 mb-8">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-black text-gray-600 uppercase mb-1">Table Number</p>
                            <p class="text-2xl font-black text-black">{{ $table->table_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-black text-gray-600 uppercase mb-1">Capacity</p>
                            <p class="text-2xl font-black text-black">{{ $table->capacity }} Seats</p>
                        </div>
                    </div>
                </div>

                <!-- QR URL -->
                <div class="bg-gray-50 border-2 border-black p-6 mb-8">
                    <p class="text-xs font-black text-gray-600 uppercase mb-2">QR Code URL</p>
                    <p class="text-sm font-mono text-gray-700 break-all">{{ $qrUrl }}</p>
                </div>

                <!-- Actions -->
                <div class="flex gap-4">
                    <button onclick="window.print()" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-black py-4 px-6 border-4 border-black hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                        Print QR Code
                    </button>
                    <a href="{{ route('admin.path.tables.index', $currentRestaurant->slug) }}" 
                       class="flex-1 bg-gray-200 hover:bg-gray-300 text-black font-black py-4 px-6 border-4 border-black hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200 text-center">
                        Back to Tables
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Generate QR code
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $qrUrl }}",
            width: 300,
            height: 300,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    </script>
</body>
</html>
