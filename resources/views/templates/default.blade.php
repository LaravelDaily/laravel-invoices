<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ $invoice->name }}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <link rel="stylesheet" href="{{ asset('vendor/LaravelDaily/css/bootstrap.min.css') }}">

        <style type="text/css" media="screen">
            html {
                margin: 0;
            }
            body {
                font-size: 0.6875rem;
                margin: 48pt;
            }
            body, h1, h2, h3, h4, h5, h6, table, th, tr, td, p, div {
                font-family: sans-serif;
                line-height: 1.1;
            }
            .total-amount {
                font-size: 12px;
                font-weight: 700;
            }
        </style>
    </head>

    <body>
        {{-- Header --}}
        <div style="clear:both;position:relative;margin-top:48pt;overflow:hidden">
            <div style="position:absolute;left:0;width:250pt">
                <h4 class="text-uppercase">
                    <strong>{{ $invoice->name }}</strong>
                </h4>
            </div>

            <div style="margin-left:373pt">
                <p>{{ __('invoices::invoice.serial') }} <strong>{{ $invoice->getSerialNumber() }}</strong></p>
                <p>{{ __('invoices::invoice.date') }}: <strong>{{ $invoice->getDate() }}</strong></p>
            </div>
        </div>


        {{-- Seller - Buyer --}}
        <div style="clear:both; position:relative;margin-top:24pt;overflow:hidden">
            <div style="position:absolute; left:0; width:237pt;">
                <h2>{{ __('invoices::invoice.seller') }}</h2>
                <hr>
                @if($invoice->seller->name)
                    <p class="seller-name">
                        <strong>{{ $invoice->seller->name }}</strong>
                    </p>
                @endif

                @if($invoice->seller->address)
                    <p class="seller-address">
                        {{ __('invoices::invoice.address') }}: {{ $invoice->seller->address }}
                    </p>
                @endif

                @if($invoice->seller->code)
                    <p class="seller-code">
                        {{ __('invoices::invoice.code') }}: {{ $invoice->seller->code }}
                    </p>
                @endif

                @if($invoice->seller->vat)
                    <p class="seller-vat">
                        {{ __('invoices::invoice.vat') }}: {{ $invoice->seller->vat }}
                    </p>
                @endif

                @if($invoice->seller->phone)
                    <p class="seller-phone">
                        {{ __('invoices::invoice.phone') }}: {{ $invoice->seller->phone }}
                    </p>
                @endif

                @foreach($invoice->seller->custom_fields as $key => $value)
                    <p class="seller-custom-field">
                        {{ ucfirst($key) }}: {{ $value }}
                    </p>
                @endforeach
            </div>
            <div style="margin-left:261pt; width:237pt;">
                <h2>{{ __('invoices::invoice.buyer') }}</h2>
                <hr>
                @if($invoice->buyer->name)
                    <p class="buyer-name">
                        <strong>{{ $invoice->buyer->name }}</strong>
                    </p>
                @endif

                @if($invoice->buyer->address)
                    <p class="buyer-address">
                        {{ __('invoices::invoice.address') }}: {{ $invoice->buyer->address }}
                    </p>
                @endif

                @if($invoice->buyer->code)
                    <p class="buyer-code">
                        {{ __('invoices::invoice.code') }}: {{ $invoice->buyer->code }}
                    </p>
                @endif

                @if($invoice->buyer->vat)
                    <p class="buyer-vat">
                        {{ __('invoices::invoice.vat') }}: {{ $invoice->buyer->vat }}
                    </p>
                @endif

                @if($invoice->buyer->phone)
                    <p class="buyer-phone">
                        {{ __('invoices::invoice.phone') }}: {{ $invoice->buyer->phone }}
                    </p>
                @endif

                @foreach($invoice->buyer->custom_fields as $key => $value)
                    <p class="buyer-custom-field">
                        {{ ucfirst($key) }}: {{ $value }}
                    </p>
                @endforeach
            </div>
        </div>


        {{-- Table --}}
        <div style="clear:both; position:relative; margin-top:24pt; width: 499pt">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" class="border-0 pl-0">{{ __('invoices::invoice.service') }}</th>
                        @if($invoice->hasUnits)
                            <th scope="col" class="text-center border-0">{{ __('invoices::invoice.units') }}</th>
                        @endif
                        <th scope="col" class="text-center border-0">{{ __('invoices::invoice.quantity') }}</th>
                        <th scope="col" class="text-right border-0">{{ __('invoices::invoice.price') }}</th>
                        @if($invoice->hasDiscount)
                            <th scope="col" class="text-center border-0">{{ __('invoices::invoice.discount') }}</th>
                        @endif
                        <th scope="col" class="text-right border-0 pr-0">{{ __('invoices::invoice.sub_total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                    <tr>
                        <td class="pl-0">{{ $item->title }}</td>
                        @if($invoice->hasUnits)
                            <td class="text-center">{{ $item->units }}</td>
                        @endif
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">
                            {{ $invoice->formatCurrency($item->price_per_unit) }}
                        </td>
                        @if($invoice->hasDiscount)
                            <td class="text-center">
                                {{ $invoice->formatCurrency($item->discount) }}
                            </td>
                        @endif
                        <td class="text-right pr-0">
                            {{ $invoice->formatCurrency($item->sub_total_price) }}
                        </td>
                    </tr>
                    @endforeach
                    @if($invoice->hasDiscount)
                        <tr>
                            <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
                            <td class="text-right pl-0">{{ __('invoices::invoice.total_discount') }}</td>
                            <td class="text-right pr-0">
                                {{ $invoice->formatCurrency($invoice->total_discount) }}
                            </td>
                        </tr>
                    @endif
                        <tr>
                            <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
                            <td class="text-right pl-0">{{ __('invoices::invoice.total_amount') }}</td>
                            <td class="text-right pr-0 total-amount">
                                {{ $invoice->formatCurrency($invoice->total_amount) }}
                            </td>
                        </tr>
                </tbody>
            </table>
            <p>{{ trans('invoices::invoice.amount_in_words') }}: {{ $invoice->getTotalAmountInWords() }}</p>
        </div>

        {{-- Footer --}}
        <div style="clear:both; position:relative; margin-top:24pt">
            <p>{{ trans('invoices::invoice.pay_until') }}: {{ $invoice->getPayUntilDate() }}</p>
        </div>
        <script type="text/php">
            if (isset($pdf) && $PAGE_COUNT > 1) {
                $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
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
