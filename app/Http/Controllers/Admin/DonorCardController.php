<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DonorCardRequest;
use App\Models\DonorCard;
use App\Services\DonorCardService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonorCardController extends Controller
{
    public function __construct(
        private DonorCardService $donorCardService
    ) {}

    public function index(): View
    {
        $donorCards = $this->donorCardService->getPaginatedCards();

        return view('admin.donor-cards.index', compact('donorCards'));
    }

    public function create(): View
    {
        $donors = $this->donorCardService->getDonorsWithoutCard()
            ->pluck('name', 'id');

        return view('admin.donor-cards.create', compact('donors'));
    }

    public function store(DonorCardRequest $request): RedirectResponse
    {
        $donorCard = $this->donorCardService->createDonorCard($request->validated());

        return redirect()
            ->route('admin.donor-cards.show', $donorCard)
            ->with('success', 'Kartu Donor berhasil dibuat');
    }

    public function show(DonorCard $donorCard): View
    {
        $donorCard->load('donor');

        return view('admin.donor-cards.show', compact('donorCard'));
    }

    public function edit(DonorCard $donorCard): View
    {
        $donorCard->load('donor');

        return view('admin.donor-cards.edit', compact('donorCard'));
    }

    public function update(DonorCardRequest $request, DonorCard $donorCard): RedirectResponse
    {
        $donorCard = $this->donorCardService->updateDonorCard($donorCard, $request->validated());

        return redirect()
            ->route('admin.donor-cards.show', $donorCard)
            ->with('success', 'Kartu Donor berhasil diperbarui');
    }

    public function destroy(DonorCard $donorCard): RedirectResponse
    {
        $this->donorCardService->deleteDonorCard($donorCard);

        return redirect()
            ->route('admin.donor-cards.index')
            ->with('success', 'Kartu Donor berhasil dihapus');
    }

    public function publicShow($cardNumber): View
    {
        $donorCard = DonorCard::where('card_number', $cardNumber)
            ->with('donor')
            ->firstOrFail();

        return view('admin.donor-cards.public-show', compact('donorCard'));
    }

    public function showVerificationForm(): View
    {
        return view('admin.donor-cards.verify');
    }

    public function verifyCard(Request $request): RedirectResponse
    {
        $request->validate([
            'card_number' => 'required|string|exists:donor_cards,card_number'
        ]);

        $cardNumber = $request->input('card_number');

        return redirect()->route('donor-cards.verify', $cardNumber);
    }

    public function downloadPdf(DonorCard $donorCard)
    {
        $donorCard->load('donor');
        $pdf = PDF::loadView('admin.donor-cards.pdf', compact('donorCard'));

        return $pdf->download('kta-' . $donorCard->card_number . '.pdf');
    }
}
