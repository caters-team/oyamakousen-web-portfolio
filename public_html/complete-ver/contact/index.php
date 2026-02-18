<?php

session_start();
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];

// 確認画面から戻ってきた場合のデータ
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
            font-size: 14px;
        }
        input.error-input,
        textarea.error-input {
            border-color: #f44336;
        }
        textarea {
            min-height: 150px;
            resize: vertical;
        }
        .required {
            color: red;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: #f44336;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>お問い合わせフォーム</h1>
        <form action="./confirm.php" method="POST">
            <div class="form-group">
                <label for="name">お名前 <span class="required">*</span></label>
                <input type="text" id="name" name="name" maxlength="50" value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>" class="<?php echo isset($errors['name']) ? 'error-input' : ''; ?>">
                <?php if (isset($errors['name'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['name']); ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="email">メールアドレス <span class="required">*</span></label>
                <input type="email" id="email" name="email" maxlength="100" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" class="<?php echo isset($errors['email']) ? 'error-input' : ''; ?>">
                <?php if (isset($errors['email'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['email']); ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="subject">件名 <span class="required">*</span></label>
                <input type="text" id="subject" name="subject" maxlength="100" value="<?php echo htmlspecialchars($old['subject'] ?? ''); ?>" class="<?php echo isset($errors['subject']) ? 'error-input' : ''; ?>">
                <?php if (isset($errors['subject'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['subject']); ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="message">お問い合わせ内容 <span class="required">*</span></label>
                <textarea id="message" name="message" maxlength="500" class="<?php echo isset($errors['message']) ? 'error-input' : ''; ?>"><?php echo htmlspecialchars($old['message'] ?? ''); ?></textarea>
                <?php if (isset($errors['message'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['message']); ?></div>
                <?php endif; ?>
            </div>
            
            <button type="submit">確認画面へ</button>
        </form>
    </div>
</body>
</html>