<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Jobs\ProcessAbsensiImage;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $absensi = $user->absensi()->whereDate('created_at', Carbon::now())->first();

        return view('user.dashboard', compact('absensi', 'user'));
    }

    public function storeAbsen(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'address' => 'required',
            'image' => 'required',
        ]);

        try{
            $user = Auth::user();
            $timeAbsen = Carbon::now();

            $minAbsen = Carbon::today()->setTime(6, 0);
            $maxAbsen = Carbon::today()->setTime(22, 30);

            $radius = config('app.radius_absen');
            $homeLat = $user->latitude;
            $homeLng = $user->longitude;

            if($this->isOutsideRadius($request->latitude, $request->longitude, $homeLat, $homeLng, $radius)){
                return response()->json([
                    'success' => false,
                    'msg' => 'Anda berada di luar jangkauan'
                ], 422);
            }

            if($timeAbsen->lt($minAbsen) || $timeAbsen->gt($maxAbsen) || $timeAbsen->isWeekend()){
                return response()->json([
                    'success' => false,
                    'msg' => 'Saat ini anda tidak bisa melakukan absen.'
                ], 422);
            }

            $absen = Absensi::where('user_id', $user->id)
                        ->whereDate('created_at', $timeAbsen)
                        ->first();

            $type = 1;

            if($absen){
                $type = 2;
                $absen->update([
                    'waktu_keluar' => $timeAbsen,
                    'latitude2' => $request->latitude,
                    'longitude2' => $request->longitude,
                    'address2' => $request->address,
                ]);
            }else{
                $absen = Absensi::create([
                    'user_id' => $user->id,
                    'waktu_masuk' => Carbon::now(),
                    'latitude1' => $request->latitude,
                    'longitude1' => $request->longitude,
                    'address1' => $request->address,
                    'status' => $this->getStatusAbsen()
                ]);
            }

            ProcessAbsensiImage::dispatch($request->image, $absen->id, $type);

            return response()->json([
                'success' => true,
                'msg' => 'Berhasil Melakukan Absen'
            ], 200);
            
        } catch (\Exception $e){
            return response()->json([
                'success' => false,
                'msg' => $e->getMessage()
            ], 422);
        }
    }

    private function isOutsideRadius($userLat, $userLng, $centerLat, $centerLng, $radiusMeter)
    {
        $earthRadius = 6371000; // meter

        $dLat = deg2rad($centerLat - $userLat);
        $dLng = deg2rad($centerLng - $userLng);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($userLat)) * cos(deg2rad($centerLat)) *
            sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance > $radiusMeter;
    }

    private function getStatusAbsen()
    {
        $timeAbsen = Carbon::now();

        if($timeAbsen->gt(Carbon::today()->setTime(7, 0))){
            return 2;
        }

        return 1;
    }

    public function riwayatAbsen()
    {
        return view('user.riwayat-absen');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function updateProfile(ProfileUpdateRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'name' => $request->name,
            'nrp' => $request->nrp,
            'jabatan' => $request->jabatan,
            'divisi' => $request->divisi,
            'email' => $request->email
        ]);

        if($request->filled('password')){
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function getMyAddress()
    {
        $lat = request('lat');
        $lng = request('lng');

        $token = env('MAPBOX_TOKEN');

        $response = Http::get("https://api.mapbox.com/geocoding/v5/mapbox.places/{$lng},{$lat}.json", [
            'access_token' => $token,
            'language' => 'id',
            'limit' => 1,
        ]);

        $data = $response->json();

        return response()->json([
            'address' => $data['features'][0]['place_name'] ?? null
        ]);
    }
}
