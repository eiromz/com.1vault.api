<?php

namespace Src\Wallets\Payments\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Exceptions\InsufficientBalance;
use Src\Wallets\Payments\App\Requests\CreateJournalRequest;
use Src\Wallets\Payments\Domain\Actions\GetAccountInstance;
use Src\Wallets\Payments\Domain\Repository\Interfaces\JournalRepositoryInterface;
use Src\Wallets\Payments\Domain\Services\JournalWalletCreditService;
use Src\Wallets\Payments\Domain\Services\JournalWalletDebitService;
use Symfony\Component\HttpFoundation\Response;

class JournalCtrl extends DomainBaseCtrl
{
    private $repository;

    public function __construct(JournalRepositoryInterface $repository)
    {
        $this->repository = $repository;
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function index(Request $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->validate([
            'filter_type' => ['required', 'in:default,date,search'],
            'start_date' => ['required_if:filter_type,date', 'after_or_equal:today'],
            'end_date' => ['required_if:filter_type,date', 'after_or_equal:tomorrow'],
        ]);

        //TODO please make sure to handle the search feature
        $result = match ($request->filter_type) {
            'default' => $this->repository->getAllByParams([]),
            'date' => $this->repository->getAllByCreatedAtDate($request->start_date, $request->end_date, []),
        };

        return jsonResponse(Response::HTTP_OK, $result);
    }

    public function view(Request $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->validate([
            'trx_ref' => ['required', 'exists:App\Models\Journal,trx_ref'],
        ]);

        $result = $this->repository->getDetailsByParams([
            'trx_ref' => $request->trx_ref,
        ]);

        return jsonResponse(Response::HTTP_OK, $result);
    }

    /**
     * TODO : refactor the process into a service class
     *
     * @throws InsufficientBalance
     * @throws Exception
     */
    public function store(CreateJournalRequest $request)
    {
        $this->repository->setUser(auth()->user());

        $request->execute();

        $source = new JournalWalletDebitService(
            GetAccountInstance::getActiveInstance(auth()->user()->profile),
            $request
        );

        $source->validateTransactionPin();

        $source->checkBalance()->debit()->notify()->saveBeneficiary()->updateBalanceQueue();

        $destination = new JournalWalletCreditService(
            GetAccountInstance::getActiveInstance($request->profile), $request
        );

        $destination->credit()->notify()->updateBalanceQueue();

        return jsonResponse(Response::HTTP_OK, $source->journal);
    }
}
