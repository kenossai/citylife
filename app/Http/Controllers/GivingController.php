<?php

namespace App\Http\Controllers;

use App\Models\GiftAidDeclaration;
use Illuminate\Http\Request;

class GivingController extends Controller
{
    /**
     * Display the giving page.
     */
    public function index()
    {
        return view('pages.giving.index');
    }

    /**
     * Handle Gift Aid form submission
     */
    public function submitGiftAid(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'postcode' => 'required|string|max:10',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'gift_aid_code' => 'required|string|max:50|unique:gift_aid_declarations,gift_aid_code',
            'confirmation_date' => 'required|date',
            'confirm_declaration' => 'required|accepted',
        ]);

        // Check for existing declaration with same email
        $existingDeclaration = GiftAidDeclaration::where('email', $request->email)
            ->where('is_active', true)
            ->first();

        if ($existingDeclaration) {
            return redirect()->route('giving.index')
                ->with('error', 'A Gift Aid declaration already exists for this email address. If you need to update your details, please contact us.');
        }

        // Create the Gift Aid declaration
        $declaration = GiftAidDeclaration::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'postcode' => $request->postcode,
            'phone' => $request->phone,
            'email' => $request->email,
            'gift_aid_code' => $request->gift_aid_code,
            'confirmation_date' => $request->confirmation_date,
            'confirm_declaration' => $request->has('confirm_declaration'),
            'is_active' => true,
        ]);

        // Check if there are any existing givings that can now be marked as gift aid eligible
        $matchedGivings = \App\Models\Giving::where('donor_email', $request->email)
            ->where('gift_aid_eligible', false)
            ->update(['gift_aid_eligible' => true]);

        $successMessage = 'Thank you for your Gift Aid declaration. We have received your information successfully.';
        if ($matchedGivings > 0) {
            $successMessage .= " We've also found {$matchedGivings} previous donations that are now eligible for Gift Aid.";
        }

        return redirect()->route('giving.index')
            ->with('success', $successMessage);
    }
}
