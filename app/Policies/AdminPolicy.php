<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

abstract class AdminPolicy
{
    /** @var list<string> */
    protected array $managers = ['admin'];

    /** @var list<string> */
    protected array $viewers = ['admin', 'readonly'];

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(array_unique([...$this->managers, ...$this->viewers]));
    }

    public function view(User $user, Model $model): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole($this->managers);
    }

    public function update(User $user, Model $model): bool
    {
        return $user->hasAnyRole($this->managers);
    }

    public function delete(User $user, Model $model): bool
    {
        return $user->hasAnyRole($this->managers);
    }

    public function restore(User $user, Model $model): bool
    {
        return $user->hasAnyRole($this->managers);
    }

    public function forceDelete(User $user, Model $model): bool
    {
        return $user->hasRole('admin');
    }
}
