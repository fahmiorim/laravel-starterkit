<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Donor;
use App\Models\DonorCard;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class DonorCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $donorCards = DonorCard::with('donor')
            ->latest()
            ->paginate(15);

        return view('admin.donor-cards.index', compact('donorCards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $donors = Donor::doesntHave('donorCard')
            ->pluck('name', 'id');

        return view('admin.donor-cards.create', compact('donors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'donor_id' => 'required|exists:donors,id|unique:donor_cards,donor_id',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Generate card number
        $validated['card_number'] = DonorCard::generateCardNumber();
        $validated['status'] = 'active';

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('donor-cards/photos', 'public');
            $validated['card_photo_path'] = $path;
        }

        // Generate QR Code
        $qrContent = route('donor-cards.show', ['donor_card' => $validated['card_number']]);
        $qrCode = QrCode::format('png')
            ->size(300)
            ->generate($qrContent);
        
        $qrPath = 'donor-cards/qrcodes/' . Str::slug($validated['card_number']) . '.png';
        Storage::disk('public')->put($qrPath, $qrCode);
        $validated['qr_code_path'] = $qrPath;

        // Create donor card
        $donorCard = DonorCard::create($validated);

        return redirect()
            ->route('admin.donor-cards.show', $donorCard)
            ->with('success', 'Kartu Donor berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(DonorCard $donorCard)
    {
        $donorCard->load('donor');
        return view('admin.donor-cards.show', compact('donorCard'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DonorCard $donorCard)
    {
        $donorCard->load('donor');
        return view('admin.donor-cards.edit', compact('donorCard'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DonorCard $donorCard)
    {
        $validated = $request->validate([
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'status' => 'required|in:active,inactive,expired,revoked',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($donorCard->card_photo_path) {
                Storage::disk('public')->delete($donorCard->card_photo_path);
            }
            
            $path = $request->file('photo')->store('donor-cards/photos', 'public');
            $validated['card_photo_path'] = $path;
        }

        $donorCard->update($validated);

        return redirect()
            ->route('admin.donor-cards.show', $donorCard)
            ->with('success', 'Kartu Donor berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DonorCard $donorCard)
    {
        // Delete associated files
        if ($donorCard->qr_code_path) {
            Storage::disk('public')->delete($donorCard->qr_code_path);
        }
        if ($donorCard->card_photo_path) {
            Storage::disk('public')->delete($donorCard->card_photo_path);
        }

        $donorCard->delete();

        return redirect()
            ->route('admin.donor-cards.index')
            ->with('success', 'Kartu Donor berhasil dihapus');
    }

    /**
     * Display the specified resource for public view.
     */
    public function publicShow($cardNumber)
    {
        $donorCard = DonorCard::where('card_number', $cardNumber)
            ->with('donor')
            ->firstOrFail();

        return view('admin.donor-cards.public-show', compact('donorCard'));
    }

    /**
     * Show the verification form
     */
    public function showVerificationForm()
    {
        return view('admin.donor-cards.verify');
    }
    
    /**
     * Verify a donor card by number
     */
    public function verifyCard(Request $request)
    {
        $request->validate([
            'card_number' => 'required|string|exists:donor_cards,card_number'
        ]);
        
        $cardNumber = $request->input('card_number');
        
        return redirect()->route('donor-cards.verify', $cardNumber);
    }

    /**
     * Generate PDF for the specified donor card.
     */
    public function downloadPdf(DonorCard $donorCard)
    {
        $donorCard->load('donor');
        $pdf = PDF::loadView('admin.donor-cards.pdf', compact('donorCard'));
        
        return $pdf->download('kta-' . $donorCard->card_number . '.pdf');
    }
}
