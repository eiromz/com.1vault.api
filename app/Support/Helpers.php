<?php

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

if (! function_exists('generateCode')) {
    /**
     * Generate random auth code.
     */
    function generateCode($arg1=100000,$arg2=999999): int
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
        $code = generateCode(1000,9999);
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
    /**
     * Generate random auth code.
     *
     * @throws Exception
     */
    function jsonResponse($statusCode, $data): JsonResponse
    {
        return response()->json([
            'status' => $statusCode,
            'data' => $data,
        ], $statusCode);
    }
}
