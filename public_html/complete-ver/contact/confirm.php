<?php

session_start();

// POSTデータがない場合はフォームに戻る
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';

// 簡単なバリデーション
$errors = [];

if (empty($name)) {
    $errors['name'] = 'お名前を入力してください。';
} elseif (mb_strlen($name) > 50) {
    $errors['name'] = 'お名前は50文字以内で入力してください。';
}

if (empty($email)) {
    $errors['email'] = 'メールアドレスを入力してください。';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = '有効なメールアドレスを入力してください。';
} elseif (mb_strlen($email) > 100) {
    $errors['email'] = 'メールアドレスは100文字以内で入力してください。';
}

if (empty($subject)) {
    $errors['subject'] = '件名を入力してください。';
} elseif (mb_strlen($subject) > 100) {
    $errors['subject'] = '件名は100文字以内で入力してください。';
}

if (empty($message)) {
    $errors['message'] = 'お問い合わせ内容を入力してください。';
} elseif (mb_strlen($message) > 500) {
    $errors['message'] = 'お問い合わせ内容は500文字以内で入力してください。';
}

// エラーがある場合
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = $_POST;
    header('Location: index.php');
    exit;
}

// セッションにデータを保存
$_SESSION['form_data'] = [
    'name' => $name,
    'email' => $email,
    'subject' => $subject,
    'message' => $message
];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>入力内容の確認</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .confirm-group {
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid #eee;
        }
        .confirm-group:last-of-type {
            border-bottom: none;
        }
        .confirm-label {
            display: block;
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .confirm-value {
            color: #333;
            font-size: 16px;
            line-height: 1.6;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        button, .back-button {
            flex: 1;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        .back-button {
            background-color: #999;
            color: white;
        }
        .back-button:hover {
            background-color: #777;
        }
        .notice {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
            color: #856404;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>入力内容の確認</h1>
        
        <div class="notice">
            以下の内容でよろしければ「送信する」ボタンを押してください。<br>
            修正する場合は「戻る」ボタンを押してください。
        </div>

        <div class="confirm-group">
            <span class="confirm-label">お名前</span>
            <div class="confirm-value"><?php echo htmlspecialchars($name); ?></div>
        </div>

        <div class="confirm-group">
            <span class="confirm-label">メールアドレス</span>
            <div class="confirm-value"><?php echo htmlspecialchars($email); ?></div>
        </div>

        <div class="confirm-group">
            <span class="confirm-label">件名</span>
            <div class="confirm-value"><?php echo htmlspecialchars($subject); ?></div>
        </div>

        <div class="confirm-group">
            <span class="confirm-label">お問い合わせ内容</span>
            <div class="confirm-value"><?php echo htmlspecialchars($message); ?></div>
        </div>

        <div class="button-group">
            <form action="./index.php" method="POST" style="flex: 1;">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <input type="hidden" name="subject" value="<?php echo htmlspecialchars($subject); ?>">
                <input type="hidden" name="message" value="<?php echo htmlspecialchars($message); ?>">
                <button type="submit" class="back-button" style="width: 100%;">戻る</button>
            </form>
            
            <form action="./complete.php" method="POST" style="flex: 1;">
                <button type="submit" style="width: 100%;">送信する</button>
            </form>
        </div>
    </div>
</body>
</html>