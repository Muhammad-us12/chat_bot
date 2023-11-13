<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->query("embedded", false) === "1") {
            return \view('welcome');
        } else {
            // Handle the case when host is not found.
            $decodedHost = base64_decode($request->get('host'), true);
            $redirectUrl = "https://$decodedHost/apps/" . \config('services.shopify.key');
            return redirect($redirectUrl . "/" . $request->path(), secure: true);
        }
    }
}
