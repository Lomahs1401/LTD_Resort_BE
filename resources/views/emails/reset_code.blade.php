<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Reset Code</title>
</head>
<body>
    <h2>Reset Your Password</h2>
    <p>Hi {{ $user->name }},</p>
    <p>Please use the following code to reset your password:</p>
    <h3>{{ $user->reset_code }}</h3>
    <p>This code will expire in 5 minutes.</p>
    <p>If you didn't request a password reset, please ignore this email.</p>
</body>
</html>

