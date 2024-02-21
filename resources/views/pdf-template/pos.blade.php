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
                            <h1 class="font-weight-bolder font-40px pl-2">POS REQUEST</h1>
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

    </div>
@endsection
