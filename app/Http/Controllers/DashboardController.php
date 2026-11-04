<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        return match(auth()->user()->role) {
            'admin'     => redirect()->route('dashboard.admin'),
            'formateur' => redirect()->route('dashboard.formateur'),
            default     => redirect()->route('dashboard.student'),
        };
    }
}