@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen" x-data="kdsApp()" x-init="init()">
    <!-- Header -->
    <div class="bg-black text-white p-8 border-b-4 border-black">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-5xl font-black mb-2">Kitchen Display System</h1>
            <p class="text-lg font-bold">{{ $currentRestaurant->name }}</p>
        </div>
    </div>

    <!-- Main Content - 3 Column Kanban -->
    <div class="max-w-7xl mx-auto p-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Column 1: New Orders (Pending) -->
            <div>
                <div class="bg-black text-white p-6 border-4 border-black mb-6">
                    <h2 class="text-3xl font-black">New Orders</h2>
                    <p class="text-sm font-bold mt-2" x-text="`${pendingOrders.length} orders`"></p>
                </div>

                <div class="space-y-4">
                    <template x-for="order in pendingOrders" :key="order.id">
                        <div class="bg-yellow-50 border-4 border-black p-6 hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                            <!-- Order Header -->
                            <div class="mb-4">
                                <p class="text-sm font-black text-gray-600 uppercase mb-1">Table</p>
                                <p class="text-4xl font-black text-black" x-text="order.table_number || 'N/A'"></p>
                            </div>

                            <!-- Time Duration -->
                            <p class="text-xs font-bold text-gray-600 mb-4" x-text="getTimeDuration(order.created_at)"></p>

                            <!-- Order Items -->
                            <div class="bg-white border-2 border-black p-4 mb-4">
                                <template x-for="item in order.items" :key="item.id">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-300 last:border-b-0">
                                        <div>
                                            <p class="font-black text-black" x-text="item.name"></p>
                                            <p class="text-xs font-bold text-gray-600" x-text="`Rs ${item.price.toFixed(2)}`"></p>
                                        </div>
                                        <p class="text-2xl font-black text-black" x-text="item.quantity"></p>
                                    </div>
                                </template>
                            </div>

                            <!-- Action Button -->
                            <form method="POST" :action="`/{{ $currentRestaurant->slug }}/orders/${order.id}/preparing`" class="w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full px-4 py-3 bg-blue-600 border-2 border-black text-white font-black text-sm uppercase hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                                    Start Cooking
                                </button>
                            </form>
                        </div>
                    </template>

                    <template x-if="pendingOrders.length === 0">
                        <div class="bg-gray-50 border-4 border-black p-8 text-center">
                            <p class="text-lg font-black text-gray-600">No new orders</p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Column 2: In Preparation -->
            <div>
                <div class="bg-black text-white p-6 border-4 border-black mb-6">
                    <h2 class="text-3xl font-black">In Preparation</h2>
                    <p class="text-sm font-bold mt-2" x-text="`${preparingOrders.length} orders`"></p>
                </div>

                <div class="space-y-4">
                    <template x-for="order in preparingOrders" :key="order.id">
                        <div class="bg-blue-50 border-4 border-black p-6 hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                            <!-- Order Header -->
                            <div class="mb-4">
                                <p class="text-sm font-black text-gray-600 uppercase mb-1">Table</p>
                                <p class="text-4xl font-black text-black" x-text="order.table_number || 'N/A'"></p>
                            </div>

                            <!-- Time Duration -->
                            <p class="text-xs font-bold text-gray-600 mb-4" x-text="getTimeDuration(order.created_at)"></p>

                            <!-- Order Items -->
                            <div class="bg-white border-2 border-black p-4 mb-4">
                                <template x-for="item in order.items" :key="item.id">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-300 last:border-b-0">
                                        <div>
                                            <p class="font-black text-black" x-text="item.name"></p>
                                            <p class="text-xs font-bold text-gray-600" x-text="`Rs ${item.price.toFixed(2)}`"></p>
                                        </div>
                                        <p class="text-2xl font-black text-black" x-text="item.quantity"></p>
                                    </div>
                                </template>
                            </div>

                            <!-- Action Button -->
                            <form method="POST" :action="`/{{ $currentRestaurant->slug }}/orders/${order.id}/ready`" class="w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full px-4 py-3 bg-emerald-600 border-2 border-black text-white font-black text-sm uppercase hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                                    Mark as Ready
                                </button>
                            </form>
                        </div>
                    </template>

                    <template x-if="preparingOrders.length === 0">
                        <div class="bg-gray-50 border-4 border-black p-8 text-center">
                            <p class="text-lg font-black text-gray-600">No orders in preparation</p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Column 3: Food Ready -->
            <div>
                <div class="bg-black text-white p-6 border-4 border-black mb-6">
                    <h2 class="text-3xl font-black">Food Ready</h2>
                    <p class="text-sm font-bold mt-2" x-text="`${readyOrders.length} orders`"></p>
                </div>

                <div class="space-y-4">
                    <template x-for="order in readyOrders" :key="order.id">
                        <div class="bg-emerald-50 border-4 border-black p-6 hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                            <!-- Order Header -->
                            <div class="mb-4">
                                <p class="text-sm font-black text-gray-600 uppercase mb-1">Table</p>
                                <p class="text-4xl font-black text-black" x-text="order.table_number || 'N/A'"></p>
                            </div>

                            <!-- Time Duration -->
                            <p class="text-xs font-bold text-gray-600 mb-4" x-text="getTimeDuration(order.created_at)"></p>

                            <!-- Order Items -->
                            <div class="bg-white border-2 border-black p-4">
                                <template x-for="item in order.items" :key="item.id">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-300 last:border-b-0">
                                        <div>
                                            <p class="font-black text-black" x-text="item.name"></p>
                                            <p class="text-xs font-bold text-gray-600" x-text="`Rs ${item.price.toFixed(2)}`"></p>
                                        </div>
                                        <p class="text-2xl font-black text-black" x-text="item.quantity"></p>
                                    </div>
                                </template>
                            </div>

                            <p class="text-xs font-bold text-emerald-700 mt-4 text-center">Awaiting pickup</p>
                        </div>
                    </template>

                    <template x-if="readyOrders.length === 0">
                        <div class="bg-gray-50 border-4 border-black p-8 text-center">
                            <p class="text-lg font-black text-gray-600">No orders ready</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function kdsApp() {
        return {
            orders: [],
            pendingOrders: [],
            preparingOrders: [],
            readyOrders: [],

            init() {
                this.loadOrders();
                // Refresh orders every 3 seconds for real-time updates
                setInterval(() => this.loadOrders(), 3000);
            },

            loadOrders() {
                fetch(`/{{ $currentRestaurant->slug }}/orders`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.orders = data.orders;
                            this.categorizeOrders();
                        }
                    })
                    .catch(error => console.error('Error loading orders:', error));
            },

            categorizeOrders() {
                this.pendingOrders = this.orders.filter(o => o.status === 'pending');
                this.preparingOrders = this.orders.filter(o => o.status === 'preparing');
                this.readyOrders = this.orders.filter(o => o.status === 'ready');
            },

            getTimeDuration(createdAt) {
                const now = new Date();
                const created = new Date(createdAt);
                const diffMs = now - created;
                const diffMins = Math.floor(diffMs / 60000);
                
                if (diffMins < 1) return 'Just now';
                if (diffMins === 1) return '1 min ago';
                if (diffMins < 60) return `${diffMins} mins ago`;
                
                const diffHours = Math.floor(diffMins / 60);
                if (diffHours === 1) return '1 hour ago';
                return `${diffHours} hours ago`;
            }
        }
    }
</script>
@endsection
