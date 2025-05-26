<!-- resources/views/2fa/enable.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enable Two-Factor Authentication</title>
</head>
<body>
    <h1>Enable Two-Factor Authentication</h1>
    <p>Scan the QR code below with your 2FA app:</p>
    <div>{!! $qrCode !!}</div>
    <p>Or enter the secret key manually: <strong>{{ $secret }}</strong></p>
    <p>Once you have set up your 2FA app, click the button below to verify your code.</p>
    <a href="{{ route('2fa.verify') }}">Verify 2FA Code</a>
</body>
</html>