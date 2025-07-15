<?php

namespace App\Policies;

use App\Models\User;
use App\Models\EbookOrder;
use Illuminate\Auth\Access\HandlesAuthorization;

class EbookOrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_order::ebook');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EbookOrder $ebookOrder): bool
    {
        return $user->can('view_order::ebook');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_order::ebook');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EbookOrder $ebookOrder): bool
    {
        return $user->can('update_order::ebook');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EbookOrder $ebookOrder): bool
    {
        return $user->can('delete_order::ebook');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_order::ebook');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, EbookOrder $ebookOrder): bool
    {
        return $user->can('force_delete_order::ebook');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_order::ebook');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, EbookOrder $ebookOrder): bool
    {
        return $user->can('restore_order::ebook');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_order::ebook');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, EbookOrder $ebookOrder): bool
    {
        return $user->can('replicate_order::ebook');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_order::ebook');
    }
}
