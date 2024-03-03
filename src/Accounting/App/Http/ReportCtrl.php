<?php

namespace Src\Accounting\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
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
    public function index(Request $request){
        $fileName = $request->type.generateTransactionReference();

        $request->validate([
            'type' => ['required', 'in:sales,debtors,invoice,receipt,pos'],
            'identifier' => ['required'],
            'start_date' => ['required_if:type,sales,debtors', 'date_format:Y-m-d'],
            'end_date' => ['required_if:type,sales,debtors', 'date_format:Y-m-d'],
        ]);

        $default_paper_size = Format::A3;

        $getView = match ($request->type) {
            'sales'     => 'pdf-template.sales',
            'debtors'   => 'pdf-template.debtors',
            'receipt'   => 'pdf-template.receipt',
            'invoice'   => 'pdf-template.invoice',
            'pos'       => 'pdf-template.pos',
        };

        $getModel = match ($request->type) {
            'receipt' => Receipt::query(),
            'pos' => PosRequest::query(),
            'sales','debtors','invoice' => Invoice::query()
        };

        $data = (in_array($request->type, ['receipt', 'pos', 'invoice'])) ?
            $getModel->findOrFail($request->identifier) : $getModel;

        if(in_array($request->type, ['sales','debtors'])) {
            $business = Business::where('customer_id','=',auth()->user()->id)->latest()->firstOrFail();
            $data = $getModel->where('business_id','=',$business->id)->firstOrFail();
        }

        $this->modifyInventoryItems($request,$data);

        return Pdf::view($getView, compact('data'))
            ->withBrowsershot(function (Browsershot $browsershot) {
                $browsershot->setNodeBinary(config('app.which_node'))
                    ->setNpmBinary(config('app.which_npm'));})
            ->format($default_paper_size)
            ->save("{$fileName}.pdf");
    }
    private function modifyInventoryItems($request,$data): void
    {
        if ($request->type === 'invoice' || $request->type === 'receipt')  {
            $data->inventory = collect($data->items)->map(function ($item) {
                $item['subtotal'] = ((double)$item['amount'] * (int)$item['quantity']);
                return $item;
            });
            $data->subtotal = $data->inventory->sum('subtotal');
        }
    }
}
