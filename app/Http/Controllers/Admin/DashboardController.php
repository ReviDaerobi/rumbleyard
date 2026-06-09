<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $dailyBookings = Booking::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $monthlyRevenue = Payment::query()
            ->select(DB::raw("DATE_FORMAT(paid_at, '%Y-%m') as month"), DB::raw('sum(amount) as total'))
            ->where('status', PaymentStatus::Paid)
            ->whereNotNull('paid_at')
            ->where('paid_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', [
            'stats' => [
                'users' => User::count(),
                'venues' => Venue::count(),
                'bookings' => Booking::count(),
                'revenue' => Payment::where('status', PaymentStatus::Paid)->sum('amount'),
            ],
            'dailyBookings' => $dailyBookings,
            'monthlyRevenue' => $monthlyRevenue,
            'activityLogs' => ActivityLog::with('user')->latest()->limit(10)->get(),
        ]);
    }
}
