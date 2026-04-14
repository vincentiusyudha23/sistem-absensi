<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $absens = Absensi::whereDate('created_at', Carbon::now())->get();
        return view('admin.dashboard', compact('absens'));
    }

    public function listUsers()
    {
        return view('admin.list-users');
    }

    public function tambahAnggota()
    {
        return view('admin.tambah-anggota');
    }

    public function storeAnggota(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nrp' => 'required|max:20|unique:users,nrp',
            'jabatan' => 'required|string',
            'divisi' => 'required|string',
        ], [
            'name.required' => 'Nama tidak boleh kosong.',
            'nrp.required' => 'NRP/NIP tidak boleh kosong.',
            'nrp.unique' => 'NRP/NIP sudah terdaftar.',
            'jabatan.required' => 'Jabatan tidak boleh kosong.',
            'divisi.required' => 'Divisi tidak boleh kosong.',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'nrp' => $request->nrp,
                'jabatan' => $request->jabatan,
                'divisi' => $request->divisi,
                'role' => 'user',
                'username' => $request->nrp,
                'password' => Hash::make($request->nrp),
            ]);

            $user->assignRole('user');

            DB::commit();

            return redirect()->route('admin.list_users')
                ->with('success', 'Anggota berhasil ditambahkan');

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan, gagal menambahkan anggota');
        }
    }

    public function editAnggota($id)
    {
        $user = User::find($id);
        return view('admin.edit-anggota', compact('user'));
    }

    public function updateAnggota(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nrp' => 'required|string|max:20|unique:users,nrp,' . $id,
            'jabatan' => 'required|string',
            'divisi' => 'required|string',
            'status' => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            $user->update([
                'name' => $request->name,
                'nrp' => $request->nrp,
                'jabatan' => $request->jabatan,
                'divisi' => $request->divisi,
                'status' => $request->status == 'on' ? true : false,
            ]);

            DB::commit();

            return back()->with('success', 'Data anggota berhasil diperbarui');

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data anggota');
        }
    }
    
    public function dataAbsensi()
    {
        $absens = Absensi::all();
        return view('admin.data-absensi', compact('absens'));
    }

    public function getNewAbsensis()
    {
        $absens = Absensi::whereDate('created_at', Carbon::now())
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function($absen){
                        return [
                            'id' => $absen->id,
                            'name' => $absen->user?->name,
                            'waktu_masuk' => Carbon::parse($absen->waktu_masuk)->format('H:i') . ' WIB',
                            'divisi' => $absen->user->divisi,
                            'status' => $absen->status,
                            'lat' => $absen->latitude1,
                            'lng' => $absen->longitude1
                        ];
                    })
                    ->toArray();

        return response()->json($absens);
    }

    public function getDetailAbsen($id)
    {
        $absen = Absensi::with('user')->findOrFail($id);
        $a     = $absen->user;

        $status = match($absen->status){
            1 => 'hadir',
            2 => 'terlambat',
            3 => 'tidak',
            default => ''
        };
 
        return response()->json([
            'id'        => $absen->id,
            'nama'      => $a->name       ?? '-',
            'nrp'       => $a->nrp        ?? '-',
            'divisi'    => $a->divisi     ?? '-',
            'tanggal'   => Carbon::parse($absen->created_at)->isoFormat('dddd, D MMMM Y'),
            'status'    => $status,
 
            // Waktu (format H:i)
            'check_in'  => $absen->waktu_masuk  ? Carbon::parse($absen->waktu_masuk)->format('H:i')  : null,
            'check_out' => $absen->waktu_keluar ? Carbon::parse($absen->waktu_keluar)->format('H:i') : null,
 
            // Foto (path relatif dari storage)
            'foto_in'   => $absen->image_masuk,
            'foto_out'  => $absen->image_keluar,
 
            // Alamat
            'alamat_in'  => $absen->address1,
            'alamat_out' => $absen->address2,
 
            // Koordinat
            'lat_in'   => $absen->latitude1,
            'lng_in'   => $absen->longitude1,
            'lat_out'  => $absen->latitude2,
            'lng_out'  => $absen->longitude2,
        ]);
    }
}
