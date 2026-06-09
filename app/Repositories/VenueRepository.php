<?php

namespace App\Repositories;

use App\Models\Venue;
use App\Repositories\Contracts\VenueRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class VenueRepository implements VenueRepositoryInterface
{
    public function findBySlug(string $slug): ?Venue
    {
        return Venue::query()
            ->with(['sport', 'images', 'reviews.user'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }

    public function search(array $filters): LengthAwarePaginator
    {
        $query = Venue::query()
            ->with(['sport', 'images'])
            ->where('is_active', true);

        if (! empty($filters['q'])) {
            $q = $filters['q'];
            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%");
            });
        }

        if (! empty($filters['city'])) {
            $query->where('city', 'like', '%'.$filters['city'].'%');
        }

        if (! empty($filters['sport_id'])) {
            $query->where('sport_id', $filters['sport_id']);
        }

        if (! empty($filters['sport_ids']) && is_array($filters['sport_ids'])) {
            $query->whereIn('sport_id', $filters['sport_ids']);
        }

        if (! empty($filters['facilities']) && is_array($filters['facilities'])) {
            $keywords = [
                'penerangan' => ['lighting', 'penerangan'],
                'ruang_ganti' => ['ruang ganti', 'locker'],
                'rumput_sintetis' => ['rumput', 'sintetis'],
                'parkir' => ['parkir'],
                'dalam_ruangan' => ['dalam ruangan', 'indoor'],
            ];

            foreach ($filters['facilities'] as $facility) {
                if (! isset($keywords[$facility])) {
                    continue;
                }

                $query->where(function ($builder) use ($keywords, $facility) {
                    foreach ($keywords[$facility] as $keyword) {
                        $builder->orWhere('description', 'like', "%{$keyword}%");
                    }
                });
            }
        }

        if (! empty($filters['min_price'])) {
            $query->where('price_per_hour', '>=', $filters['min_price']);
        }

        if (! empty($filters['max_price'])) {
            $query->where('price_per_hour', '<=', $filters['max_price']);
        }

        if (! empty($filters['min_rating'])) {
            $query->where('rating_avg', '>=', $filters['min_rating']);
        }

        $sort = $filters['sort'] ?? 'popular';
        match ($sort) {
            'price_low' => $query->orderBy('price_per_hour'),
            'price_high' => $query->orderByDesc('price_per_hour'),
            'rating' => $query->orderByDesc('rating_avg'),
            default => $query->orderByDesc('is_featured')->orderByDesc('rating_avg'),
        };

        return $query->paginate(12)->withQueryString();
    }

    public function popular(int $limit = 8): Collection
    {
        return Venue::query()
            ->with(['sport', 'images'])
            ->where('is_active', true)
            ->orderByDesc('reviews_count')
            ->orderByDesc('rating_avg')
            ->limit($limit)
            ->get();
    }

    public function featured(int $limit = 6): Collection
    {
        return Venue::query()
            ->with(['sport', 'images'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderByDesc('rating_avg')
            ->limit($limit)
            ->get();
    }
}
