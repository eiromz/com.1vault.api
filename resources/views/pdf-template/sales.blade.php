@extends('pdf-template.layout')

@section('title', 'Sales')

@section('content')
        <div class="py-4">
            <div class="px-14 py-6">
                <table class="w-full border-collapse border-spacing-0">
                    <tbody>
                        <tr class="border-b">
                            <td class="w-full align-top pb-3">
                                <div>
                                    <h1 class="font-weight-bolder font-40px pl-2">SALES REPORT</h1>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="w-full align-top py-3">
                                <div>
                                    <img
                                        src="https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1/docs/logo.png"
                                        class="h-12" />
                                </div>
                            </td>

                            <td class="w-full align-top py-3">
                                <div class="text-sm">
                                    <table class="border-collapse border-spacing-0">
                                        <tbody>
                                        <tr>
                                            <td class="pr-4 py-3">
                                                <div>
                                                    <p class="whitespace-nowrap text-black text-right">Name of company</p>
                                                    <p class="whitespace-nowrap font-bold text-main text-right">Email address of company</p>
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


            <div class="px-14 py-10 text-sm text-neutral-700">
                <table class="w-full border-collapse border-spacing-0">
                    <thead>
                    <tr>
                        <td class="border-b-2 border-main pb-3 pl-3 font-weight-bolder text-main">#</td>
                        <td class="border-b-2 border-main pb-3 pl-2 font-weight-bolder text-main">Date</td>
                        <td class="border-b-2 border-main pb-3 pl-2 text-right font-bold text-main">Item Description</td>
                        <td class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">Revenue</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="border-b py-3 pl-3">1.</td>
                        <td class="border-b py-3 pl-2">Montly accountinc services</td>
                        <td class="border-b py-3 pl-2 text-right">$150.00</td>
                        <td class="border-b py-3 pl-2 text-center">1</td>
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
                                                    <div class="whitespace-nowrap font-bold text-white">Total:</div>
                                                </td>
                                                <td class="bg-main p-3 text-right">
                                                    <div class="whitespace-nowrap font-bold text-white">$384.00</div>
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
                <p class="text-main font-bold">DATE GENERATED</p>
                <p>Payment Reference: BRA-00335</p>
            </div>
        </div>
@endsection
