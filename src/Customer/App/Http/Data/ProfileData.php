<?php

namespace Src\Customer\App\Http\Data;

use App\Models\Customer;
use Exception;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Data;
use Src\Customer\App\Enum\AccountStatus;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\Attribute\Required;

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
