# Chapter 4: お問い合わせフォーム（確認画面・バリデーション）

**所要時間: 15分**

---

## 確認画面とは

入力内容をチェックして、問題なければ確認画面を表示します。
エラーがあれば入力画面に戻します。

**主なポイント:**
- バリデーション（入力チェック）
- エラー時は入力画面にリダイレクト
- OKなら確認画面を表示

---

## ファイル作成

`public_html/contact/confirm.php` を作成し、以下を貼り付け：

```php
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';

// バリデーション
$errors = [];

if (empty($name)) {
    $errors['name'] = 'お名前を入力してください。';
} elseif (mb_strlen($name) > 50) {
    $errors['name'] = 'お名前は50文字以内で入力してください。';
}

if (empty($email)) {
    $errors['email'] = 'メールアドレスを入力してください。';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'メールアドレスの形式が正しくありません。';
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
    'message' => $message,
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
        }
        .confirm-group {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .confirm-group:last-of-type {
            border-bottom: none;
        }
        .label {
            color: #555;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .value {
            color: #333;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 4px;
            word-wrap: break-word;
        }
        .button-group {
            display: flex;
            gap: 10px;
        }
        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-primary {
            background-color: #4CAF50;
            color: white;
        }
        .btn-primary:hover {
            background-color: #45a049;
        }
        .btn-secondary {
            background-color: #999;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>入力内容の確認</h1>
        
        <div class="confirm-group">
            <div class="label">お名前</div>
            <div class="value"><?php echo htmlspecialchars($name); ?></div>
        </div>
        
        <div class="confirm-group">
            <div class="label">メールアドレス</div>
            <div class="value"><?php echo htmlspecialchars($email); ?></div>
        </div>
        
        <div class="confirm-group">
            <div class="label">件名</div>
            <div class="value"><?php echo htmlspecialchars($subject); ?></div>
        </div>
        
        <div class="confirm-group">
            <div class="label">お問い合わせ内容</div>
            <div class="value"><?php echo nl2br(htmlspecialchars($message)); ?></div>
        </div>
        
        <form action="complete.php" method="POST">
            <div class="button-group">
                <button type="button" class="btn btn-secondary" onclick="history.back()">戻る</button>
                <button type="submit" class="btn btn-primary">送信する</button>
            </div>
        </form>
    </div>
</body>
</html>
```

---

## コードの解説

### POSTチェック

```php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}
```

直接このページにアクセスされた場合は入力画面に戻します。

### バリデーションの流れ

1. **必須チェック** - `empty()` で空かどうか
2. **形式チェック** - `filter_var()` でメール形式
3. **文字数チェック** - `mb_strlen()` で文字数

```php
if (empty($email)) {
    $errors['email'] = 'メールアドレスを入力してください。';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'メールアドレスの形式が正しくありません。';
}
```

### エラー時のリダイレクト

```php
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;  // エラーをセッションに保存
    $_SESSION['old'] = $_POST;      // 入力値もセッションに保存
    header('Location: index.php');  // 入力画面に戻る
    exit;
}
```

セッションにエラーと入力値を保存して、入力画面に戻ります。

### nl2br の役割

```php
<?php echo nl2br(htmlspecialchars($message)); ?>
```

改行文字（`\n`）をHTMLの改行タグ（`<br>`）に変換して表示します。

---

## 動作確認

1. 入力画面で何も入力せずに送信 → エラー表示
2. メールアドレスに不正な値を入力 → エラー表示
3. 正しく入力 → 確認画面が表示される

---

📝 **次のChapter**: メール送信と完了画面の実装
