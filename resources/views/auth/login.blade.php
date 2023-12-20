<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
</head>
<body>

    <h2>Đăng nhập</h2>

    <form method="POST" action="{{ url('/login') }}">
        @csrf

        <!-- Thêm các trường đăng nhập bằng email và mật khẩu nếu cần -->

        <button type="submit">Đăng nhập</button>
    </form>

    <hr>

    <a href="{{ url('/login/facebook') }}" class="btn btn-primary">Đăng nhập bằng Facebook</a>

</body>
</html>
