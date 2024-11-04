<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ $invoice->name }}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <style type="text/css" media="screen">
            html {
                font-family: sans-serif;
                line-height: 1.15;
                margin: 0;
            }

            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                font-weight: 400;
                line-height: 1.5;
                color: #212529;
                text-align: left;
                background-color: #fff;
                font-size: 10px;
                margin: 36pt;
            }

            h4 {
                margin-top: 0;
                margin-bottom: 0.5rem;
            }

            p {
                margin-top: 0;
                margin-bottom: 1rem;
            }

            strong {
                font-weight: bold;
            }

            img {
                vertical-align: middle;
                border-style: none;
            }

            table {
                border-collapse: collapse;
            }

            th {
                text-align: inherit;
            }

            h4, .h4 {
                margin-bottom: 0.5rem;
                font-weight: 500;
                line-height: 1.2;
            }

            h4, .h4 {
                font-size: 1.5rem;
            }

            .table {
                width: 100%;
                margin-bottom: 1rem;
                color: #212529;
            }

            .table th,
            .table td {
                padding: 0.10rem;
                padding: 0.10rem;
                vertical-align: top;
            }

            .table.table-items td {
                border-top: 1px solid #dee2e6;
            }

            .table thead th {
                vertical-align: bottom;
                border-bottom: 2px solid #dee2e6;
            }

            .mt-5 {
                margin-top: 3rem !important;
            }
            .mt-1 {
                margin-top: 1rem !important;
            }

            .pr-0,
            .px-0 {
                padding-right: 0 !important;
            }

            .pl-0,
            .px-0 {
                padding-left: 0 !important;
            }

            .text-right {
                text-align: right !important;
            }

            .text-center {
                text-align: center !important;
            }

            .text-uppercase {
                text-transform: uppercase !important;
            }
            * {
                font-family: "DejaVu Sans";
            }
            body, h1, h2, h3, h4, h5, h6, table, th, tr, td, p, div {
                line-height: 1.1;
            }
            .party-header {
                font-size: 1.5rem;
                font-weight: 400;
            }
            .total-amount {
                font-size: 12px;
                font-weight: 700;
            }
            .border-0 {
                border: none !important;
            }
            .cool-gray {
                color: #6B7280;
            }

            .b3 {
  border-style: dotted;
}
.b4 {
  border-style: dashed;
  border-color:#6B7280;
  margin:0;
  padding:0;

}

.clase_table {
        border-collapse: separate;
        border-spacing: 7;
        border: 1px solid black;
        border-color:#6B7280;
        border-radius: 15px;
        -moz-border-radius: 20px;
        padding: 0;
        margin: 0;
    }

    .clearfix:after{
        content:"";
        display:table;
        clear:both
    }
    a{
        color:#375bc8;
        text-decoration:underline
    }
    body{
        position:relative;
        {% comment %} width:21cm;
        height:29.7cm; {% endcomment %}
        margin:0 auto;
        color:#3A3A3A;
        background:#FFFFFF;
        font-family:sans-serif;font-size:14px
    }
    header{
        padding:10px 0;
        margin-bottom:30px
    }
    #logo{
        text-align:right;
        margin-bottom:30px
    }
    #invoice-logo{
        max-height:125px;
        text-align:right
    }
    .invoice-title{
        color:#0380b2;
        font-size:2em;
        line-height:1em;
        font-weight:normal;
        margin:20px 0
    }
    .remission-title{
        color:#0380b2;
        font-size:2em;
        line-height:1.4em;
        font-weight:normal;
        margin:0;
    }
    #client{
        float:right;
        text-align:right;
        width:40%
    }
    #company{
        float:left;
        width:55%;margin-right:5%
    }
    .invoice-details{
        text-align:right
    }
    .invoice-details table{
        border-collapse:collapse;
        border-spacing:0;
        text-align:right;
        width:40%;
        margin:0 0 0 auto;
        font-size:12px
    }
    .invoice-details table td{
        width:auto;
        margin:0;
        padding:0 0 0.5em 0
    }
    table.item-table{
        width:100%;
        border-collapse:collapse;
        border-spacing:0;
        margin-bottom:20px;
        font-size:12px
    }
    table.item-table tr:nth-child(2n-1) td{
        background:#F5F5F5
    }
    table.item-table th{
        padding:10px 15px;
        border-bottom:1px solid #606060;
        white-space:nowrap;
        text-align:left
    }
    table.item-table th.text-right{
        text-align:right
    }
    table.item-table td{
        padding:10px 15px
    }
    table.item-table .invoice-sums{
        text-align:right
    }
    footer{
        color:#878686;
        width:100%;
        border-top:2px solid #878686;
        padding:8px 0
    }
    .text-right{
        text-align:right
    }
    .text-red{
        color:#ea5340
    }
    .text-green{
        color:#0380b2
    }

    .text-warning{
        color:#ffbb00
    }
    .cool-warning {
        color: #ffbb00;
    }
    .cool-red {
        color:#ea5340;
    }
    .cool-green {
        color:#77b632;
    }
    .title-data{
        font-size:14px;
        text-transform: uppercase !important;
    }
    hr{
        border-color: gray;
        {% comment %} color: #6B7280; {% endcomment %}
    }
    .hr {
        margin-top:4rem;
        width: 50%;
        margin-right: auto;
        margin-left: auto;
        border: 1px solid #4B4B4B;
    }

    .box-disclaimer{
        border: gray 1px solid;
        font-size:14px;
        text-transform: uppercase !important;
        font-weight: bold;
        background: #ADD8E6;
        padding: 0;
        margin: 0;
    }


        </style>
    </head>

    <body>
        {{-- Header --}}

        <table style="margin-bottom:0px;" class="table mt-1">
            <tbody>
                <tr>
                    <td class="pl-0 border-0" width="15%">
                       @if($invoice->logo)
                            <img src="{{ $invoice->getLogo() }}" alt="logo" height="50">

                        @endif
                    </td>
                    <td class="pl-0 border-0" width="70%">
                        <h4 class="text-center text-uppercase invoice-title">
                            <strong>{{ $invoice->name }}</strong>
                        </h4>
                    </td>
                    <td class="clearfix pl-0 border-0 invoice-details"  width="15%">
                        <p > <strong class="remission-title text-green">{{ $invoice->getSerialNumber() }}</strong>
                        <b> <br>{{ __('invoices::invoice.remission') }}</b>
                        </p>


                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Seller - Buyer --}}
        <table class="table clase_table b4">

            <tbody>
                <tr>
                    <td id="company" class="px-0">
                        @if($invoice->buyer->name)
                        <strong class="title-data">{{ $invoice->buyer->name }}</strong>

