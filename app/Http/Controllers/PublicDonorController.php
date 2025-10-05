<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use Illuminate\Http\Request;

class PublicDonorController extends Controller
{
    public function show(Donor $donor)
    {
        return view('donors.show', compact('donor'));
    }
}