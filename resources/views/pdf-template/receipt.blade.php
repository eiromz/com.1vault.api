@extends('pdf-template.layout')

@section('title', 'Receipt')

@section('content')
    <div class="px-14 py-6">
        <table class="w-full border-collapse border-spacing-0">
            <tbody>
            <tr>
                <td class="w-full align-top">
                    <div class="pb-5">
                        <h1 class="font-weight-bolder font-40px pl-2">RECEIPT</h1>
                        <p class="font-weight-bolder py-3 pl-2">REC0001</p>
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
                                        <p class="whitespace-nowrap text-black text-right">Issue Date</p>
                                        <p class="whitespace-nowrap font-bold text-main text-right">April 26, 2023</p>
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
                        <p>Number: 23456789</p>
                        <p>VAT: 23456789</p>
                        <p>6622 Abshire Mills</p>
                        <p>Port Orlofurt, 05820</p>
                        <p>United States</p>
                    </div>
                </td>
                <td class="w-1/2 align-top text-right">
                    <div class="text-sm text-neutral-600">
                        <p class="font-bold">From</p>
                        <p>Number: 123456789</p>
                        <p>VAT: 23456789</p>
                        <p>9552 Vandervort Spurs</p>
                        <p>Paradise, 43325</p>
                        <p>United States</p>
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
            <tr>
                <td class="border-b py-3 pl-3">1.</td>
                <td class="border-b py-3 pl-2">Monthly accounting services</td>
                <td class="border-b py-3 pl-2 text-center">1</td>
                <td class="border-b py-3 pl-2 text-right">$150.00</td>
                <td class="border-b py-3 pl-2 pr-3 text-right">$150.00</td>
            </tr>
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
