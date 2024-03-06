@extends('pdf-template.layout')

@section('title', 'Receipt')

@section('content')
    <div class="container bg-white p-5">
        <div class="flex flex-wrap items-center justify-between gap-6 mb-5">
            <div>
                <h1 class="text-5xl font-normal font-bold pb-3">RECEIPT</h1>
                <h6 class="text-base font-normal">{{$data->receiptNumber}}</h6>
            </div>
            <img src="{{ $data->business->logo ?? "https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1/docs/logo.png"}}" alt="">
        </div>
        <hr>
        <div class="flex flex-wrap items-center justify-between gap-6 mt-4">
            <div class="text-start">
                <div class="pb-2">
                    <h4 class="text-base font-bold">Issue Date:</h4>
                    <p class="font-sm font-normal"> {{ $data->transaction_date->format('l jS F Y') }} </p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-6 mt-4">
            <div class="text-start">
                <h4 class="text-base font-bold">Billed To</h4>
                <p class="font-sm font-normal">{{ $data->client->fullname ?? 'N/A'}}</p>
                <p class="font-sm font-normal text-truncate">{{ $data->client->address ?? 'N/A'}}</p>
                <p class="font-sm font-normal">{{ $data->client->phone_number ?? 'N/A'}}</p>
            </div>
            <div class="text-end">
                <h4 class="text-base font-bold">From</h4>
                <p class="font-sm font-normal">{{ $data->business->fullname ?? 'N/A'}}</p>
                <p class="font-sm font-normal text-truncate">{{ $data->business->address ?? 'N/A'}}</p>
                <p class="font-sm font-normal">{{ $data->business->email ?? 'N/A'}}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="border-collapse table-auto w-full text-sm mt-14 whitespace-pre">
                <thead>
                <tr class="border-b border-gray-900">
                    <th class="p5 text-lg text-start">Item</th>
                    <th class="p5 text-lg">Qty</th>
                    <th class="p5 text-lg">Rate</th>
                    <th class="p5 text-lg text-end">Amount</th>
                </tr>
                </thead>
                <tbody class="bg-white">
                @foreach($data->inventory as $item)
                    <tr>
                        <td class="text-start">{{ $item['name'] }}</td>
                        <td class="text-center">{{ $item['quantity'] }}</td>
                        <td class="text-center">{{ $item['amount'] }}</td>
                        <td class="text-end">{{ $item['subtotal'] }}</td>
                    </tr>
                @endforeach
                <tr class="border-b border-top border-gray-900">
                    <td colspan="5" class="text-base font-normal text-end">
                        <span class="pe-10 font-bold">Total Amount</span> &#8358 {{ $data->total }}
                    </td>
                </tr>
                <tr class="border-b">
                    <td colspan="5" class="text-base font-normal text-end">
                        <span class="pe-10 font-bold">Amount Received</span> &#8358 {{ $data->amount_received }}
                    </td>
                </tr>
                <tr class="border-b">
                    <td colspan="5" class="text-base font-normal text-end">
                        <span class="pe-10 font-bold">Balance Due</span> &#8358 {{ $data->balance_due }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="flex flex-wrap justify-between gap-6 border-b border-gray-400 p-2">
            <p class="text-sm pt-2">Thanks for shopping with us </p>
        </div>

        <div class="flex flex-wrap justify-between p-2">
            <img src="https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1/docs/logo.png" alt="" class="border-gray-300" width="150px">
        </div>
    </div>


@endsection
