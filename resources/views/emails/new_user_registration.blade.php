<!DOCTYPE html>
<html>
<head>
    <title>New Estimator Registration</title>
</head>
<body>
    <p>Hello {{ $admin->name }},</p>
    <p>A new user has registered as an estimator. Please review and approve their status.</p>
    <p>Name: {{ $user->name }}</p>
    <p>Email: {{ $user->email }}</p>
</body>
</html>
