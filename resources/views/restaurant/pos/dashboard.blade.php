@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen" x-data="posApp()" x-init="init()">
    <!-- Header -->
    <div class="bg-black text-white p-8 border-b-4 border-black">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-5xl font-black mb-2">Waiter POS</h1>
            <p class="text-lg font-bold">{{ $currentRestaurant->name }}</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto p-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Table Matrix Section -->
            <div class="lg:col-span-2">
                <h2 class="text-4xl font-black text-black mb-8">Active Tables</h2>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <template x-for="table in tables" :key="table.number">
                        <div class="bg-white border-4 border-black p-6 hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200 cursor-pointer" @click="selectTable(table.number)">
                            <div class="text-center">
                                <p class="text-sm font-black text-gray-600 uppercase mb-2">Table</p>
                                <p class="text-5xl font-black text-black mb-4" x-text="table.number"></p>
                                
                                <template x-if="table.activeOrder">
                                    <div>
                                        <p class="text-xs font-black text-gray-600 uppercase mb-2">Bill Total</p>
                                        <p class="text-2xl font-black text-black mb-4">Rs <span x-text="table.activeOrder.total_price.toFixed(2)"></span></p>
                                        
                                        <div class="inline-block px-3 py-1 border-2 border-black font-black text-xs uppercase" :class="getStatusColor(table.activeOrder.status)" x-text="table.activeOrder.status"></div>
                                    </div>
                                </template>
                                
                                <template x-if="!table.activeOrder">
                                    <p class="text-sm font-bold text-gray-500">No active order</p>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Order Ticket Stream Section -->
            <div class="lg:col-span-1">
                <h2 class="text-4xl font-black text-black mb-8">Order Stream</h2>
                
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    <template x-for="order in orders" :key="order.id">
                        <div class="bg-white border-4 border-black p-4">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="text-sm font-black text-gray-600 uppercase">Table</p>
                                    <p class="text-2xl font-black text-black" x-text="order.table_number || 'N/A'"></p>
                                </div>
                                <div class="inline-block px-2 py-1 border-2 border-black font-black text-xs uppercase" :class="getStatusColor(order.status)" x-text="order.status"></div>
                            </div>
                            
                            <p class="text-xs font-bold text-gray-600 mb-3">Rs <span x-text="order.total_price.toFixed(2)"></span></p>
                            
                            <template x-if="order.status === 'ready'">
                                <form method="POST" :action="`/{{ $currentRestaurant->slug }}/orders/${order.id}/completed`" class="w-full">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full px-3 py-2 bg-emerald-600 border-2 border-black text-white font-black text-xs uppercase hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                                        Delivered
                                    </button>
                                </form>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function posApp() {
        return {
            tables: [],
            orders: [],
            selectedTable: null,

            init() {
                this.loadOrders();
                this.generateTables();
                // Refresh orders every 5 seconds
                setInterval(() => this.loadOrders(), 5000);
            },

            generateTables() {
                // Generate 12 tables
                for (let i = 1; i <= 12; i++) {
                    this.tables.push({
                        number: i,
                        activeOrder: null
                    });
                }
            },

            loadOrders() {
                fetch(`/{{ $currentRestaurant->slug }}/orders`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.orders = data.orders.filter(order => order.status !== 'completed');
                            this.updateTableStatus();
                        }
                    })
                    .catch(error => console.error('Error loading orders:', error));
            },

            updateTableStatus() {
                this.tables.forEach(table => {
                    const order = this.orders.find(o => o.table_number == table.number);
                    table.activeOrder = order || null;
                });
            },

            selectTable(tableNumber) {
                this.selectedTable = tableNumber;
            },

            getStatusColor(status) {
                const colors = {
                    'pending': 'bg-yellow-100 text-yellow-800',
                    'preparing': 'bg-blue-100 text-blue-800',
                    'ready': 'bg-emerald-100 text-emerald-800',
                    'completed': 'bg-gray-100 text-gray-800',
                    'cancelled': 'bg-red-100 text-red-800'
                };
                return colors[status] || 'bg-gray-100 text-gray-800';
            }
        }
    }
</script>
@endsection
