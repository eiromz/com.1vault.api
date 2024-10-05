<?php

namespace Src\Wallets\Payments\Domain\Services;

use JsonException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use App\Exceptions\BaseException;
use Src\Wallets\Payments\App\Enum\Messages;
use Src\Wallets\Payments\Domain\Integrations\Providus\ProvidusRestApi;
use Src\Wallets\Payments\Domain\Integrations\Providus\Requests\NipFundTransfer;
use Symfony\Component\HttpFoundation\Response;

class NipTransferService
{
    public object $response;

    public object $data;

    private object $connector;

    private object $nipService;

    public function __construct(private $request)
    {
        $this->connector = new ProvidusRestApi;
        $this->nipService = new NipFundTransfer($this->request->only([
            'transactionAmount', 'beneficiaryAccountName', 'beneficiaryAccountNumber',
            'beneficiaryBank', 'currencyCode', 'narration', 'userName', 'password', 'sourceAccountName', 'transactionReference',
        ]));
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function callExtServer()
    {
        $this->response = $this->connector->send($this->nipService);

        return $this;
    }

    public function queueTransaction()
    {
        //$this->response->status() === 200
        //call the nipTransferValidationService
        //new nipTransferValidationService()
    }

    /**
     * @throws BaseException
     * @throws JsonException
     */
    public function callExtServerFailed()
    {
        if ($this->response->status() !== 200 && ! isset($this->data['responseCode'])) {
            throw new BaseException(Messages::TRANSACTION_FAILED->value,
                Response::HTTP_BAD_REQUEST
            );
        }

        if ($this->data['responseCode'] !== '00') {
            throw new BaseException(Messages::TRANSACTION_FAILED->value,
                Response::HTTP_BAD_REQUEST
            );
        }

        return $this->response->json();
    }
}
