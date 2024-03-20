@extends('pdf-template.layout')

@section('title', '1VAULT')

@section('content')
    <div class="container bg-white p-5">
        <div class="flex flex-wrap items-center justify-between gap-6 mb-5">
            <div class="text-start">
                <h1 class="text-5xl font-normal font-bold pb-3">DEBTORS REPORT</h1>
            </div>
            <div class="text-end">
                <p class="font-sm font-normal">{{ $request->business->fullname ?? 'N/A'}}</p>
                <p class="font-sm font-normal">{{ $request->business->email ?? 'N/A'}}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="border-collapse table-auto w-full text-sm mt-14 whitespace-pre">
                <thead class="tbl_header_color text-white p-5">
                <tr class="border-b border-gray-900">
                    <th class="p-5 text-lg text-start">Customer Name</th>
                    <th class="p-5 text-lg">Invoice No.</th>
                    <th class="p-5 text-lg">Invoice Date</th>
                    <th class="p-5 text-lg">Invoice Amount</th>
                    <th class="p-5 text-lg">Balance Due</th>
                </tr>
                </thead>
                <tbody class="bg-white">
                @foreach($data as $item)
                    <tr>
                        <td class="p-5 text-start">{{ $item->client->fullname ?? 'N/A' }}</td>
                        <td class="p-5 text-center">{{ $item->invoiceNumber }}</td>
                        <td class="p-5 text-center">{{ $item->due_date }}</td>
                        <td class="p-5 text-center">&#8358 {{ $item->total }}</td>
                        <td class="p-5 text-end text-red-600">&#8358 {{ $item->balance_due }}</td>
                    </tr>
                @endforeach
                <tr class="border-b border-t border-gray-900">
                    <td  class="p-4 text-base font-normal text-start"></td>
                    <td class="p-4 text-base font-normal text-center"></td>
                    <td class="p-4 text-base font-normal text-center">
                        <span class="pe-10 font-bold">Total </span>
                    </td>
                    <td class="p-4 text-base font-normal text-center">
                         &#8358 {{ $data->sum('total') }}
                    </td>
                    <td  class="p-4 text-base font-normal text-end ">
                        &#8358 {{ $data->sum('balance_due') }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="flex flex-wrap justify-between gap-6 border-b border-gray-400 p-2">
            <p class="text-sm pt-2">Date Generated <br>{{ now()->isoFormat('LLLL') }}</p>
        </div>

        <div class="flex flex-wrap justify-between p-2">
            <img src="https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1/docs/logo.png" alt="" class="border-gray-300" width="150px">
        </div>
    </div>
@endsection