<br>
                        @endif

                        @if($invoice->buyer->vat)

                                {{ __('invoices::invoice.vat') }}: <b>{{ $invoice->buyer->vat }}</b>
<br>
                        @endif

                        @if($invoice->buyer->code)

                                {{ __('invoices::invoice.code') }}: <b>{{ $invoice->buyer->code }}</b>
<br>
                        @endif

                        @if($invoice->buyer->address)

                                {{ __('invoices::invoice.address') }}: <b>{{ $invoice->buyer->address }}</b>
<br>
                        @endif

                        @if($invoice->buyer->city)

                                {{ __('invoices::invoice.city') }}: <b>{{ $invoice->buyer->city }}</b>
<br>
                        @endif

                        @if($invoice->buyer->phone)

                                {{ __('invoices::invoice.phone') }}: <b>{{ $invoice->buyer->phone }}</b>
<br>
                        @endif

                        @foreach($invoice->buyer->custom_fields as $key => $value)

                                {{ ucfirst($key) }}: <b>{{ $value }}</b>
                                <br>
                                @endforeach

                    </td>
                    <td class="border-0"></td>
                    <td  id="client" class="px-0">


                            <p class="buyer-name">
                                @if($invoice->status)
                                    <strong class="title-data text-green">{{ $invoice->status }}</strong>
                                <br>
                                @endif


                            {{ __('invoices::invoice.date') }}: <strong>{{ $invoice->getDate() }}</strong><br>
                        {{ __('invoices::invoice.due_date') }}: <strong>{{ $invoice->getPayUntilDate() }}</strong>
                        @foreach($invoice->seller->custom_fields as $key => $value)
                                @if($key == 'vendedor')
                                {{ ucfirst($key) }}: <b>{{ $value }}</b>
                                @endif
                                <br>
                                @endforeach
                        {{ __('invoices::invoice.amount_due') }}: <strong class="text-green title-data">{{ $invoice->formatCurrency($invoice->total_amount) }}</strong>



                            </p>
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Table --}}

        <hr>
        <table class="table table-items">

            <thead>
                <tr>
                    <th scope="col" class="pl-0 border-0">#</th>
                    @if($invoice->hasItemReference)
                    <th scope="col" class="pl-0 border-0">{{ __('invoices::invoice.reference') }}</th>
                    @endif
                    @if($invoice->hasItemCode)
                    <th scope="col" class="pl-0 border-0">{{ __('invoices::invoice.code') }}</th>
                    @endif
                    <th scope="col" class="pl-0 border-0">{{ __('invoices::invoice.description') }}</th>
                    @if($invoice->hasItemUnits)
                        <th scope="col" class="text-center border-0">{{ __('invoices::invoice.units') }}</th>
                    @endif
                    <th scope="col" class="text-center border-0">{{ __('invoices::invoice.quantity') }}</th>
                    <th scope="col" class="text-right border-0">{{ __('invoices::invoice.price') }}</th>
                    @if($invoice->hasItemDiscount)
                        <th scope="col" class="text-right border-0">{{ __('invoices::invoice.discount') }}</th>
                    @endif
                    @if($invoice->hasItemTax)
                        <th scope="col" class="text-right border-0">{{ __('invoices::invoice.tax') }}</th>
                    @endif
                    <th scope="col" class="pr-0 text-right border-0">{{ __('invoices::invoice.sub_total') }}</th>
                </tr>
            </thead>
            <tbody>
                {{-- Items --}}
                @php
                $count = 0;
                @endphp
                @foreach($invoice->items as $item)
                @php
                $count++;
                @endphp
                <tr>
                    <td class="pl-0">

                            <p class="cool-gray">{{ $count }}</p>

                    </td>
                    @if($invoice->hasItemReference)
                        <td class="pl-0">{{ $item->reference }}</td>
                    @endif
                    @if($invoice->hasItemCode)
                        <td class="pl-0">{{ $item->code }}</td>
                    @endif
                    <td class="pl-0">
                        {{ $item->title }}

                        @if($item->description)
                            <p class="cool-gray">{{ $item->description }}</p>
                        @endif
                    </td>
                    @if($invoice->hasItemUnits)
                        <td class="text-center">{{ $item->units }}</td>
                    @endif
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">
                        {{ $invoice->formatCurrency($item->price_per_unit) }}
                    </td>
                    @if($invoice->hasItemDiscount)
                        <td class="text-right">
                            {{ $invoice->formatCurrency($item->discount) }}
                        </td>
                    @endif
                    @if($invoice->hasItemTax)
                        <td class="text-right">
                            {{ $invoice->formatCurrency($item->tax) }}
                        </td>
                    @endif

                    <td class="pr-0 text-right">
                        {{ $invoice->formatCurrency($item->sub_total_price) }}
                    </td>
                </tr>
                @endforeach
                {{-- Summary --}}
                @if($invoice->hasItemOrInvoiceDiscount())
                    <tr>
                        <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
                        <td class="pl-0 text-right">{{ __('invoices::invoice.total_discount') }}</td>
                        <td class="pr-0 text-right">
                            {{ $invoice->formatCurrency($invoice->total_discount) }}
                        </td>
                    </tr>
                @endif
                @if($invoice->taxable_amount)
                    <tr>
                        <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
                        <td class="pl-0 text-right">{{ __('invoices::invoice.taxable_amount') }}</td>
                        <td class="pr-0 text-right">
                            {{ $invoice->formatCurrency($invoice->taxable_amount) }}
                        </td>
                    </tr>
                @endif
                @if($invoice->tax_rate)
                    <tr>
                        <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
                        <td class="pl-0 text-right">{{ __('invoices::invoice.tax_rate') }}</td>
                        <td class="pr-0 text-right">
                            {{ $invoice->tax_rate }}%
                        </td>
                    </tr>
                @endif
                @if($invoice->hasItemOrInvoiceTax())
                    <tr>
                        <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
                        <td class="pl-0 text-right">{{ __('invoices::invoice.total_taxes') }}</td>
                        <td class="pr-0 text-right">
                            {{ $invoice->formatCurrency($invoice->total_taxes) }}
                        </td>
                    </tr>
                @endif
                @if($invoice->shipping_amount)
                    <tr>
                        <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
                        <td class="pl-0 text-right">{{ __('invoices::invoice.shipping') }}</td>
                        <td class="pr-0 text-right">
                            {{ $invoice->formatCurrency($invoice->shipping_amount) }}
                        </td>
                    </tr>
                @endif
                    <tr>
                        <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
                        <td class="pl-0 title-data text-right"><strong>{{ __('invoices::invoice.total_amount') }}</strong></td>
                        <td class="pr-0 text-right total-amount">
                            {{ $invoice->formatCurrency($invoice->total_amount) }}
                        </td>
                    </tr>
            </tbody>
        </table>

        <table class="table">
            <thead>
                <th>
                    <td colspan="{{ $invoice->table_columns - 2 }}" width="60%" class="border-0" text-center><hr class="hr"></td>
                </th>
            </thead>
            <tbody>
                <th>
                    <td class="text-center">FIRMA, SELLO Y FECHA DE RECIBO</td>
                </th>
            <tbody>
        </table>

        <p class="clase_table text-uppercase">
            <b>{{ __('invoices::invoice.amount_in_words') }}:</b> {{ $invoice->getTotalAmountInWords() }}
        </p>



            <p class="box-disclaimer">
                ESTIMADO CLIENTE DESPUES DE OCHO DIAS CALENDARIO NO SE ACEPTAN RECLAMOS NI
                DEVOLUCIONES
            </p>

        @if($invoice->notes)
            <p class="clase_table text-uppercase">
                <b>{{ __('invoices::invoice.notes') }}:</b> {!! $invoice->notes !!}
            </p>
        @endif




        <script type="text/php">
            if (isset($pdf) && $PAGE_COUNT > 1) {
                $text = "{{ __('invoices::invoice.page') }} {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width);
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>
    </body>
</html>
