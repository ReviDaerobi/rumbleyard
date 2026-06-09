<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\Payment\MockPaymentGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct()
    {
      
    }

    public function show(Payment $payment): View
    {
        $this->authorize('pay', $payment->booking);
        $payment->load(['booking.venue.images']);

        return view('payments.show', compact('payment'));
    }

    public function mockCheckout(Payment $payment): View
    {
        $this->authorize('pay', $payment->booking);
        $payment->load(['booking.venue']);

        return view('payments.mock-checkout', compact('payment'));
    }

    public function mockPay(Payment $payment, MockPaymentGateway $gateway): RedirectResponse
    {
        $this->authorize('pay', $payment->booking);
        $gateway->simulateSuccess($payment);

        return redirect()->route('bookings.success', $payment->booking);
    }

    public function mockFail(Payment $payment, MockPaymentGateway $gateway): RedirectResponse
    {
        $this->authorize('pay', $payment->booking);
        $gateway->simulateFailure($payment);

        return redirect()->route('payments.show', $payment)
            ->with('error', 'Pembayaran gagal. Silakan coba lagi.');
    }
}
