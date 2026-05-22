<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TableController extends Controller
{
    /**
     * Display a listing of tables
     */
    public function index()
    {
        $tables = Table::where('restaurant_id', auth()->user()->restaurant_id)
            ->orderBy('table_number')
            ->get();

        $stats = [
            'total_tables' => $tables->count(),
            'available' => $tables->where('status', 'available')->count(),
            'occupied' => $tables->where('status', 'occupied')->count(),
            'reserved' => $tables->where('status', 'reserved')->count(),
        ];

        return view('admin.tables.index', compact('tables', 'stats'));
    }

    /**
     * Show the form for creating a new table
     */
    public function create()
    {
        return view('admin.tables.create');
    }

    /**
     * Store a newly created table
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1|max:20',
            'notes' => 'nullable|string|max:500',
        ]);

        // Security: Automatically set restaurant_id
        $validated['restaurant_id'] = auth()->user()->restaurant_id;
        $validated['status'] = 'available';

        Table::create($validated);

        return redirect()
            ->route('admin.path.tables.index', auth()->user()->restaurant->slug)
            ->with('success', 'Table created successfully!');
    }

    /**
     * Show the form for editing the specified table
     */
    public function edit(Table $table)
    {
        $this->authorizeRestaurant($table);
        return view('admin.tables.edit', compact('table'));
    }

    /**
     * Update the specified table
     */
    public function update(Request $request, Table $table)
    {
        $this->authorizeRestaurant($table);

        $validated = $request->validate([
            'table_number' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1|max:20',
            'notes' => 'nullable|string|max:500',
        ]);

        // Security: Ensure restaurant_id doesn't change
        $validated['restaurant_id'] = auth()->user()->restaurant_id;

        $table->update($validated);

        return redirect()
            ->route('admin.path.tables.index', auth()->user()->restaurant->slug)
            ->with('success', 'Table updated successfully!');
    }

    /**
     * Remove the specified table
     */
    public function destroy(Table $table)
    {
        $this->authorizeRestaurant($table);

        $table->delete();

        return redirect()
            ->route('admin.path.tables.index', auth()->user()->restaurant->slug)
            ->with('success', 'Table deleted successfully!');
    }

    /**
     * Change table status
     */
    public function changeStatus(Table $table, Request $request)
    {
        $this->authorizeRestaurant($table);

        $validated = $request->validate([
            'status' => 'required|in:available,occupied,reserved,maintenance',
        ]);

        $table->update([
            'status' => $validated['status'],
            'restaurant_id' => auth()->user()->restaurant_id, // Security
        ]);

        return redirect()
            ->route('admin.path.tables.index', auth()->user()->restaurant->slug)
            ->with('success', "Table status changed to {$validated['status']}!");
    }

    /**
     * Generate QR code for table
     */
    public function generateQR(Table $table)
    {
        $this->authorizeRestaurant($table);

        // Generate QR code URL for ordering
        $qrUrl = route('restaurant.menu.path', auth()->user()->restaurant->slug);
        
        // Add table parameter for tracking
        $qrUrl .= '?table=' . urlencode($table->table_number);

        return view('admin.tables.qr', compact('table', 'qrUrl'));
    }

    /**
     * Authorize that table belongs to user's restaurant
     */
    private function authorizeRestaurant(Table $table)
    {
        if ($table->restaurant_id !== auth()->user()->restaurant_id) {
            abort(403, 'Unauthorized access to this table.');
        }
    }
}
