<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BloghomeController extends Controller
{
    /**
     * Display Blog dashboard page.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Dashboards/Blog');
    }
}
