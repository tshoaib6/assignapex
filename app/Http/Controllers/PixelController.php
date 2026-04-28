<?php

namespace App\Http\Controllers;

use App\Http\Requests\PixelStoreRequest;
use App\Http\Requests\PixelUpdateRequest;
use App\Models\Pixel;
use Illuminate\Http\Request;

class PixelController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q', ''));
        $pixels = Pixel::query()
            ->when($q, fn($qry) => $qry->where('grid_id','like',"%{$q}%")
                ->orWhere('region','like',"%{$q}%"))
            ->orderBy('region')
            ->orderBy('grid_id')
            ->paginate(20)
            ->withQueryString();

        return view('pixels.index', compact('pixels','q'));
    }

    public function create() { return view('pixels.create'); }

    public function store(PixelStoreRequest $request)
    {
        Pixel::create($request->validated());
        return redirect()->route('pixels.index')->with('notify', [['success','Pixel created.']]);
    }

    public function show(Pixel $pixel) { return view('pixels.show', compact('pixel')); }

    public function edit(Pixel $pixel) { return view('pixels.edit', compact('pixel')); }

    public function update(PixelUpdateRequest $request, Pixel $pixel)
    {
        $pixel->update($request->validated());
        return redirect()->route('pixels.index')->with('notify', [['success','Pixel updated.']]);
    }

    public function destroy(Pixel $pixel)
    {
        $pixel->delete();
        return back()->with('notify', [['success','Pixel deleted.']]);
    }
}
