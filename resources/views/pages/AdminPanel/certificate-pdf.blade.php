<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificate</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            width: 80px;
            height: auto;
        }
        .logo-left {
            float: left;
        }
        .logo-right {
            float: right;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
        }
        .content {
            margin-top: 50px;
            text-align: justify;
            line-height: 1.6;
        }
        .signature {
            margin-top: 100px;
            text-align: center;
            float: right;
            width: 40%;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            width: 100%;
            text-align: center;
            font-style: italic;
            font-size: 12px;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="header clearfix">
        <img src="{{ public_path('storage/' . str_replace('public/', '', $layout->logo_1)) }}" class="logo logo-left">
        <img src="{{ public_path('storage/' . str_replace('public/', '', $layout->logo_2)) }}" class="logo logo-right">
        
        <div style="margin: 0 100px;">
            <p style="font-size: 16px; margin: 0;">REPUBLIC OF THE PHILIPPINES</p>
            <p style="font-size: 16px; margin: 5px 0;">{{ $layout->municipality }}</p>
            <p style="font-size: 16px; margin: 5px 0;">{{ $layout->province }}</p>
            <p style="font-size: 16px; margin: 5px 0;"><b><u>{{ $layout->barangay }}</u></b></p>
            <p style="font-size: 18px; margin: 10px 0;"><b>{{ $layout->office }}</b></p>
            <p style="font-size: 22px; margin: 15px 0;"><u><b>{{ $certificate->certificateType->certificate_name ?? 'CERTIFICATION' }}</b></u></p>
        </div>
    </div>

    <div class="content">
        <p style="font-size: 16px;"><b>TO WHOM IT MAY CONCERN:</b></p>
        
        <p style="text-indent: 50px;">
            THIS IS TO CERTIFY that {{ $certificate->name }}, {{ $certificate->age }} years old, {{ $certificate->gender }} 
            and a resident of {{ $certificate->certificateType->content_1 ?? '' }}
        </p>

        <p style="text-indent: 50px;">
            {{ $certificate->certificateType->content_2 ?? '' }}
        </p>

        <p>
            <b>DONE AND ISSUED</b> this {{ now()->format('jS') }} day of {{ now()->format('F Y') }} at the {{ $certificate->certificateType->content_3 ?? '' }}
        </p>
    </div>

    <div class="signature">
        <p style="margin-bottom: 40px;">APPROVE BY:</p>
        @foreach($puno as $official)
        <p style="text-transform: uppercase; margin: 0;">{{ $official->name }}</p>
        <p style="margin: 0;">{{ $official->position }}</p>
        @endforeach
    </div>

    <div class="footer">
        <p>***This is a computer-generated document. No signature is required***</p>
    </div>
</body>
</html> 