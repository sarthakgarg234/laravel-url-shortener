<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class InvitationController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
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

        $invitation = Invitation::create([
            'company_id' => $user->company_id,
            'invited_by' => $user->id,
            'email' => $data['email'],
            'role' => $data['role'],
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Invitation created. Share the invitation link with the user.')
            ->with(
                'invitation_url',
                route('invitations.accept.form', $invitation->token)
            );
    }

    public function showAcceptForm(string $token)
    {
        $invitation = Invitation::where('token', $token)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        return view('invitations.accept', compact('invitation'));
    }

    public function accept(Request $request, string $token)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = DB::transaction(function () use ($data, $token) {
            $invitation = Invitation::where('token', $token)
                ->lockForUpdate()
                ->firstOrFail();

            abort_if(
                $invitation->accepted_at !== null ||
                $invitation->expires_at->isPast(),
                410,
                'This invitation has expired or has already been used.'
            );

            abort_if(
                User::where('email', $invitation->email)->exists(),
                409,
                'An account already exists for this email address.'
            );

            $newUser = User::create([
                'name' => $data['name'],
                'email' => $invitation->email,
                'password' => $data['password'],
                'company_id' => $invitation->company_id,
                'role' => $invitation->role,
                'is_active' => true,
            ]);

            $invitation->update([
                'accepted_at' => now(),
            ]);

            return $newUser;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Your account has been created successfully.');
    }
}