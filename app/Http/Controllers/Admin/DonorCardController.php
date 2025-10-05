<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DonorCardRequest;
use App\Models\Donor;
use App\Models\DonorCard;
use App\Services\DonorCardService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
    /**
     * Store a newly created resource in storage.
     */
    public function store(DonorCardRequest $request, DonorCardService $donorCardService)
    {
        $donorCard = $donorCardService->createDonorCard($request->validated());

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
    public function update(DonorCardRequest $request, DonorCard $donorCard, DonorCardService $donorCardService)
    {
        $donorCard = $donorCardService->updateDonorCard($donorCard, $request->validated());

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
