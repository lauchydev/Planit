<?php

namespace App\Http\Controllers;

use App\Queries\OrganiserDashboardQuery;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        abort_unless($user && $user->isOrganiser(), 403);

        $rows = OrganiserDashboardQuery::forUser($user->id);

        return view('dashboard.index', [
            'rows' => $rows,
        ]);
    }
}
