<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PermohonanKonseling;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermohonanKonselingPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->role === 'siswa';
    }

    public function update(User $user)
    {
        return $user->role === 'guru' && $user->guru && $user->guru->role_guru === 'bk';
    }
}
