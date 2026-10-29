<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $urls = ShortUrl::with('user')
            ->when(
                $user->role === 'Admin',
                fn ($query) => $query->where(
                    'company_id',
                    '!=',
                    $user->company_id
                )
            )
            ->when(
                $user->role === 'Member',
                fn ($query) => $query->where(
                    'user_id',
                    '!=',
                    $user->id
                )
            )
            ->when(
                $user->role === 'SuperAdmin',
                fn ($query) => $query->where(
                    'company_id',
                    $user->company_id
                )
            )
            ->latest()
            ->paginate(10);

        return view('dashboard', compact('urls'));
    }
}