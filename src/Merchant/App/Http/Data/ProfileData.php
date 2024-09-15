<?php

namespace Src\Merchant\App\Http\Data;

use Spatie\LaravelData\Data;

class ProfileData extends Data
{
    public function __construct(
        public int $id,
        public int $user_id,
        public int $product_id,
        public Currency $amount,
        public string $status,
        public string $processed_at,
        public string $created_at,
        public string $updated_at,
    ) {}
}
