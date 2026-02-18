<?php

session_start();

// セッションにデータがない場合はフォームに戻る
if (!isset($_SESSION['form_data']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$form_data = $_SESSION['form_data'];
$name = $form_data['name'];
$email = $form_data['email'];
$subject = $form_data['subject'];
$message = $form_data['message'];

// メール送信設定
$to = 'admin@example.com';
$mail_subject = 'お問い合わせ: ' . $subject;

// メール本文
$mail_body = "お問い合わせがありました。\n\n";
$mail_body .= "【お名前】\n" . $name . "\n\n";
$mail_body .= "【メールアドレス】\n" . $email . "\n\n";
$mail_body .= "【件名】\n" . $subject . "\n\n";
$mail_body .= "【お問い合わせ内容】\n" . $message . "\n";

// メールヘッダー
$headers = [
    'From: ' . $email,
    'Reply-To: ' . $email,
    'X-Mailer: PHP/' . phpversion(),
    'Content-Type: text/plain; charset=UTF-8'
];

// メール送信
$result = mail($to, $mail_subject, $mail_body, implode("\r\n", $headers));

// セッションをクリア
unset($_SESSION['form_data']);
unset($_SESSION['errors']);
unset($_SESSION['old']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>送信完了</title>
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
            text-align: center;
        }
        .success {
            color: #4CAF50;
            font-size: 24px;
            margin: 20px 0;
        }
        .failure {
            color: #f44336;
            font-size: 24px;
            margin: 20px 0;
        }
        .message {
            color: #666;
            margin: 20px 0;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 30px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        a:hover {
            background-color: #45a049;
        }
        .mailpit-link {
            display: block;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        .mailpit-link a {
            background-color: #2196F3;
            padding: 8px 20px;
            font-size: 14px;
        }
        .mailpit-link a:hover {
            background-color: #0b7dda;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($result): ?>
            <h1 class="success">✓ 送信完了</h1>
            <p class="message">お問い合わせありがとうございます。<br>メールを送信しました。</p>
            <div class="mailpit-link">
                開発環境では、送信されたメールを以下で確認できます:<br>
                <a href="http://localhost:8025" target="_blank">Mailpit Web UI を開く</a>
            </div>
        <?php else: ?>
            <h1 class="failure">✗ 送信失敗</h1>
            <p class="message">申し訳ございません。メールの送信に失敗しました。<br>しばらく時間をおいて再度お試しください。</p>
        <?php endif; ?>
        
        <a href="./index.php">フォームに戻る</a>
    </div>
</body>
</html>