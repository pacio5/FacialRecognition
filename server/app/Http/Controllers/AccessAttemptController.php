<?php

namespace App\Http\Controllers;

use App\Models\AccessAttempt;
use App\Models\AuthorizedFace;
use Illuminate\Http\Request;

class AccessAttemptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accessAttempts = AccessAttempt::paginate(20)->load('authorized_face');
        return view('attempts.index', [
            'accessAttempts' => $accessAttempts,
        ]);
    }
}
