<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FAQRCode\Google2FA;

class GenerateTwoFactorController extends Controller
{
    public function generate2faKey()
    {
        // Get the currently authenticated user
        $user = Auth::user();
        
        // Check if the user is authenticated
        if (!$user) {
            abort(403, 'User not authenticated.');
        }

        // Retrieve the user from the database using the authenticated user's ID
        $user = User::find($user->id);
        
        // Initialize the Google2FA library
        $google2fa = new Google2FA();

        // Generate a new secret key for 2FA
        $secret = $google2fa->generateSecretKey();

        // Save the generated secret key to the user's record

        $user->google2fa_secret = $secret;
        $user->save();

        // Generate a QR code for the user to scan with their 2FA app
        $qrCode = $google2fa->getQRCodeInline(
            config('app.name'), // Application name
            $user->email,       // User's email
            $secret             // Generated secret key
        );

        // Return a JSON response with the redirect URL, QR code, and secret
        return response()->json([
            'redirect' => route('2fa.verify'),
            'qrCode' => $qrCode,
            'secret' => $secret
        ]);
    }

    public function showForm()
    {
        // Return the view to verify the 2FA code
        return view('2fa.verify');
    }

    public function verify(Request $request)
    {
        // Retrieve the user using the ID stored in the session
        $user = User::find(session('2fa:user:id'));
        
        // Initialize the Google2FA library
        $google2fa = new Google2FA();

        // Verify the provided 2FA code against the user's secret key
        
        $isValidCode = $google2fa->verifyKey($user->google2fa_secret, $request->code);

        // Check if the code is valid
        if ($isValidCode) {
            Auth::login($user);
            session()->forget('2fa:user:id');
            return redirect()->intended('/');
        }

        // Return back with an error message if the code is invalid
        return back()->withErrors(['message' => 'Invalid code verification']);
    }
}
