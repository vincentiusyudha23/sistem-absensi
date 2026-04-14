<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
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
        return view('admin.data-absensi');
    }
}
