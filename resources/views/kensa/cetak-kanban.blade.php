<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="widtd=180px, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Kanban</title>
    <style>
        html,
        a,
        abbr,
        acronym,
        address,
        applet,
        article,
        aside,
        audio,
        b,
        big,
        blockquote,
        body,
        canvas,
        caption,
        center,
        cite,
        code,
        dd,
        del,
        details,
        dfn,
        div,
        dl,
        dt,
        em,
        embed,
        fieldset,
        figcaption,
        figure,
        footer,
        form,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        header,
        hgroup,
        html,
        i,
        iframe,
        img,
        ins,
        kbd,
        label,
        legend,
        li,
        mark,
        menu,
        nav,
        object,
        ol,
        output,
        p,
        pre,
        q,
        ruby,
        s,
        samp,
        section,
        small,
        span,
        strike,
        strong,
        sub,
        summary,
        sup,
        table,
        tbody,
        td,
        tfoot,
        td,
        tdead,
        time,
        tr,
        tt,
        u,
        ul,
        var,
        video {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline
        }

        article,
        aside,
        details,
        figcaption,
        figure,
        footer,
        header,
        hgroup,
        menu,
        nav,
        section {
            display: block
        }

        body {
            line-height: 1
        }

        ol,
        ul {
            list-style: none
        }

        blockquote,
        q {
            quotes: none
        }

        blockquote:after,
        blockquote:before,
        q:after,
        q:before {
            content: '';
            content: none
        }

        table {
            border-collapse: collapse;
            border-spacing: 0
        }

        html {
            background-color: #fff;
            width: 100%
        }

        body {
            box-sizing: border-box;
            font-family: serif;
            padding: 0;
            margin: 0;
            width: 100%
        }

        table {
            position: absolute;
            width: 301.711pt;
            height: 216.772;
            border: solid 1px #000;
            top: 50%;
            left: 50%;
            margin: -108.386pt 0 0 -150.855pt
        }

        table td {
            border: solid 1px #000;
            text-align: center;
            vertical-align: middle;
            padding: 5px
        }

        .text-left {
            text-align: left
        }

        .text-strong {
            font-weight: 700
        }

        font {
            display: block
        }

        .serif {
            font-family: sans-serif
        }

        .d-block {
            display: block
        }

        .centring {
            display: table;
            margin-left: auto;
            margin-right: auto;
            height: 30px;
            margin-bottom: 5px
        }

        .text-white {
            color: #fff !important;
        }

        .bg-dark {
            background-color: #000000 !important;
        }

        .bg-secondary {
            background-color: #808080 !important;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td colspan="3">
                <font size="3" class="text-strong">PT. SAKAE RIKEN INDONESIA</font>
            </td>
            <td rowspan="2">
                <font size="6" class="text-strong serif">{{ $no_kartu }}</font>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <font size="3" class="text-strong">PART TAG</font>
            </td>
        </tr>
        <tr>
            @php
            $no_parts = $pengiriman->no_part;
            $gabungan = $no_parts . '-' . $qty;
            $gabungans = '_____' . $gabungan . '_____';
            @endphp

            <td colspan="3">
                <font size="1" class="text-left"> PART NO. </font>
                <div class="centring"> {!! DNS2D::getBarcodeHTML($gabungan, 'QRCODE', 2, 2) !!}
                    {{-- <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($gabungans, 'C39') }}"
                        alt="{{ $pengiriman->no_part }}" width="180" height="30"> --}}
                </div>
                <font size="1"> {{ $gabungan }}
            </td>

            @if ($pengiriman->bagian == 'LH')
            <td colspan="1" class="bg-dark">
                <font size="5" class="text-strong serif">
                    <p class="text-white ">LH</p>
                </font>
            </td>
            @elseif($pengiriman->bagian == 'RH')
            <td colspan="1">
                <font size="5" class="text-strong serif">
                    RH
                </font>
            </td>
            @elseif($pengiriman->bagian == '-')
            <td colspan="1">
                <font size="5" class="text-strong serif">
                    -
                </font>
            </td>
            @endif
        </tr>
        <tr>

            <td colspan="4">
                <font size="2" class="text-left">PART NAME</font>
                <font size="4" class="text-strong serif">{{ $pengiriman->part_name }}</font>
            </td>

        </tr>
        <tr>

            <td colspan="4">
                <font size="2" class="text-left">NEXT PROCESS</font>
                <font size="4" class="text-strong serif">{{ $pengiriman->next_process }}</font>
            </td>


        </tr>
        @if ($pengiriman->next_process == 'PPIC')
        <tr>
            <td>QTY</td>
            <td>LOT NO.</td>
            <td>PIC PACK</td>
            <td>D. CHECK</td>
        </tr>
        @elseif($pengiriman->next_process == 'PAINTING')
        <tr>
            <td>QTY</td>
            <td colspan="2">LOT NO.</td>
            <td>TIME</td>
        </tr>
        @elseif($pengiriman->next_process == 'ASSEMBLY')
        <tr>
            <td>QTY</td>
            <td colspan="2">LOT NO.</td>
            <td>TIME</td>
        </tr>
        @endif

        @if ($pengiriman->next_process == 'PPIC')
        <tr>
            <td class="align-middle text-center">
                <font size="4" class="text-strong serif">{{ $qty }}</font>
            </td>
            <td class="align-middle text-center">
                <font size="4" class="text-strong serif">
                    {{ \Carbon\Carbon::parse($pengiriman->tgl_kanban)->format('d/m/Y') }}</font>
            </td>
            <td class="align-middle text-center">
                <font size="4" class="text-strong serif">{{ $pengiriman->pic_packing }}</font>
            </td>
            <td></td>
        </tr>
        @elseif($pengiriman->next_process == 'PAINTING')
        <tr>
            <td class="align-middle text-center">
                <font size="4" class="text-strong serif">{{ $qty }}</font>
            </td>
            <td class="align-middle text-center" colspan="2">
                <font size="4" class="text-strong serif">
                    {{ \Carbon\Carbon::parse($pengiriman->tgl_kanban)->format('d/m/Y') }}</font>
            </td>
            <td class="align-middle text-center">
                <font size="4" class="text-strong serif">
                    {{ \Carbon\Carbon::parse($pengiriman->waktu_kanban)->format('H:i') }}</font>
            </td>
        </tr>
        @elseif($pengiriman->next_process == 'ASSEMBLY')
        <tr>
            <td class="align-middle text-center">
                <font size="4" class="text-strong serif">{{ $qty }}</font>
            </td>
            <td class="align-middle text-center" colspan="2">
                <font size="4" class="text-strong serif">
                    {{ \Carbon\Carbon::parse($pengiriman->tgl_kanban)->format('d/m/Y') }}</font>
            </td>
            <td class="align-middle text-center">
                <font size="4" class="text-strong serif">
                    {{ \Carbon\Carbon::parse($pengiriman->waktu_kanban)->format('H:i') }}</font>
            </td>
        </tr>
        @endif
    </table>
</body>

</html>
