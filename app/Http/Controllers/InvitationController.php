<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'role' => [
                'required',
                'in:Admin,Member,Sales,Manager',
            ],
        ]);

        if ($user->role === 'SuperAdmin') {
            abort_if(
                $data['role'] === 'Admin',
                403,
                'SuperAdmin cannot invite an Admin.'
            );
        } elseif ($user->role === 'Admin') {
            abort_if(
                !in_array($data['role'], ['Sales', 'Manager'], true),
                403,
                'Admin can invite Sales or Manager only.'
            );
        } else {
            abort(403, 'You cannot invite users.');
        }

        Invitation::create([
            'company_id' => $user->company_id,
            'invited_by' => $user->id,
            'email' => $data['email'],
            'role' => $data['role'],
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Invitation created.');
    }
}