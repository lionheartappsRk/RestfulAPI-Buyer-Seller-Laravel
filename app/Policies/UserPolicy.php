<?php

namespace App\Policies;

use App\authuser;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class authuserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the authuser can view any authusers.
     *
     * @param  \App\authuser  $authuser
     * @return mixed
     */
    public function viewAny(User $authuser)
    {
        //
    }

    /**
     * Determine whether the authuser can view the authuser.
     *
     * @param  \App\authuser  $authuser
     * @param  \App\authuser  $authuser
     * @return mixed
     */
    public function view(User $authuser, User $user)
    {
        //
        return $authuser->id === $user->id;
    }



    /**
     * Determine whether the authuser can update the authuser.
     *
     * @param  \App\authuser  $authuser
     * @param  \App\authuser  $authuser
     * @return mixed
     */
    public function update(User $authuser, User $user)
    {
        return $authuser->id === $user->id;
    }


    public function delete(User $authuser, User $user)
    {
        //
        return $authuser->id === $user->id;
    }
}
