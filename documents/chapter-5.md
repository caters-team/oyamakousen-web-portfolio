# Chapter 5: お問い合わせフォーム（完了画面・メール送信）

**所要時間: 10分**

---

## 完了画面とは

確認画面で「送信する」をクリックした後の処理です。
メール送信を行い、完了メッセージを表示します。

**主なポイント:**
- セッションからデータ取得
- `mail()` 関数でメール送信
- セッションをクリア

---

## ファイル作成

`public_html/contact/complete.php` を作成し、以下を貼り付け：

```php
<?php
session_start();

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
    'Content-Type: text/plain; charset=UTF-8',
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
        h1 {
            color: #4CAF50;
        }
        .message {
            color: #555;
            line-height: 1.8;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>送信完了</h1>
        
        <?php if ($result): ?>
            <div class="message">
                お問い合わせを受け付けました。<br>
                ご入力いただいたメールアドレス宛に確認メールをお送りしましたので、<br>
                ご確認ください。
            </div>
        <?php else: ?>
            <div class="message" style="color: #d32f2f;">
                メール送信に失敗しました。<br>
                お手数ですが、時間をおいて再度お試しください。
            </div>
        <?php endif; ?>
        
        <a href="../index.php" class="btn">トップページへ</a>
    </div>
</body>
</html>
```

---

## コードの解説

### セッションチェック

```php
if (!isset($_SESSION['form_data']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}
```

確認画面を経由せずに直接アクセスされるのを防ぎます。

### メール本文の作成

```php
$mail_body = "お問い合わせがありました。\n\n";
$mail_body .= "【お名前】\n" . $name . "\n\n";
```

`.=` は文字列の連結代入演算子です。`\n` は改行文字。

### メールヘッダー

```php
$headers = [
    'From: ' . $email,           // 送信元
    'Reply-To: ' . $email,       // 返信先
    'Content-Type: text/plain; charset=UTF-8',  // 文字コード
];
```

メールの送信元や文字コードを指定します。

### mail() 関数

```php
$result = mail($to, $mail_subject, $mail_body, implode("\r\n", $headers));
```

PHPの組み込み関数でメールを送信します。
- 第1引数: 送信先
- 第2引数: 件名
- 第3引数: 本文
- 第4引数: ヘッダー（`\r\n` で結合）

### Mailpit で確認

開発環境では実際にメールは送信されず、Mailpit で確認できます。
http://localhost:8025 にアクセスしてメールを確認してください。

---

## 動作確認

1. フォームに入力して送信
2. 完了画面が表示される
3. http://localhost:8025 でメールが届いていることを確認

---

📝 **次のChapter**: お知らせCMSの作成（一覧・新規作成）
