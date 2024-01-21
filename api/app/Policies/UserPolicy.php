<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades;

class UserPolicy
{
    use HandlesAuthorization;
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, User $auth)
    {

        return $user->id !== $auth->id;
    }

    public function delete(User $user, User $auth)
    {
        return $user->id !== $auth->id;

    }


}
