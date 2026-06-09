<?php

namespace App\Repositories\Contracts;

use App\Models\Venue;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface VenueRepositoryInterface
{
    public function findBySlug(string $slug): ?Venue;

    public function search(array $filters): LengthAwarePaginator;

    public function popular(int $limit = 8): Collection;

    public function featured(int $limit = 6): Collection;
}
