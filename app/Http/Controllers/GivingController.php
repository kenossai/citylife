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

        // Create the Gift Aid declaration
        GiftAidDeclaration::create([
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

        return redirect()->route('giving.index')
            ->with('success', 'Thank you for your Gift Aid declaration. We have received your information successfully.');
    }
}
