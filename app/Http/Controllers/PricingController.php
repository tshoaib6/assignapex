<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pricing;
class PricingController extends Controller
{
    //
   public function index()
{
    $pricing = Pricing::firstOrCreate(
        ['id' => 1], 
        [
            'unit_cost_driver_test' => 21.65,
            'unit_cost_walk_test' => 1168.00,
        ]
    );

    return view('pricing', compact('pricing'));
}


   public function update(Request $request, $id)
{
    $request->validate([
        'unit_cost_driver_test' => 'nullable|numeric|min:0',
        'unit_cost_walk_test'    => 'nullable|numeric|min:0',
    ]);

    $pricing = Pricing::findOrFail($id);
    $pricing->update($request->only(['unit_cost_driver_test', 'unit_cost_walk_test']));

    return redirect()->back()->with('success', 'Pricing updated successfully!');
}

}
