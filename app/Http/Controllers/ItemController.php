<?php

namespace App\Http\Controllers;

use App\Exports\ItemExport;
use App\Models\Item;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('items.index', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|integer',
        ]);

        Item::create($validated);

        return response()->json(['message' => 'Item created successfully']);
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json(['message' => 'Item deleted successfully']);
    }

    public function export(ItemExport $item)
    {
        $response['items'] = Item::all();
        return Excel::download($item->export($response), 'items.xlsx');
    }
}
