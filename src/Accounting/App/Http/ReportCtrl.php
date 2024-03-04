<?php

namespace Src\Accounting\App\Http;

use App\Exceptions\BaseException;
use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Business;
use App\Models\Invoice;
use App\Models\PosRequest;
use App\Models\Receipt;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Http\Request;
use Src\Accounting\Domain\Repository\Interfaces\InvoiceRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class ReportCtrl extends DomainBaseCtrl
{
    private $data;
    private string $getView;
    private $getModel;
    public function index(Request $request){
        $fileName = $request->type.generateTransactionReference();

        $request->validate([
            'type' => ['required', 'in:sales,debtors,invoice,receipt,pos'],
            'identifier' => ['required'],
            'start_date' => ['required_if:type,sales,debtors', 'date_format:Y-m-d'],
            'end_date' => ['required_if:type,sales,debtors', 'date_format:Y-m-d'],
        ]);

        $default_paper_size = Format::A3;

        $this->getView = match ($request->type) {
            'sales'     => 'pdf-template.sales',
            'debtors'   => 'pdf-template.debtors',
            'receipt'   => 'pdf-template.receipt',
            'invoice'   => 'pdf-template.invoice',
            'pos'       => 'pdf-template.pos',
        };

        $this->getModel = match ($request->type) {
            'receipt' => Receipt::query(),
            'pos' => PosRequest::query(),
            'sales','debtors','invoice' => Invoice::query()
        };

        $this->data = (in_array($request->type, ['receipt', 'pos', 'invoice'])) ?
            $this->getModel->findOrFail($request->identifier) : $this->getModel;

        $business = Business::where('customer_id','=',auth()->user()->id)->latest()->first();

        $request->merge([
            'business' => $business
        ]);

        $this->handleSalesDebtors($request);

        $this->modifyInventoryItems($request);

        $data = $this->data;

        return Pdf::view($this->getView, compact('data','request'))
            ->withBrowsershot(function (Browsershot $browsershot) {
                $browsershot->setNodeBinary(config('app.which_node'))
                    ->setNpmBinary(config('app.which_npm'));})
            ->format($default_paper_size)
            ->save("{$fileName}.pdf");
    }
    private function modifyInventoryItems($request): void
    {
        if ($request->type === 'invoice' || $request->type === 'receipt')  {
            $this->data->inventory = collect($this->data->items)->map(function ($item) {
                $item['subtotal'] = ((double)$item['amount'] * (int)$item['quantity']);
                return $item;
            });
            $this->data->subtotal = $this->data->inventory->sum('subtotal');
        }

//        if($request->type === 'sales'){
//            $this->data->inventory_name = collect($this->data->items)->map(fn($item) => "{$item['name']},");
//        }
    }

    private function handleSalesDebtors($request): void
    {
        if(in_array($request->type,['sales','debtors'])){

            $this->data = match($request->type){
                'debtors' => $this->getModel->where('business_id','=',$request->business->id)
                    ->whereBetween('due_date',[$request->start_date,$request->end_date])->where('payment_status','=',false)->get(),
                'sales' => $this->getModel->where('business_id','=',$request->business->id)
                    ->whereBetween('due_date',[$request->start_date,$request->end_date])->get(),
                default => null
            };
        }
    }
}
