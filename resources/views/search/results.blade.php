<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tra cứu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            line-height: 1.6;
        }
        h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .info strong {
            font-weight: bold;
        }
        .info p {
            margin: 5px 0;
        }
        .back-btn {
            margin-top: 20px;
        }
        a {
            color: #0d6efd;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <h1>Kết quả tra cứu</h1>

    <div class="info">
        <p><strong>Số biên lai:</strong> {{ $result['data']['pos_reference'] }}</p>
        <p><strong>Ngày mua hàng:</strong> {{ $result['data']['issued_date'] }}</p>
        <p><strong>Số hóa đơn điện tử:</strong> {{ $result['data']['invoice_no'] }}</p>
        <p><strong>Ngày hóa đơn:</strong> {{ $result['data']['issued_date'] }}</p>
        <p><strong>Mã số bí mật:</strong> {{ $result['data']['reservation_code'] }}</p>
    </div>

    <p>
        Để tra cứu thông tin hóa đơn vui lòng truy cập 
        <a href="{{ config('app.url') }}" target="_blank">{{ config('app.url') }}</a><br>
    </p>

    <div class="back-btn">
        <button onclick="history.back()">Quay lại</button>
    </div>

</body>
</html>
