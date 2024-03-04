@extends('pdf-template.layout')

@section('title', 'Sales')

@section('content')
    <div class="container bg-white p-5">
        <div class="flex flex-wrap items-center justify-between gap-6 mb-5">
            <div class="text-start">
                <h1 class="text-5xl font-normal font-bold pb-3">SALES REPORT</h1>
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
                    <th class="p-5 text-lg text-start">Date</th>
                    <th class="p-5 text-lg">Item Description</th>
                    <th class="p-5 text-lg text-end">Revenue</th>
                </tr>
                </thead>
                <tbody class="bg-white">
                @foreach($data as $item)
                    <tr>
                        <td class="p-5 text-start">{{ $item->client->fullname ?? 'N/A' }}</td>
                        <td class="p-5 text-center text-truncate">{{ collect($item->items)?->pluck('name')?->implode(',') ?? 'N/A' }}</td>
                        <td class="p-5 text-end">{{ $item->total }}</td>
                    </tr>
                @endforeach
                <tr class="">
                    <td  class="p-4 text-base font-normal text-start"></td>
                    <td class="p-4 text-base font-normal text-center"></td>
                    <td class="p-4 text-base font-normal text-end border-b border-t border-gray-900">
                        <span class="font-bold">Total Revenue </span>  &#8358 {{ $data->sum('total') }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="flex flex-wrap justify-between gap-6 border-b border-gray-400 p-2">
            <p class="text-sm pt-2">Date Generated <br> {{ now()->diffForHumans() }}</p>
        </div>

        <div class="flex flex-wrap justify-between p-2">
            <img src="https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1/docs/logo.png" alt="" class="border-gray-300" width="150px">
        </div>
    </div>
@endsection
