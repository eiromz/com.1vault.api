<?php

namespace Src\Wallets\Payments\App\Http\Providus\Bills;

use App\Exceptions\BaseException;
use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use JsonException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Src\Wallets\Payments\App\Enum\Messages;
use Src\Wallets\Payments\Domain\Integrations\Providus\ProvidusBills;
use Src\Wallets\Payments\Domain\Integrations\Providus\Requests\GetBillsCategory;
use Src\Wallets\Payments\Domain\Integrations\Providus\Requests\GetCatgories;
use Symfony\Component\HttpFoundation\Response;

class CategoriesCtrl extends DomainBaseCtrl
{
    public array $excludeBills = ['Transportation', 'Insurance', 'Betting and Lottery', 'Examinations', 'Church Collection'];

    /**
     * @throws FatalRequestException
     * @throws BaseException
     * @throws RequestException
     * @throws JsonException
     */
    public function index(): JsonResponse
    {
        try{
            $connector = new ProvidusBills;
            $request = new GetCatgories;
            $response = $connector->send($request);

            if (!$response->ok()) {
                throw new BaseException(Messages::TRANSACTION_FAILED->value,
                    Response::HTTP_BAD_REQUEST
                );
            }


            if ($response->collect()->isEmpty()) {
                throw new BaseException(Messages::TRANSACTION_FAILED->value,
                    Response::HTTP_BAD_REQUEST
                );
            }

            $data = $response->collect()->filter(fn ($array) => ! in_array($array['name'], $this->excludeBills));

            return jsonResponse($response->status(), $data);
        } catch (Exception $e) {
            dd($e->getMessage());
            //logExceptionErrorMessage('GenerateAccountNumberQueue', $e, [], 'critical');
        }
    }

    /**
     * @throws BaseException
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function view($category): JsonResponse
    {
        try {
            $connector = new ProvidusBills;
            $request = new GetBillsCategory($category);
            $response = $connector->send($request);

            if (! $response->ok()) {
                throw new BaseException(Messages::TRANSACTION_FAILED->value,
                    Response::HTTP_BAD_REQUEST
                );
            }

            if ($response->collect()->isEmpty()) {
                throw new BaseException(Messages::TRANSACTION_FAILED->value,
                    Response::HTTP_BAD_REQUEST
                );
            }

            return jsonResponse($response->status(), $response->collect());
        } catch (Exception $e) {
            dd($e->getMessage());
            //logExceptionErrorMessage('GenerateAccountNumberQueue', $e, [], 'critical');
        }
    }
}
