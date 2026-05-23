<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; text-align: center; color: #333; padding-top: 50px; }
        .card { 
            border: 3px solid #ffed00; 
            padding: 30px; 
            border-radius: 20px; 
            width: 350px; 
            margin: 0 auto; 
            background-color: #fff;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .header { 
            border-bottom: 2px solid #eee; 
            margin-bottom: 20px; 
            padding-bottom: 10px;
        }
        h1 { color: #000; font-size: 22px; margin: 0; text-transform: uppercase; letter-spacing: 2px; }
        .info { text-align: left; margin: 20px 0; line-height: 1.6; }
        .qr-code { margin: 25px 0; }
        .footer { font-size: 10px; color: #888; margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px; }
        .label { font-weight: bold; color: #666; font-size: 11px; text-transform: uppercase; }
        .value { font-weight: bold; color: #000; font-size: 16px; }
    </style>
</head>
<body>

    <div class="card">
        <div class="header">
            <h1>FIT PRO | ACCESS CARD</h1>
        </div>

        <div class="info">
            <div class="label">Member Name</div>
            <div class="value">{{ strtoupper($user->name) }}</div>
            
            <div style="margin-top: 15px;">
                <div class="label">Membership Protocol</div>
                <div class="value">{{ $user->abonnement->typeAbonnement->nom ?? 'Standard' }}</div>
            </div>

            <div style="margin-top: 15px;">
                <div class="label">Signal Validity (Expiry)</div>
                <div class="value" style="color: #d32f2f;">
                    {{ \Carbon\Carbon::parse($user->expired_at)->format('d M Y') }}
                </div>
            </div>
        </div>
        
        <div class="qr-code">
            <!-- استعمال الـ API لضمان اشتغال الـ QR Code داخل الـ PDF بلا مشاكل ✅ -->
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $user->id }}" width="150" height="150">
        </div>
        
        <div class="footer">
            <p>AUTHORIZED AGENT ACCESS ONLY</p>
            <p>Please present this digital pass at the security terminal.</p>
        </div>
    </div>

</body>
</html>