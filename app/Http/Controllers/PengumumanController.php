<?php

namespace App\Http\Controllers;

use App\DataTables\PengumumanDataTable;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumumans = Pengumuman::all();
        return view('global.pengumuman')->with('pengumumans', $pengumumans);
        // $pengumumans = Pengumuman::all();
        // return view('auth.rw.pengumuman', compact('pengumumans'));
    }
    
    public function list(PengumumanDataTable $dataTable) {
        return $dataTable->render('auth.rw.pengumuman');
    }

    // public function create()
    // {
    //     return view('auth.rw.pengumuman.create');
    // }

    public function store(Request $request)
    {
        // Validasi data input dari form
        $validated = $request->validate([
            'nama_pengumuman' => 'required',
            'desc_pengumuman' => 'required',
            'tanggal_pengumuman' => 'required',
        ]);
        
        try {
            Pengumuman::create([
                'judul' => $validated['nama_pengumuman'],
                'deskripsi' => $validated['desc_pengumuman'],
                'tanggal' => $validated['tanggal_pengumuman'],
                'foto' => $request->foto_pengumuman,
            ]);
            return redirect()->back()->with('success', 'Pengumuman berhasil dipublish!');
        } catch (\Exception $e) {
            Alert::error('Oops!', $e->getMessage());
            return redirect()->back();
        }

        // $pengumuman = new Pengumuman();
        // $pengumuman->judul = $request->nama_pengumuman;
        // $pengumuman->deskripsi = $request->desc_pengumuman;
        // $pengumuman->tanggal = $request->tanggal_pengumuman;
        // $pengumuman->foto = $request->foto_pengumuman;
        // $pengumuman->save();

        // return redirect()->back()->with('success', 'Pengumuman berhasil dipublish!');
    }

    public function edit(Pengumuman $pengumuman)
    {
        return view('pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'tanggal_pengumuman' => 'required',
        ]);

        $pengumuman->update($request->all());

        return redirect()->route('pengumuman.manage')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();

        return redirect()->back()
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
