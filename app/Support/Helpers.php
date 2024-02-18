<?php

use App\Exceptions\BaseException;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Src\Services\App\Enum\BillingCycle;
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
    /**
     * Generate random auth code.
     */
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
    /**
     * @return array|string|string[]
     */
    function createNameForToken($email): array|string
    {
        $identifier = str_replace('@', '', $email);
        $unix = now()->unix();

        return "{$identifier}{$unix}";
    }
}

if (! function_exists('logExceptionErrorMessage')) {

    function logExceptionErrorMessage(string $name, $exception, array $context = []): void
    {
        $context['class-identifier'] = $name;

        if (! is_null($exception)) {
            $context = [
                'message' => $exception->getMessage() ?? 'Nothing to report',
                'trace' => $exception->getTraceAsString() ?? 'Nothing to report',
            ];
        }

        $data = $context;

        logger('error', $data);
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
    /**
     * @throws BaseException
     */
    function calculateDiscount($attributes): float|int
    {
        $amount = $attributes['amount'];
        $has_discount = $attributes['has_discount'];
        $discount = $attributes['discount'];

        if ($discount > 100 || !$has_discount ||$discount === 0) {
            return $amount;
        }

        $discount = ($discount / 100);

        $calculateDiscountAmount = $discount * (double)$amount;

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
