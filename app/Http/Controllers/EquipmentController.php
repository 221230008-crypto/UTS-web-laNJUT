<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        return response()->json(Equipment::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'quantity' => 'required|integer',
            'status' => 'required'
        ]);

        $equipment = Equipment::create($request->all());
        return response()->json(['success' => true, 'data' => $equipment]);
    }

    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->update($request->all());
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Equipment::destroy($id);
        return response()->json(['success' => true]);
    }
}