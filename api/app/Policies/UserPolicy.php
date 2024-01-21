<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Auth\Access\Response;

class UserPolicy
{
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

    public function update(User $user)
    {

        return true;
    }

    public function delete(User $user, User $userDel): Response
    {
        $auth = Auth::user();
        $authId = $auth->id;

        return $userDel->id !== $authId ? Response::allow() : Response::deny('Não pode excluir seu próprio usuário.');

    }


}
