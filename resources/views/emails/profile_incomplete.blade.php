<!DOCTYPE html>
<html>
<head>
    <title>Profile Incomplete</title>
</head>
<body>
    <h1>Hello, {{ $employee->full_name }}</h1>
    <p>Your employee profile has been reviewed and marked as <strong>Incomplete</strong>.</p>
    <p><strong>Reason:</strong> {{ $cause }}</p>
    <p>Please log in to your account and update the missing information to proceed with the activation.</p>
    <p>Thank you,</p>
    <p>HR Department</p>
</body>
</html>
