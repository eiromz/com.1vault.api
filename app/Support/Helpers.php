<?php

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Src\Services\App\Enum\BillingCycle;
use Src\Template\Application\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response;

if (! function_exists('generateCode')) {
    function generateCode($arg1 = 100000, $arg2 = 999999): int
    {
        return random_int($arg1, $arg2);
    }
}

if (! function_exists('generateOtpCode')) {
    function generateOtpCode(): int
    {
        return generateCode();
    }
}

if (! function_exists('generateReferralCode')) {
    function generateReferralCode(): string
    {
        $code = generateCode(1000, 9999);

        return "1V{$code}T";
    }
}

if (! function_exists('generateAccountId')) {
    function generateAccountId(): string
    {
        $code = generateCode(10000000, 99999999);

        return "AC{$code}ID";
    }
}

if (! function_exists('jsonResponse')) {
    function jsonResponse($statusCode, $data): JsonResponse
    {
        return response()->json([
            'status' => $statusCode,
            'data' => $data,
        ], $statusCode);
    }
}

if (! function_exists('createNameForToken')) {
    function createNameForToken($email): array|string
    {
        $identifier = str_replace('@', '', $email);
        $unix = now()->unix();

        return "{$identifier}{$unix}";
    }
}

if (! function_exists('logExceptionErrorMessage')) {
    function logExceptionErrorMessage(string $name, $exception, array $context = [], $level = 'error'): void
    {
        $context['class-identifier'] = $name;

        if (! is_null($exception)) {
            $context = [
                'message' => $exception->getMessage() ?? 'Nothing to report',
                'trace' => $exception->getTraceAsString() ?? 'Nothing to report',
            ];
        }

        $data = $context;

        logger($level, $data);
    }
}

if (! function_exists('generateInvoiceNumber')) {
    function generateInvoiceNumber($model, $prefix)
    {
        $latest = $model::latest()->first();

        if (! $latest) {
            return $prefix.'0001';
        }

        $string = preg_replace("/[^0-9\.]/", '', $latest->number);

        return $prefix.sprintf('%04d', $string + 1);
    }
}

if (! function_exists('generateTransactionReference')) {
    function generateTransactionReference(): string
    {
        $random = fake()->randomNumber(6);

        return 'trx'.time().uniqid().$random;
    }
}

if (! function_exists('calculateDiscount')) {
    function calculateDiscount($attributes): float|int
    {
        $amount = $attributes['amount'];
        $has_discount = $attributes['has_discount'];
        $discount = $attributes['discount'];

        if ($discount > 100 || ! $has_discount || $discount === 0) {
            return $amount;
        }

        $discount = ($discount / 100);

        $calculateDiscountAmount = $discount * (float) $amount;

        $newAmount = ($amount - $calculateDiscountAmount);

        if ($newAmount < 0) {
            throw new BaseException('Invalid discount amount', Response::HTTP_BAD_REQUEST);
        }

        return $newAmount;
    }
}

if (! function_exists('determineExpirationDate')) {
    function determineExpirationDate(Carbon $currentDate, string $billing_cycle): Carbon
    {
        return match ($billing_cycle) {
            BillingCycle::MONTHLY->value => $currentDate->addMonth(),
            BillingCycle::QUARTERLY->value => $currentDate->addMonths(3),
            BillingCycle::YEARLY->value => $currentDate->addMonths(12)
        };
    }
}

if (! function_exists('generateOrderNumber')) {
    function generateOrderNumber(): string
    {
        $code = generateCode(1000000000, 99999999999);

        return "ORID{$code}";
    }
}

if (! function_exists('generateProvidusTransactionRef')) {
    function generateProvidusTransactionRef(): string
    {
        $year = now()->format('Y');
        $code = generateCode(1000000000, 9999999999);

        return "{$year}{$code}";
    }
}

if (! function_exists('getInventorySizeLimit')) {
    function getInventorySizeLimit($model, $user): int
    {
        return $model->where('is_store_front', '=', true)
            ->where('customer_id', '=', $user->id)->first()->inventory_limit
            ?? config('1vault.store_front_size');
    }
}
