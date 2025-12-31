<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use App\Models\Destinasi;

class DestinasiController extends BaseController
{
    public function index()
    {
        $list = Destinasi::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.destinasi.index', ['list' => $list]);
    }

    public function create()
    {
        return view('admin.destinasi.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'alamat' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'kategori' => 'nullable|string|max:100',
            'foto' => 'nullable|image|max:2048',
            'tiktok' => 'nullable|url',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('destinasi', 'public');
            $data['foto'] = '/storage/' . $path;
        }

        $dest = Destinasi::create($data);

        return redirect()->route('admin.destinasi.index')->with('success', 'Destinasi dibuat.');
    }

    public function show($id)
    {
        $item = Destinasi::findOrFail($id);
        return view('admin.destinasi.show', ['data' => $item]);
    }

    public function edit($id)
    {
        $item = Destinasi::findOrFail($id);
        return view('admin.destinasi.edit', ['data' => $item]);
    }

    public function update(Request $request, $id)
    {
        $item = Destinasi::findOrFail($id);
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'alamat' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'kategori' => 'nullable|string|max:100',
            'foto' => 'nullable|image|max:2048',
            'tiktok' => 'nullable|url',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        if ($request->hasFile('foto')) {
            // delete old file if stored on public disk
            if (!empty($item->foto) && str_starts_with($item->foto, '/storage/')) {
                $oldPath = ltrim(str_replace('/storage/', '', $item->foto), '/');
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('foto')->store('destinasi', 'public');
            $data['foto'] = '/storage/' . $path;
        }

        $item->update($data);

        return redirect()->route('admin.destinasi.index')->with('success', 'Destinasi diperbarui.');
    }

    public function destroy($id)
    {
        $item = Destinasi::findOrFail($id);
        if (!empty($item->foto) && str_starts_with($item->foto, '/storage/')) {
            $oldPath = ltrim(str_replace('/storage/', '', $item->foto), '/');
            Storage::disk('public')->delete($oldPath);
        }
        $item->delete();
        return redirect()->route('admin.destinasi.index')->with('success', 'Destinasi dihapus.');
    }
}
