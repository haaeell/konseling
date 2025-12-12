<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ManajemenUserController extends Controller
{
    public function index(Request $request)
    {
        abort_if(
            auth()->user()->role !== 'guru' ||
                auth()->user()->guru->role_guru !== 'bk',
            403
        );

        $query = User::with(['siswa', 'orangtua', 'guru'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->get();

        return view('manajemen-user.index', compact('users'));
    }

    public function resetPassword(User $user)
    {
        abort_if(
            auth()->user()->role !== 'guru' ||
                auth()->user()->guru->role_guru !== 'bk',
            403
        );

        $plainPassword = 'Pass12345';

        if ($user->role === 'siswa' && $user->siswa && $user->siswa->nis) {
            $plainPassword = 'Pass' . $user->siswa->nis;
        }

        $user->update([
            'password' => Hash::make($plainPassword),
        ]);

        return back()->with(
            'success',
            "Password untuk {$user->name} berhasil direset.
            Password default: {$plainPassword}"
        );
    }
}
