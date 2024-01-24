<?php

namespace Src\Wallets\Payments\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Profile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Wallets\Payments\Domain\Repository\Interfaces\JournalRepositoryInterface;
use Src\Wallets\Payments\Domain\Actions\GetAccountInstance;
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
                'search'        =>     ['required_if:filter_type,search']
            ]);

            $result = match($request->filter_type) {
                'default' => $this->repository->getAllByParams([]),
                'date'    => $this->repository->getAllByCreatedAtDate($request->start_date,$request->end_date,[]),
            };

            return jsonResponse(Response::HTTP_OK, $result);

        }catch(Exception $e){
            return jsonResponse(Response::HTTP_BAD_REQUEST, ['message' => 'We could find what you are looking for']);
        }
    }

    public function view(Request $request) : JsonResponse{
        $this->repository->setUser(auth()->user());

        $request->validate([
            'trx_ref' => ['required','exists:App\Models\Journal,trx_ref']
        ]);

        $result = $this->repository->getDetailsByParams([
            'trx_ref' => $request->trx_ref
        ]);

        return jsonResponse(Response::HTTP_OK, $result);
    }

    //Airtime::purchase();
    //Debit::journal();
    //Subscribe::service()
    //Order::service()
    //ProcessService::save();
    //PosRequest::save();
}
