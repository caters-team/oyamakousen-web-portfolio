<?php
require_once __DIR__ . '/../config/database.php';

$errors = [];
$success = false;
$news = null;

// IDの取得
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header('Location: index.php');
    exit;
}

$pdo = getDbConnection();

// 既存データの取得
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare('SELECT * FROM news WHERE id = ?');
    $stmt->execute([$id]);
    $news = $stmt->fetch();
    
    if (!$news) {
        header('Location: index.php');
        exit;
    }
}

// 更新処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $published_at = trim($_POST['published_at'] ?? '');
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    
    // バリデーション
    if (empty($title)) {
        $errors[] = 'タイトルを入力してください。';
    }
    if (empty($content)) {
        $errors[] = '本文を入力してください。';
    }
    if (empty($published_at)) {
        $errors[] = '公開日時を入力してください。';
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare('UPDATE news SET title = ?, content = ?, published_at = ?, is_published = ? WHERE id = ?');
            $stmt->execute([$title, $content, $published_at, $is_published, $id]);
            $success = true;
            
            // 更新後のデータを再取得
            $stmt = $pdo->prepare('SELECT * FROM news WHERE id = ?');
            $stmt->execute([$id]);
            $news = $stmt->fetch();
        } catch (PDOException $e) {
            $errors[] = '更新に失敗しました: ' . $e->getMessage();
        }
    } else {
        // エラー時は入力値を保持
        $news = [
            'id' => $id,
            'title' => $title,
            'content' => $content,
            'published_at' => $published_at,
            'is_published' => $is_published
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お知らせ編集</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
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
        input[type="datetime-local"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        textarea {
            min-height: 200px;
            resize: vertical;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
        }
        .checkbox-group input {
            width: auto;
            margin-right: 10px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn-secondary {
            background-color: #999;
        }
        .btn-secondary:hover {
            background-color: #777;
        }
        .error {
            background-color: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .success {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>お知らせ編集</h1>
        
        <?php if ($success): ?>
            <div class="success">
                お知らせを更新しました。
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="title">タイトル</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($news['title']); ?>">
            </div>
            
            <div class="form-group">
                <label for="content">本文</label>
                <textarea id="content" name="content"><?php echo htmlspecialchars($news['content']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="published_at">公開日時</label>
                <input type="datetime-local" id="published_at" name="published_at" value="<?php echo date('Y-m-d\TH:i', strtotime($news['published_at'])); ?>">
            </div>
            
            <div class="form-group checkbox-group">
                <input type="checkbox" id="is_published" name="is_published" <?php echo $news['is_published'] ? 'checked' : ''; ?>>
                <label for="is_published" style="margin: 0;">公開する</label>
            </div>
            
            <div>
                <button type="submit" class="btn">更新</button>
                <a href="index.php" class="btn btn-secondary">キャンセル</a>
            </div>
        </form>
    </div>
</body>
</html>