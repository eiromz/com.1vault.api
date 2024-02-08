@extends('pdf-template.layout')

@section('title', 'Page Title')

@section('content')
    <div class="px-14 py-6">
        <table class="w-full border-collapse border-spacing-0">
            <tbody>
                <tr class="border-b-2">
                    <td class="w-full align-top">
                        <div>
                            <h1 class="font-weight-bolder font-40px pl-2">INVOICE</h1>
                            <p class="font-weight-bolder py-3 pl-2">{{$data->invoiceNumber}}</p>
                        </div>
                    </td>
                </tr>
                <tr class="py-6">
                    <td class="w-full align-top">
                        <div>
                            <img
                                src="https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1/docs/logo.png"
                                class="h-12" />
                        </div>
                    </td>

                    <td class="w-full align-top">
                        <div class="text-sm">
                            <table class="border-collapse border-spacing-0">
                                <tbody>
                                <tr>
                                    <td class="border-r pr-4">
                                        <div>
                                            <p class="whitespace-nowrap text-black text-right">Invoice Date</p>
                                            <p class="whitespace-nowrap font-bold text-main text-right">
                                                {{ $data->invoice_date->format('l jS F Y') }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="pl-4">
                                        <div>
                                            <p class="whitespace-nowrap text-black text-right">Due Date</p>
                                            <p class="whitespace-nowrap font-bold text-main text-right">
                                                {{ $data->due_date->format('l jS F Y') }}
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>

                </tr>
            </tbody>
        </table>
    </div>

    <div class="bg-slate-100 px-14 py-6 pt-5 text-sm">
        <table class="w-full border-collapse border-spacing-0">
            <tbody>
            <tr>
                <td class="w-1/2 align-top">
                    <div class="text-sm text-neutral-600">
                        <p class="font-bold">Billed to</p>
                        <p>{{ $data->client->fullname ?? 'N/A'}}</p>
                        <p>{{ $data->client->address ?? 'N/A'}}</p>
                        <p>{{ $data->client->phone_number ?? 'N/A'}}</p>
                    </div>
                </td>
                <td class="w-1/2 align-top text-right">
                    <div class="text-sm text-neutral-600">
                        <p class="font-bold">From</p>
                        <p>{{ $data->business->fullname ?? 'N/A'}}</p>
                        <p>{{ $data->business->address ?? 'N/A'}}</p>
                        <p>{{ $data->business->email ?? 'N/A'}}</p>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="px-14 py-10 text-sm text-neutral-700">
        <table class="w-full border-collapse border-spacing-0">
            <thead>
            <tr>
                <td class="border-b-2 border-main pb-3 pl-3 font-bold text-main">#</td>
                <td class="border-b-2 border-main pb-3 pl-2 font-bold text-main">Item</td>
                <td class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">Qty.</td>
                <td class="border-b-2 border-main pb-3 pl-2 text-right font-bold text-main">Rate</td>
                <td class="border-b-2 border-main pb-3 pl-2 text-right font-bold text-main">Amount</td>
            </tr>
            </thead>
            <tbody>
          @foreach($data->items as $item)
              <tr>
                  <td class="border-b py-3 pl-3">1.</td>
                  <td class="border-b py-3 pl-2">{{ $item['name'] }}</td>
                  <td class="border-b py-3 pl-2 text-center">{{ $item['quantity'] }}</td>
                  <td class="border-b py-3 pl-2 text-right">{{ $item['unit'] }}</td>
                  <td class="border-b py-3 pl-2 pr-3 text-right">{{ $item['amount'] }}</td>
              </tr>
          @endforeach
            <tr>
                <td colspan="7">
                    <table class="w-full border-collapse border-spacing-0">
                        <tbody>
                        <tr>
                            <td class="w-full"></td>
                            <td>
                                <table class="w-full border-collapse border-spacing-0">
                                    <tbody>
                                    <tr>
                                        <td class="border-b p-3">
                                            <div class="whitespace-nowrap text-slate-400">Subtotal:</div>
                                        </td>
                                        <td class="border-b p-3 text-right">
                                            <div class="whitespace-nowrap font-bold text-main">$320.00</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class=" border-b p-3">
                                            <div class="whitespace-nowrap text-slate-400">Tax:</div>
                                        </td>
                                        <td class="border-b p-3 text-right">
                                            <div class="whitespace-nowrap font-bold text-main">$64.00</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class=" border-b p-3">
                                            <div class="whitespace-nowrap text-slate-400">Discount:</div>
                                        </td>
                                        <td class="border-b p-3 text-right">
                                            <div class="whitespace-nowrap font-bold text-main">$64.00</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-b p-3">
                                            <div class="whitespace-nowrap text-slate-400">Shipping fee:</div>
                                        </td>
                                        <td class="border-b p-3 text-right">
                                            <div class="whitespace-nowrap font-bold text-main">$384.00</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bg-main p-3">
                                            <div class="whitespace-nowrap font-bold text-white">Total amount:</div>
                                        </td>
                                        <td class="bg-main p-3 text-right">
                                            <div class="whitespace-nowrap font-bold text-white">$384.00</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-b p-3">
                                            <div class="whitespace-nowrap text-slate-400">Amount received:</div>
                                        </td>
                                        <td class="border-b p-3 text-right">
                                            <div class="whitespace-nowrap font-bold text-main">$384.00</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-b p-3">
                                            <div class="whitespace-nowrap text-slate-400">Balance Due:</div>
                                        </td>
                                        <td class="border-b p-3 text-right">
                                            <div class="whitespace-nowrap font-bold text-main">$384.00</div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="px-14 text-sm text-neutral-700">
        <p class="text-main font-bold">Thanks For Shopping</p>
    </div>
@endsection
