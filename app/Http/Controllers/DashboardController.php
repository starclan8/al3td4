<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Need;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $myNeeds = $user->canSeekHelp() ? $user->needs()->latest()->get() : collect();
        $helpOpportunities = $user->canProvideHelp()
            ? Need::where('status', 'active')
                ->where('user_id', '!=', $user->id)
                ->whereDoesntHave('helpers', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->get()
            : collect();

        return view('dashboard', [
            'myNeeds' => $myNeeds,
            'helpOpportunities' => $helpOpportunities,
            'user' => $user,
        ]);
    }
}