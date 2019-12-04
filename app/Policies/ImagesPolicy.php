<?php

namespace App\Policies;

use \App\Models\Images;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ImagesPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any images.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(?User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can view the images.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Images  $images
     * @return mixed
     */
    public function view(?User $user, Images $images)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create images.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the images.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Images  $image
     * @return mixed
     */
    public function update(User $user, Images $image)
    {
        //
        return $user->id === $image->user_id;
    }

    /**
     * Determine whether the user can delete the images.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Images  $image
     * @return mixed
     */
    public function delete(User $user, Images $image)
    {
        //
        return $user->id === $image->user_id;
    }

    /**
     * Determine whether the user can restore the images.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Images  $images
     * @return mixed
     */
    public function restore(User $user, Images $images)
    {
        //
        return $user->id === $image->user_id;
    }

    /**
     * Determine whether the user can permanently delete the images.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Images  $images
     * @return mixed
     */
    public function forceDelete(User $user, Images $images)
    {
        //
        return $user->id === $image->user_id;
    }
}
