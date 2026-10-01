<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Receipt #{{ $booking->number }} - TRB Auto Car Care</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Segoe UI','Helvetica',Arial,sans-serif; background:#050506; color:#e8e8ea; padding:30px 15px; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
    .sheet { max-width:820px; margin:0 auto; background:#0c0c0e; border:1px solid #1d1d20; border-radius:16px; overflow:hidden; box-shadow:0 25px 60px rgba(0,0,0,.6); }

    .hero { position:relative; min-height:300px; background:#050506 center/cover no-repeat; }
    .hero::after { content:''; position:absolute; inset:0; background:linear-gradient(90deg,rgba(5,5,6,.92) 0%,rgba(5,5,6,.35) 45%,rgba(5,5,6,.55) 100%); }
    .hero-inner { position:relative; z-index:2; display:flex; justify-content:space-between; padding:34px 38px; min-height:300px; }
    .brand-name { font-size:30px; font-weight:800; font-style:italic; color:#fff; letter-spacing:.5px; }
    .brand-name .r { color:#EC1F24; }
    .brand-sub { font-size:12px; color:#cfcfd3; margin-top:6px; letter-spacing:.3px; }
    .brand-rule { width:60px; height:3px; background:#EC1F24; margin-top:12px; border-radius:2px; }
    .rcpt { text-align:right; }
    .rcpt .lbl { font-size:26px; font-weight:800; letter-spacing:8px; color:#fff; }
    .rcpt .no { font-size:13px; color:#cfcfd3; margin-top:8px; letter-spacing:1px; }
    .rcpt .paid { display:inline-block; margin-top:16px; background:#EC1F24; color:#fff; font-weight:800; font-size:13px; letter-spacing:2px; padding:7px 22px; border-radius:30px; box-shadow:0 6px 18px rgba(236,31,36,.45); }

    .contact { display:flex; justify-content:space-between; gap:14px; background:#0e0e11; border-top:2px solid #EC1F24; padding:16px 38px; font-size:12.5px; color:#b9b9be; }
    .contact div { display:flex; align-items:center; gap:9px; }
    .contact i { color:#EC1F24; }

    .body { padding:30px 34px; }
    .cards { display:grid; grid-template-columns:1fr 1fr; gap:18px; margin-bottom:22px; }
    .card { background:#141417; border:1px solid #222226; border-left:3px solid #EC1F24; border-radius:12px; padding:20px 22px; box-shadow:0 0 24px rgba(236,31,36,.06); }
    .card-head { display:flex; align-items:center; gap:11px; margin-bottom:14px; }
    .card-icon { width:34px; height:34px; border:2px solid #EC1F24; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#EC1F24; font-size:14px; }
    .card-title { font-size:13px; font-weight:800; color:#EC1F24; text-transform:uppercase; letter-spacing:1.5px; }
    .card .big { font-size:18px; font-weight:700; color:#fff; margin-bottom:10px; }
    .row { display:flex; font-size:13.5px; line-height:2; }
    .row .k { color:#85858c; width:90px; flex-shrink:0; }
    .row .sep { color:#85858c; width:14px; }
    .row .v { color:#e2e2e6; }
    .card .addr { font-size:13.5px; color:#c2c2c7; line-height:1.7; display:flex; gap:8px; }
    .card .addr i { color:#EC1F24; margin-top:3px; }

    .ptable { width:100%; border-collapse:collapse; border-radius:10px; overflow:hidden; margin-top:4px; }
    .ptable thead th { background:#1b1b1f; color:#fff; text-align:left; padding:14px 20px; font-size:12px; letter-spacing:1.5px; text-transform:uppercase; }
    .ptable thead th.r { text-align:right; }
    .ptable tbody td { background:#0f0f12; padding:16px 20px; font-size:14px; color:#dcdce0; border-bottom:1px solid #1c1c20; }
    .ptable tbody td.r { text-align:right; }
    .ptable .disc td { color:#56d98a; font-weight:600; }

    .final { display:flex; justify-content:flex-end; margin-top:22px; }
    .final-box { width:54%; background:linear-gradient(100deg,#7a0f12 0%,#EC1F24 100%); border-radius:14px; padding:20px 26px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 10px 30px rgba(236,31,36,.35); }
    .final-box .k { color:#ffe1e1; font-size:12px; letter-spacing:2px; text-transform:uppercase; }
    .final-box .v { color:#fff; font-size:26px; font-weight:800; }

    .foot { text-align:center; padding:30px 34px 40px; }
    .foot .chk { width:46px; height:46px; border:2px solid #EC1F24; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; color:#EC1F24; font-size:20px; margin-bottom:14px; }
    .foot .ty { font-size:17px; font-weight:700; color:#fff; }
    .foot .ty .r { color:#EC1F24; }
    .foot .note { font-size:12px; color:#7a7a80; margin-top:8px; }

    .printbar { max-width:820px; margin:0 auto 16px; text-align:right; }
    .btn-print { background:#EC1F24; color:#fff; border:none; font-size:14px; font-weight:700; padding:11px 24px; border-radius:30px; cursor:pointer; box-shadow:0 8px 20px rgba(236,31,36,.4); }
    .btn-print i { margin-right:7px; }

    @media print {
        @page { size:A4; margin:0; }
        body { padding:0; background:#050506; }
        .printbar { display:none !important; }
        .sheet { max-width:100%; border:none; border-radius:0; box-shadow:none; }
    }
</style>
</head>
<body>
    <div class="printbar">
        <button class="btn-print" onclick="window.print()"><i class="fa-solid fa-print"></i> Print / Save as PDF</button>
    </div>

    <div class="sheet">
        <div class="hero" style="background-image:url('{{ asset('images/receipt-car.jpg') }}');">
            <div class="hero-inner">
                <div>
                    <div class="brand-name"><span class="r">TRB</span> Auto Car Care</div>
                    <div class="brand-sub">Premium Car Care &amp; Service Booking</div>
                    <div class="brand-rule"></div>
                </div>
                <div class="rcpt">
                    <div class="lbl">RECEIPT</div>
                    <div class="no">No. {{ $booking->number }}</div>
                    <div class="paid">PAID</div>
                </div>
            </div>
        </div>

        <div class="contact">
            <div><i class="fa-solid fa-location-dot"></i> 70, Jalan PU 7/3, Taman Puchong Utama, 47100 Puchong, Selangor</div>
            <div><i class="fa-solid fa-envelope"></i> trbautocarcare@gmail.com</div>
            <div><i class="fa-solid fa-phone"></i> 017-367 3385</div>
        </div>

        <div class="body">
            <div class="cards">
                <div class="card">
                    <div class="card-head"><span class="card-icon"><i class="fa-solid fa-user"></i></span><span class="card-title">Customer</span></div>
                    <div class="big">{{ $booking->user->name }}</div>
                    <div class="row"><span class="k">Phone</span><span class="sep">:</span><span class="v">{{ $booking->user->phone ?? '-' }}</span></div>
                    <div class="row"><span class="k">Email</span><span class="sep">:</span><span class="v">{{ $booking->user->email }}</span></div>
                </div>
                <div class="card">
                    <div class="card-head"><span class="card-icon"><i class="fa-solid fa-car-side"></i></span><span class="card-title">Vehicle</span></div>
                    <div class="big">{{ $booking->car->brand }} {{ $booking->car->model }}</div>
                    <div class="row"><span class="k">Plate No</span><span class="sep">:</span><span class="v">{{ $booking->car->car_plate }}</span></div>
                    <div class="row"><span class="k">Year</span><span class="sep">:</span><span class="v">{{ $booking->car->year ?? '-' }}</span></div>
                </div>
                <div class="card">
                    <div class="card-head"><span class="card-icon"><i class="fa-solid fa-calendar-days"></i></span><span class="card-title">Booking</span></div>
                    <div class="row"><span class="k">Number</span><span class="sep">:</span><span class="v">{{ $booking->number }}</span></div>
                    <div class="row"><span class="k">Date</span><span class="sep">:</span><span class="v">{{ $booking->booking_date?->format('d M Y') }}</span></div>
                    <div class="row"><span class="k">Time</span><span class="sep">:</span><span class="v">{{ $booking->start_time?->format('h:i A') }} - {{ $booking->end_time?->format('h:i A') }}</span></div>
                </div>
                <div class="card">
                    <div class="card-head"><span class="card-icon"><i class="fa-solid fa-warehouse"></i></span><span class="card-title">Workshop</span></div>
                    <div class="big">{{ $booking->branch->name }}</div>
                    <div class="addr"><i class="fa-solid fa-location-dot"></i><span>{{ $booking->branch->address }}</span></div>
                </div>
            </div>

            <table class="ptable">
                <thead><tr><th>Service</th><th class="r">Original Price</th></tr></thead>
                <tbody>
                    <tr>
                        <td>
                            {{ $booking->service->name }}
                            @if(!empty($booking->booking_options))
                                <div style="font-size: 0.85em; color: #555; margin-top: 4px; padding-left: 10px; border-left: 2px solid #ddd;">
                                    @php
                                        $serviceOptionsConfig = collect($booking->service->meta_data['booking_options'] ?? []);
                                    @endphp
                                    @foreach($booking->booking_options as $key => $val)
                                        @php
                                            $config = $serviceOptionsConfig->firstWhere('key', $key);
                                            $label = $config['label'] ?? ucfirst(str_replace('_', ' ', $key));
                                            $valLabel = $val;
                                            if ($config && isset($config['choices'])) {
                                                $choice = collect($config['choices'])->firstWhere('value', (string)$val);
                                                if ($choice) {
                                                    $valLabel = $choice['label'] ?? $val;
                                                }
                                            }
                                        @endphp
                                        <div style="margin-bottom: 2px;">• {{ $label }}: {{ $valLabel }}</div>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="r">RM {{ number_format($booking->service_price_at_booking, 2) }}</td>
                    </tr>
                    @if(($booking->discount_amount ?? 0) > 0)
                    <tr class="disc"><td>Discount Applied</td><td class="r">- RM {{ number_format($booking->discount_amount, 2) }}</td></tr>
                    @endif
                </tbody>
            </table>

            <div class="final">
                <div class="final-box">
                    <span class="k">Final Price (Paid)</span>
                    <span class="v">RM {{ number_format(max(0, (float)$booking->service_price_at_booking - (float)($booking->discount_amount ?? 0)), 2) }}</span>
                </div>
            </div>
        </div>

        <div class="foot">
            <div class="chk"><i class="fa-solid fa-check"></i></div>
            <div class="ty">Thank you for choosing <span class="r">TRB Auto Car Care!</span></div>
            <div class="note">This is a computer-generated receipt. No signature is required.</div>
        </div>
    </div>
</body>
</html>
