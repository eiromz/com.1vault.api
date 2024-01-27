@extends('pdf-template.layout')

@section('title', 'Page Title')

@section('content')
    <div class="px-14 py-6">
        <table class="w-full border-collapse border-spacing-0">
            <tbody>
                <tr class="border-b-2">
                    <td class="w-full align-top">
                        <div>
                            <h1 class="font-weight-bolder font-40px pl-2">REPORT GENERATION FAILED</h1>
                            <p class="font-weight-bolder py-3 pl-2">INV0001</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
