<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2>New Contact Message</h2>
    <p><strong>From:</strong> {{ $submission->email }}</p>
    <p><strong>Received:</strong> {{ $submission->created_at->format('d M Y, h:i A') }}</p>
    <hr>
    <p style="white-space: pre-wrap;">{{ $submission->comments }}</p>
    <hr>
    <p style="font-size: 12px; color: #888;">You can view and manage this message in the <a href="{{ url('/admin/contacts') }}">admin panel</a>.</p>
</body>
</html>
