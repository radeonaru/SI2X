<?php

namespace App\Http\Controllers;

use App\DataTables\PendudukDataTable;
use App\Enums\GolDar as GolDar;
use App\Enums\StatusPernikahan as StatusPernikahan;
use App\Enums\JenisKelamin as JenisKelamin;
use App\Enums\Agama as Agama;
use App\Enums\Pekerjaan as Pekerjaan;
use App\Enums\Pendidikan as Pendidikan;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\Rt;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class PendudukController extends Controller
{
    public function list(PendudukDataTable $dataTable)
    {
        $no_rts = Rt::pluck('no_rt');
        $nkks = Keluarga::pluck('nkk');
        $goldar = GolDar::cases();
        $sp = StatusPernikahan::cases();
        $agamas = Agama::cases();
        $jk = JenisKelamin::cases();
        $pekerjaans = Pekerjaan::cases();
        $pendidikans = Pendidikan::cases();

        return $dataTable->render('auth.rw.penduduk', compact('no_rts', 'nkks', 'goldar', 'pekerjaans', 'pendidikans', 'sp','jk','agamas'));
        // return $dataTable->render('auth.rw.penduduk', ['goldar' => GolDar::cases()]);
    }
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nik' => 'required|string|min:15|max:17|unique:penduduk,nik',
            'nkk' => 'required|string|min:15|max:17',
            'no_rt' => 'required|string|max:2',
            'nama' => 'required|string|max:49',
            'tempat_lahir' => 'required|string|min:2|max:49',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|min:5',
            'jenis_kelamin' => [Rule::enum(JenisKelamin::class)],
            'agama' => [Rule::enum(Agama::class)],
            'pendidikan' => [Rule::enum(Pendidikan::class)],
            'pekerjaan' => [Rule::enum(Pekerjaan::class)],
            // 'golongan_darah' => 'required|string|max:2',
            'golongan_darah' =>  [Rule::enum(GolDar::class)],
            'status_pernikahan' => [Rule::enum(StatusPernikahan::class)],
            'status_pendatang' => 'required',
            // Tambahkan validasi untuk input lainnya jika diperlukan
        ]);

        try {
            Penduduk::create([
                'nik' => $validated['nik'],
                'nkk' => $validated['nkk'],
                'no_rt' => $validated['no_rt'],
                'nama' => $validated['nama'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'alamat' => $validated['alamat'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'agama' => $validated['agama'],
                'pendidikan' => $validated['pendidikan'],
                'pekerjaan' => $validated['pekerjaan'],
                'golongan_darah' => $validated['golongan_darah'],
                'status_pernikahan' => $validated['status_pernikahan'],
                'status_pendatang' => $validated['status_pendatang'],
            ]);
            return redirect()->back()->with('success', 'Data Penduduk berhasil ditambahkan!');
        } catch (\Exception $e) {
            Alert::error('Error', $e->getMessage());
            return redirect()->back();
        }
    }
    public function edit(Penduduk $penduduk)
    {
        $penduduk = Penduduk::findOrFail($penduduk->nik);
        return view('penduduk.edit', compact('penduduk'));
    }
    public function update(Request $request, Penduduk $penduduk)
    {

        $request->validate([
            'nik' => 'required|string|min:15|max:17|unique:penduduk,nik,' . $penduduk->nik. ',nik', // gbisa edit primary key
            'nkk' => 'required|string|min:15|max:17',
            'no_rt' => 'required|string|max:2',
            'nama' => 'required|string|max:49',
            'tempat_lahir' => 'required|string|min:2|max:49',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|min:5',
            'jenis_kelamin' => [Rule::enum(JenisKelamin::class)],
            'agama' => [Rule::enum(Agama::class)],
            'pendidikan' => [Rule::enum(Pendidikan::class)],
            'pekerjaan' => [Rule::enum(Pekerjaan::class)],
            // 'golongan_darah' => 'required|string|max:2',
            'golongan_darah' =>  [Rule::enum(GolDar::class)],
            'status_pernikahan' => [Rule::enum(StatusPernikahan::class)],
            'status_pendatang' => 'required',
        ]);

        $penduduk->update($request->all());

        return redirect()->route('penduduk.manage')
            ->with('success', 'Penduduk berhasil diperbarui.');
    }

    public function destroy(Penduduk $penduduk)
    {
        $penduduk->delete();

        return redirect()->back()
            ->with('success', 'Penduduk berhasil dihapus.');
    }
}
