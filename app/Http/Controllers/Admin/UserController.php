<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->orderByDesc('is_admin')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'is_admin' => ['required', 'boolean'],
        ]);

        $makeAdmin = (bool) $validated['is_admin'];
        $currentUser = $request->user();

        if ($currentUser->id === $user->id && !$makeAdmin) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas retirer votre propre role administrateur.');
        }

        if ($user->is_admin && !$makeAdmin && User::where('is_admin', true)->count() <= 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Impossible de retirer le role du dernier administrateur.');
        }

        $user->update(['is_admin' => $makeAdmin]);

        return redirect()->route('admin.users.index')
            ->with('success', $makeAdmin
                ? 'Le role administrateur a ete attribue avec succes.'
                : 'Le role administrateur a ete retire avec succes.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $currentUser = $request->user();

        if ($currentUser->id === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte depuis cette page.');
        }

        if ($user->isAdmin() && User::where('is_admin', true)->count() <= 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Impossible de supprimer le dernier administrateur.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprime avec succes.');
    }
}