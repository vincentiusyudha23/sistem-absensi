<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrasiRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegistrasiRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'divisi' => $request->divisi,
            'jurusan' => $request->jurusan,
            'nrp' => $request->nrp,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        $user->assignRole('user');

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
