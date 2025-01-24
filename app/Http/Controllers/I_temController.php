<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class I_temController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $items = $query->get();
        return view('items.index', compact('items'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
        ]);

        Item::create($request->all());
        return redirect()->route('items.index')->with('success', 'Item berhasil ditambahkan!');
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
        ]);

        $item->update($request->all());
        return redirect()->route('items.index')->with('success', 'Item berhasil diperbarui!');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item berhasil dihapus!');
    }
}
