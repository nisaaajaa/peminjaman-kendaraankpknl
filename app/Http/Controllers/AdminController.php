<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Get all loans that are currently active (not yet returned, or waiting approval)
        // Since we don't have a status column in `loans` table in the migration,
        // we'll just fetch all loans for now. 
        // Note: For a real app, `loans` should have a `status` (pending, approved, returned).
        // I will pass the vehicles as well.
        $loans = Loan::with('vehicle')->orderBy('created_at', 'desc')->get();
        $vehicles = Vehicle::all();
        $employees = \App\Models\Employee::all();

        return view('admin.dashboard', compact('loans', 'vehicles', 'employees'));
    }
}
