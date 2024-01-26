<?php

use Illuminate\Http\JsonResponse;

if (! function_exists('generateCode')) {
    /**
     * Generate random auth code.
     */
    function generateCode($arg1 = 100000, $arg2 = 999999): int
    {
        return random_int($arg1, $arg2);
    }
}

if (! function_exists('generateOtpCode')) {
    /**
     * Generate random auth code.
     */
    function generateOtpCode(): int
    {
        return generateCode();
    }
}

if (! function_exists('generateReferralCode')) {
    /**
     * Generate random auth code.
     */
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
