<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ShortUrlController extends Controller
{
    public function store(Request $request)
    {
        abort_unless(
            in_array($request->user()->role, ['Sales', 'Manager'], true),
            403,
            'You are not allowed to create short URLs.'
        );

        $data = $request->validate([
            'original_url' => [
                'required',
                'url',
                'max:2048',
                Rule::notIn(['']),
            ],
        ]);

        do {
            $code = Str::random(8);
        } while (ShortUrl::where('code', $code)->exists());

        ShortUrl::create([
            'company_id' => $request->user()->company_id,
            'user_id' => $request->user()->id,
            'original_url' => $data['original_url'],
            'code' => $code,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Short URL created successfully.');
    }

    public function resolve(string $code)
    {
        // Short URLs must never publicly redirect to their destination.
        abort(404);
    }
}