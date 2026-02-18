# Chapter 3: お問い合わせフォーム（入力画面）

**所要時間: 15分**

---

## 入力画面とは

お問い合わせフォームの最初の画面です。
ユーザーが名前、メールアドレス、件名、内容を入力します。

**主なポイント:**
- セッションでエラーメッセージと入力値を保持
- エラーがあれば赤字で表示
- 入力値を保持して再表示

---

## ファイル作成

`public_html/contact/index.php` を作成し、以下を貼り付け：

```php
<?php
session_start();
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = $_POST;
}

unset($_SESSION['errors']);
unset($_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせフォーム</title>
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
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            min-height: 150px;
            resize: vertical;
        }
        .error {
            color: #d32f2f;
            font-size: 14px;
            margin-top: 5px;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>お問い合わせフォーム</h1>
        
        <form action="confirm.php" method="POST">
            <div class="form-group">
                <label for="name">お名前 <span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>">
                <?php if (isset($errors['name'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['name']); ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="email">メールアドレス <span style="color: red;">*</span></label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>">
                <?php if (isset($errors['email'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['email']); ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="subject">件名 <span style="color: red;">*</span></label>
                <input type="text" id="subject" name="subject" value="<?php echo htmlspecialchars($old['subject'] ?? ''); ?>">
                <?php if (isset($errors['subject'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['subject']); ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="message">お問い合わせ内容 <span style="color: red;">*</span></label>
                <textarea id="message" name="message"><?php echo htmlspecialchars($old['message'] ?? ''); ?></textarea>
                <?php if (isset($errors['message'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['message']); ?></div>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="btn">確認画面へ</button>
        </form>
    </div>
</body>
</html>
```

---

## コードの解説

### セッション管理

```php
session_start();
$errors = $_SESSION['errors'] ?? [];  // エラーメッセージ
$old = $_SESSION['old'] ?? [];        // 前回の入力値
```

確認画面でバリデーションエラーがあった場合、この画面に戻ってきた時にエラーと入力値を表示します。

### エラー表示

```php
<?php if (isset($errors['name'])): ?>
    <div class="error"><?php echo htmlspecialchars($errors['name']); ?></div>
<?php endif; ?>
```

フィールドごとにエラーメッセージを赤字で表示します。

### 入力値の保持

```php
value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>"
```

エラー時に入力値を保持して再表示することで、ユーザーが入力し直す手間を省きます。

### htmlspecialchars の役割

ユーザー入力をそのまま表示するとXSS攻撃のリスクがあるため、特殊文字を無害化します。

---

## 動作確認

http://localhost:8080/contact/ にアクセスしてフォームが表示されることを確認。

---

📝 **次のChapter**: 確認画面とバリデーションの実装
