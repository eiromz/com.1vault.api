<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseException extends Exception
{
    protected array $payload = [];
    /**
     * @throws Exception
     */
    public function render(Request $request)
    {
        $this->payload = [
            'message'   => $this->getMessage(),
            'code'      => $this->getCode()
        ];

        if ($request->is('api/*')) {
            return jsonResponse(
                $this->getCode(),
                $this->payload
            );
        }
    }
}
