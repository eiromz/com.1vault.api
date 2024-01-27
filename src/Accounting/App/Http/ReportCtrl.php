<?php

namespace Src\Accounting\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Invoice;
use App\Models\Receipt;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Facades\Pdf;
use Src\Accounting\Domain\Repository\Interfaces\InvoiceRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class ReportCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function index(Request $request)
    {
        $request->validate([
            'type' => ['required', 'in:sales,debtors,invoice,receipt,pos'],
            'identifier' => ['required'],
        ]);

        $getView = match ($request->type) {
            'sales' => 'pdf-template.sales',
            'debtors' => 'pdf-template.debtors',
            'receipt' => 'pdf-template.receipt',
            'invoice' => 'pdf-template.invoice',
        };

        $getModel = match ($request->type) {
            'sales','debtors','invoice' => Invoice::query(),
            'receipt' => Receipt::query(),
        };

        $getModel->findOrFail($request->identifier);

        $filename = generateTransactionReference();

        return Pdf::view('pdf-template.receipt',['welcome'])
            ->withBrowsershot(function (Browsershot $browsershot) {
                $browsershot->setNodeBinary(config('app.which_node'))
                    ->setNpmBinary(config('app.which_npm'));
            })->save($filename.'.pdf');
    }
}
