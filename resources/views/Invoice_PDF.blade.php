<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice_{{ $purchase_order["order_code"] }}_{{ $purchase_order["customer"]["name"] }}</title>
    <style>
        .text-sm {
            font-size: .8em;
        }

        .font-semibold {
            font-weight: 500
        }
    </style>
</head>
<body>
    <div style="width:100%;position:relative;border-bottom:2px solid black; padding-bottom:1.5em;">
        <table cellspacing="0" cellpadding="5" style="width:150px;position:absolute;right:0;top:.5em;">
            <tbody>
                <tr>
                    <td class="font-semibold" colspan="2" style="text-align: center;">INVOICE</td>
                </tr>
                <tr>
                    <td class="font-semibold" style="text-align: center;">No:</td>
                    <td style="text-align: right;">{{ date_format(date_create($print_date),"m-y") . $document_number }}</td>
                </tr>
            </tbody>
        </table>
        <table cellspacing="0" style="position:relative;top:0;">
            <tbody>
                <tr><td rowspan="4" style="padding: 0 1em 0 0"><img src="assets/images/test.jpg" style="height: 75px"></td><td class="font-semibold"><h3 style="padding: 0;margin:0 ;">PT. BUMI ISAM MANDIRI</h3></td></tr>
                <tr><td style="font-size: .9em">Jalan Kehakiman XI No. C-13 Tanah Tinggi</td></tr>
                <tr><td style="font-size: .9em">Kota Tangerang, Banten - 15119</td></tr>
                <tr><td style="font-size: .9em">Telp: 0821-2212-1913 Email: info@ptbim.co.id Website: www.ptbim.co.id</td></tr>
            </tbody>
        </table>
    </div>

    <table border="1" cellspacing="0" cellpadding="5" style="width: 100%; padding-top:2em">
        <tbody>
            <tr>
                <td>Customer Name:</td>
                <td>Address:</td>
                <td>PO NO: <span class="font-semibold">{{ date_format(date_create($print_date),"my") }} - {{ $document_number }}</span></td>
            </tr>
            <tr>
                <td class="font-semibold">{{ $purchase_order["customer"]["name"] }}</td>
                <td class="font-semibold" style="width: 300px">{{ $purchase_order["customer"]["address"] }}</td>
                <td>Term of payment: <span class="font-semibold">{{ $purchase_order["customer"]["term"] }} days</span></td>
            </tr>
        </tbody>
    </table>

    <table border="1" cellspacing="0" cellpadding="5" style="width: 100%; padding-top:2em">
        <thead>
            <tr>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Description</th>
                <th>Unit Price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td style="text-align: center">{{ $order["qty"] }}</td>
                <td style="text-align: center">{{ $order["product"]["unit"] }}</td>
                <td><span class=" font-semibold">{{ $order["product"]["name"] }}</span><br>{{ $order["product"]["dimension"] }}</td>
                <td>Rp. {{ number_format($order["price"], 2, ',', '.') }}</td>
                <td>Rp. {{ number_format($order["amount"], 2, ',', '.') }}</td>
            </tr>
            @php
                $subtotal += $order["amount"]
            @endphp
            @endforeach
            <tr>
                <td colspan="3" rowspan="3"></td>
                <td>Subtotal</td>
                <td>Rp. {{ number_format($subtotal, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>VAT {{ $purchase_order["ppn"] }}%</td>
                <td>Rp. {{ number_format((0.01*$purchase_order["ppn"]*$subtotal), 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total</td>
                <td>Rp. {{ number_format((0.01*$purchase_order["ppn"]*$subtotal+$subtotal), 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
    <div style="margin-top:2.5em">
        <div style="float:right">Tangerang, {{ date_format(date_create($print_date),"d M Y") }}</div>
        <div class="font-semibold">
            <div>Please remit your payment in full amount to our bank</div>
            <div>Bank Mandiri cab. Tangerang Kisamaun</div>
            <div>Account no: 155-00-1077967-9</div>
            <div>Account name: PT. BUMI ISAM MANDIRI</div>
        </div>
    </div>
</body>
</html>