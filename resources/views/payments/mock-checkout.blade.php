@extends('layouts.app')
@section('title', 'Mock Payment')
@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="card-soft p-8 text-center">
        <p class="text-sm text-gray-500">Mock Payment Gateway</p>
        <p class="text-2xl font-bold my-4">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mb-6">Simulasi pembayaran untuk development. Siap integrasi Midtrans/Xendit.</p>
        <form action="{{ route('payments.mock.pay', $payment) }}" method="POST" class="mb-3">@csrf
            <button class="btn-secondary w-full">Simulasikan Pembayaran Sukses</button>
        </form>
        <form action="{{ route('payments.mock.fail', $payment) }}" method="POST">@csrf
            <button class="btn-outline w-full text-red-600">Simulasikan Gagal</button>
        </form>
    </div>
</div>
@endsection
