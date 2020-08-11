<?php

namespace Wikichua\SAP\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
              'status_code' => 200,
              'data' => $request->user(),
          ]);
    }
}
