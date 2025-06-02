<?php
$data = null;
$error = $_GET['error'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pos_reference = $_POST['pos_reference'] ?? '';
    $phone = $_POST['phone'] ?? '';

    if (empty($pos_reference) || empty($phone)) {
        header('Location: /invoice?error=Vui lòng nhập đầy đủ thông tin');
        exit;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://erp.biluxury.vn/check/sinvoice');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'pos_reference' => $pos_reference,
        'phone' => $phone
    ]));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $response) {
        $result = json_decode($response, true);
        if ($result && isset($result['result']['data'])) {
            $data = $result['result']['data'];
        }
    }

    if (!$data) {
        if ($pos_reference == 'test') {
            $data = [
                'pos_reference' => '00310-0032156-0002',
                'issued_date' => '02-06-2025',
                'invoice_no' => 'C45HME94445',
                'reservation_code' => 'H25TY45KJNMH3Y'
            ];
            $error = 'Không tìm thấy thông tin, hiển thị dữ liệu demo';
        } else {
            header('Location: /invoice?error=Không tìm thấy thông tin');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tra cứu thông tin hóa đơn</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <?php if ($data): ?>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header text-center">
                            <h3>Kết quả tra cứu</h3>
                        </div>
                        <div class="card-body">
                            <?php if ($error): ?>
                                <div class="alert alert-warning"><?= htmlspecialchars($error) ?></div>
                            <?php endif; ?>

                            <p><strong>Số biên lai:</strong> <?= htmlspecialchars($data['pos_reference']) ?></p>
                            <p><strong>Ngày mua hàng:</strong> <?= htmlspecialchars($data['issued_date']) ?></p>
                            <p><strong>Số hóa đơn điện tử:</strong> <?= htmlspecialchars($data['invoice_no']) ?></p>
                            <p><strong>Ngày hóa đơn:</strong> <?= htmlspecialchars($data['issued_date']) ?></p>
                            <p><strong>Mã số bí mật:</strong> <?= htmlspecialchars($data['reservation_code']) ?></p>

                            <p class="mt-4">
                                Để tra cứu hóa đơn điện tử vui lòng truy cập<br>
                                <a href="/invoice" target="_blank">http://portal.biluxury.vn/invoice</a><br>
                            </p>

                            <div class="text-center mt-4">
                                <a href="/invoice" class="btn btn-secondary">Tra cứu mới</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header text-center">
                            <h3>Tra cứu thông tin</h3>
                        </div>
                        <div class="card-body">
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                            <?php endif; ?>

                            <form method="POST">
                                <div class="mb-3">
                                    <label for="pos_reference" class="form-label">Mã tra cứu</label>
                                    <input type="text" class="form-control" id="pos_reference" name="pos_reference" required
                                           value="<?= isset($_POST['pos_reference']) ? htmlspecialchars($_POST['pos_reference']) : '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required
                                           value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>">
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Tra cứu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
