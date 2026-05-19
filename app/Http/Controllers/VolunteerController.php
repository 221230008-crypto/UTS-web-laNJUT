<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    public function index()
    {
        return response()->json(Volunteer::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'role' => 'required',
            'phone' => 'required',
            'blood_type' => 'required',
            'join_date' => 'required'
        ]);

        $volunteer = Volunteer::create($request->all());
        return response()->json(['success' => true, 'data' => $volunteer]);
    }

    public function update(Request $request, $id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $volunteer->update($request->all());
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Volunteer::destroy($id);
        return response()->json(['success' => true]);
    }
}