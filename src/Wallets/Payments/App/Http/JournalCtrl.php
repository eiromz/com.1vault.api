<?php

namespace Src\Wallets\Payments\App\Http;

use App\Exceptions\InsufficientBalance;
use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Profile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Wallets\Payments\Domain\Repository\Interfaces\JournalRepositoryInterface;
use Src\Wallets\Payments\Domain\Actions\GetAccountInstance;
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
        try{
            $this->repository->setUser(auth()->user());

            $request->validate([
                'filter_type'   =>    ['required','in:default,date,search'],
                'start_date'    =>     ['required_if:filter_type,date','after_or_equal:today'],
                'end_date'      =>     ['required_if:filter_type,date','after_or_equal:tomorrow'],
            ]);

            //TODO please make sure to handle the search feature
            $result = match($request->filter_type) {
                'default' => $this->repository->getAllByParams([]),
                'date'    => $this->repository->getAllByCreatedAtDate($request->start_date,$request->end_date,[]),
            };

            return jsonResponse(Response::HTTP_OK, $result);

        }catch(Exception $e){
            return jsonResponse(Response::HTTP_BAD_REQUEST, ['message' => 'We could find what you are looking for']);
        }
    }

    public function view(Request $request) : JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->validate([
            'trx_ref' => ['required','exists:App\Models\Journal,trx_ref']
        ]);

        $result = $this->repository->getDetailsByParams([
            'trx_ref' => $request->trx_ref
        ]);

        return jsonResponse(Response::HTTP_OK, $result);
    }

    /**
     * @throws InsufficientBalance
     */
    public function store(Request $request)
    {
        $this->repository->setUser(auth()->user());
        $request->merge([
            'trx_ref'   => generateTransactionReference(),
            'debit'     => true,
            'credit'    => false,
            'label'     => 'wallet to wallet transfer',
            'source'    => 'wallet transaction',
        ]);

        $request->validate([
            'account_number' => ['required','exists:App\Models\Profile,account_number'],
            'amount'         => ['required']
        ]);

        $source =  new JournalWalletDebitService(
            GetAccountInstance::getActiveInstance(auth()->user()->profile),$request,$this->repository
        );

        $source->checkBalance()->debit()->notify();

        $destination = new JournalWalletDebitService(
            GetAccountInstance::getActiveInstance(
                Profile::query()->where('account_number','=',$request->account_number)->first()),
            $request,$this->repository
        );

        $destination->credit()->notify();
    }

    //Airtime::purchase();
    //Debit::journal();
    //Subscribe::service()
    //Order::service()
    //ProcessService::save();
    //PosRequest::save();
}
