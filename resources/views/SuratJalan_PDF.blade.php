<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Jalan-{{ $order["delivery_order"] }}-{{ $order["customer"]["name"] }}</title>
    <style>
        .text-center {
            text-align: center
        }

        h2 {
            margin: 25px 0 0 0;
            padding: 0;
        }

        p {
            margin: 0;
            padding: 0 0 20px 0;
        }

        .text-sm {
            font-size: .8em;
        }

        .font-semibold {
            font-weight: 500
        }
    </style>
</head>
<body>
    <div style="width:100%;display:relative">
        <table border="1" cellspacing="0" cellpadding="5" style="width:300px;display:relative;margin-left:400px">
            <tbody>
                <tr>
                    <td class="font-semibold">Tanggal:</td>
                    <td>{{ $now }}</td>
                </tr>
                <tr>
                    <td class="font-semibold">Kepada:</td>
                    <td>
                        <div class="font-semibold" style="padding-left: .25em">
                            {{ $order["customer"]["name"] }}
                        </div>
                        <div class="text-sm" style="padding-left: .25em">
                            {{ $order["customer"]["address"] }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <table cellspacing="0" style="position:absolute;top:0;">
            <tbody>
                <tr><td rowspan="4" style="padding: 0 1em 0 0"><img src="{{ public_path('assets/images/test.jpg') }}" style="height: 75px"></td><td class="font-semibold"><h3 style="padding: 0;margin:0 ;">PT. BUMI ISAM MANDIRI</h3></td></tr>
                <tr><td style="font-size: .9em">Jalan Kehakiman XI No. C-13 Tanah Tinggi</td></tr>
                <tr><td style="font-size: .9em">Kota Tangerang, Banten - 15119</td></tr>
                <tr><td style="font-size: .9em">Telp: 0821-2212-1913 Email: info@ptbim.co.id</td></tr>
            </tbody>
        </table>
    </div>
    <h2 class="text-center">SURAT JALAN</h2>
    <p class="text-center">No : {{ $order["delivery_order"] }}</p>
    <table border="1" cellspacing="0" cellpadding="5" style="width: 100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Deskripsi Barang</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order["orders"] as $order)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td><span class="font-semibold">{{ $order["product"]["name"] }}</span><br>{{ $order["product"]["dimension"] }}</td>
                <td class="text-center">{{ $order["qty"] }}</td>
                <td class="text-center">{{ $order["product"]["unit"] }}</td>
                <td>{{ $order["keterangan"] }}</td>
                @php
                    $total_product += $order["qty"]
                @endphp
            </tr>
            @endforeach
            <tr>
                <td colspan="2" class="text-center">Total</td>
                <td class="text-center">{{ $total_product }}</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
    <br>
    <br>
    <div style="text-align: center; float:left;margin-left:50px">
        Hormat kami,
        <br>
        <br>
        <br>
        ______________
    </div>
    <div style="text-align: center; float:right;margin-right:50px">
        Diterima oleh,
        <br>
        <br>
        <br>
        ______________
    </div>
</body>
</html>