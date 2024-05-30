<?php

namespace App\Http\Controllers;

use App\DataTables\PengajuanDokumenDataTable;
use App\Models\Dokumen;
use App\Models\Penduduk;
use App\Models\PengajuanDokumen;
use App\Models\RT;
use App\Models\RW;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PengajuanDokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // Mengambil hanya kolom 'nik' dari model Penduduk
        $pengajuanDokumens = PengajuanDokumen::all();
        $no_rts = RT::pluck('no_rt');
        // $penduduks = Penduduk::all();
        $dokumens = Dokumen::all();

        // Mengirimkan data ke view
        return view('global.pengajuandokumen', compact('pengajuanDokumens', 'no_rts', 'dokumens'));
    }


    public function list(PengajuanDokumenDataTable $dataTable)
    {
        return $dataTable->render('auth.rt.pengajuandokumen');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // Validasi input

        $validated = $request->validate([
            // 'id_pengajuandokumen' => 'required', // (tidak bisa mengedit id as primary key, cek view)
            // 'no_rt' => 'required|max:2',
            'id_dokumen' => 'required',
            'nik_pemohon' => 'required|min:15|max:17|exists:penduduk,nik',
            'keperluan' => 'required|min:10',
            // 'nama_pemohon' => 'required|min:3|max:49',
            // 'no_rw' => 'string',
            // 'status_umkm' => 'string',
        ], [
            // 'no_rt.required' => 'Nomor RT wajib diisi.',
            // 'no_rt.max' => 'Nomor RT tidak boleh lebih dari :max karakter.',
            'id_dokumen.required' => 'Jenis Dokumen wajib dipilih.',
            'nik_pemohon.required' => 'NIK Pengaju wajib diisi.',
            'keperluan.required' => 'Keperluan Wajib diisi',
            'keperluan.min' => 'Keperluan harus memiliki panjang minimal :min karakter.',
            'nik_pemohon.min' => 'NIK Pengaju harus memiliki panjang minimal :min karakter.',
            'nik_pemohon.max' => 'NIK Pengaju harus memiliki panjang maksimal :max karakter.',
            'nik_pemohon.exists' => 'NIK Anda Tidak Terdata, silahkan hubungi Ketua RT atau Ketua RW anda untuk keperluan kelengkapan data kependudukan di Sistem Informasi Rukun Warga ini',
            // 'nama_pemohon.required' => 'Nama Pengaju wajib diisi.',
            // 'nama_pemohon.min' => 'Nama Pengaju harus memiliki panjang minimal :min karakter.',
            // 'nama_pemohon.max' => 'Nama Pengaju harus memiliki panjang maksimal :max karakter.',
        ]);

        $rt = Penduduk::where('nik', $validated['nik_pemohon'])->first();
        
        // Cek apakah ada pengajuan dokumen dengan nik_pemohon yang sama dan status "Baru"
        $existingPengajuan = PengajuanDokumen::where('nik_pemohon', $validated['nik_pemohon'])
            ->where('status_pengajuan', 'Baru')
            ->first();

        if ($existingPengajuan) {
            $wa_rt = RT::where('no_rt', $rt->no_rt)->first();
            Alert::error('Permintaan Dokumen sebelumnya belum diproses!', 'Silahkan tunggu permintaan dokumen yang anda ajukan sebelumnya diproses oleh Ketua RT atau hubungi Ketua RT melalui nomor ' . $wa_rt->wa_rt);
            return redirect()->back();
        }

        $pengaju = Penduduk::where('nik', $validated['nik_pemohon'])->first();

        try {
            PengajuanDokumen::create([
                // 'id_pengajuandokumen' => $validated['id_pengajuandokumen'],
                'no_rt' => $rt->no_rt,
                'id_dokumen' => $validated['id_dokumen'],
                'nik_pemohon' => $validated['nik_pemohon'],
                'nama_pemohon' => $pengaju->nama,
                'keperluan' => '',
                'status_pengajuan' => 'Baru',
                'catatan' => '',
            ]);
            Alert::success('Permintaan Dokumen berhasil diajukan!');
            return redirect()->back()->with('warning', 'Status Permintaan Dokumen yang anda ajukan akan tampil pada halaman ini jika sudah melalui proses validasi oleh Ketua RT');
        } catch (\Illuminate\Database\QueryException $e) {
            $rw = RW::all()->first();
            $rt = RT::where('no_rt', $rt->no_rt)->first();
            Alert::error('NIK Anda Tidak Terdata!', 'Silahkan hubungi RT anda untuk keperluan kelengkapan data kependudukan di Sistem Informasi Rukun Warga ini melalui nomor ' . $rt->wa_rt . ', atau hubungi RW melalui nomor ' . $rw->wa_rw);
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Oops!', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PengajuanDokumen $pengajuandokumen)
    {
        $pengajuandokumen = PengajuanDokumen::findOrFail($pengajuandokumen->id_pengajuandokumen);
        return view('pengajuandokumen.edit', compact('pengajuandokumen'));
    }

    public function update(Request $request, PengajuanDokumen $pengajuandokumen)
    {

        $request->validate([
            // 'id_pengajuandokumen' => 'required',
            // 'id_dokumen' => 'required',
            // 'no_rt' => 'required',
            // 'nik_pemohon' => 'required',
            // 'nama_pemohon' => 'required',
            // 'desc_umkm' => 'required',
            'status_pengajuan' => 'required|max:10',
            'catatan' => 'required',
        ], [
            'status_pengajuan.required' => 'Status Pengajuan wajib diisi.',
            'status_pengajuan.max' => 'Status Pengajuan tidak boleh lebih dari :max karakter.',
            'catatan.required' => 'Catatan wajib diisi.',
        ]);

        $pengajuandokumen->update($request->all());

        return redirect()->route('pengajuandokumen.manage')
            ->with('success', 'Status Permintaan Dokumen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_pengajuandokumen)
    {
        $pengajuandokumen = PengajuanDokumen::findOrFail($id_pengajuandokumen);
        $pengajuandokumen->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}