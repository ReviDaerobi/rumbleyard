<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class BookingCodeService
{
    public function generate(): string
    {
        $year = (int) now()->format('Y');

        return DB::transaction(function () use ($year) {
            $sequence = DB::table('booking_sequences')
                ->where('year', $year)
                ->lockForUpdate()
                ->first();

            if (! $sequence) {
                DB::table('booking_sequences')->insert([
                    'year' => $year,
                    'last_number' => 1,
                ]);
                $number = 1;
            } else {
                $number = $sequence->last_number + 1;
                DB::table('booking_sequences')
                    ->where('year', $year)
                    ->update(['last_number' => $number]);
            }

            return sprintf('RY-%d-%05d', $year, $number);
        });
    }
}
