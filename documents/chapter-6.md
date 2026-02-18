# Chapter 6: お知らせCMS（一覧・新規作成）

**所要時間: 20分**

---

## お知らせCMSとは

お知らせ情報を管理するシステムです。
データベースのCRUD操作（作成・読取・更新・削除）の基本を学びます。

**主なポイント:**
- SELECT文でデータ一覧取得
- INSERT文でデータ登録
- PDOのプリペアドステートメント

---

## 一覧画面の作成

`public_html/user_admin/index.php` を作成し、以下を貼り付け。

```php
<?php
require_once __DIR__ . '/../config/database.php';

$pdo = getDbConnection();
$stmt = $pdo->query('SELECT * FROM news ORDER BY published_at DESC');
$news_list = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お知らせ管理</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .header {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        h1 {
            margin: 0;
            color: #333;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn-danger {
            background-color: #f44336;
        }
        .btn-danger:hover {
            background-color: #da190b;
        }
        table {
            width: 100%;
            background-color: white;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .status-published {
            color: #4CAF50;
            font-weight: bold;
        }
        .status-draft {
            color: #999;
        }
        .actions a {
            margin-right: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>お知らせ管理</h1>
        <a href="create.php" class="btn">新規作成</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>タイトル</th>
                <th>公開日時</th>
                <th>ステータス</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($news_list)): ?>
                <tr>
                    <td colspan="5" style="text-align: center;">お知らせがありません</td>
                </tr>
            <?php else: ?>
                <?php foreach ($news_list as $news): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($news['id']); ?></td>
                        <td><?php echo htmlspecialchars($news['title']); ?></td>
                        <td><?php echo htmlspecialchars($news['published_at']); ?></td>
                        <td>
                            <span class="<?php echo $news['is_published'] ? 'status-published' : 'status-draft'; ?>">
                                <?php echo $news['is_published'] ? '公開中' : '下書き'; ?>
                            </span>
                        </td>
                        <td class="actions">
                            <a href="edit.php?id=<?php echo $news['id']; ?>" class="btn">編集</a>
                            <a href="delete.php?id=<?php echo $news['id']; ?>" class="btn btn-danger" onclick="return confirm('本当に削除しますか?')">削除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
```

### コードの解説

#### データベース接続

```php
require_once __DIR__ . '/../config/database.php';
$pdo = getDbConnection();
```

Chapter 2で作った接続関数を読み込んで使用します。

#### SELECT文でデータ取得

```php
$stmt = $pdo->query('SELECT * FROM news ORDER BY published_at DESC');
$news_list = $stmt->fetchAll();
```

- `SELECT *` - すべてのカラムを取得
- `ORDER BY published_at DESC` - 公開日時の新しい順
- `fetchAll()` - 全データを配列で取得

#### foreachで繰り返し表示

```php
<?php foreach ($news_list as $news): ?>
    <tr>
        <td><?php echo htmlspecialchars($news['id']); ?></td>
        ...
    </tr>
<?php endforeach; ?>
```

取得したデータを1件ずつテーブル行として表示します。

---

## 新規作成画面の作成

`public_html/user_admin/create.php` を作成し、以下を貼り付け。

```php
<?php
require_once __DIR__ . '/../config/database.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $published_at = trim($_POST['published_at'] ?? '');
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    
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
            $pdo = getDbConnection();
            $stmt = $pdo->prepare('INSERT INTO news (title, content, published_at, is_published) VALUES (?, ?, ?, ?)');
            $stmt->execute([$title, $content, $published_at, $is_published]);
            $success = true;
        } catch (PDOException $e) {
            $errors[] = '登録に失敗しました: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お知らせ新規作成</title>
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
        <h1>お知らせ新規作成</h1>
        
        <?php if ($success): ?>
            <div class="success">
                お知らせを登録しました。
                <a href="index.php">一覧に戻る</a>
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
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="content">本文</label>
                <textarea id="content" name="content"><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="published_at">公開日時</label>
                <input type="datetime-local" id="published_at" name="published_at" value="<?php echo htmlspecialchars($_POST['published_at'] ?? ''); ?>">
            </div>
            
            <div class="form-group checkbox-group">
                <input type="checkbox" id="is_published" name="is_published" <?php echo isset($_POST['is_published']) ? 'checked' : ''; ?>>
                <label for="is_published" style="margin: 0;">公開する</label>
            </div>
            
            <div>
                <button type="submit" class="btn">登録</button>
                <a href="index.php" class="btn btn-secondary">キャンセル</a>
            </div>
        </form>
    </div>
</body>
</html>
```

### コードの解説

#### trim() 関数

```php
$title = trim($_POST['title'] ?? '');
```

前後の空白を削除します。ユーザーが誤ってスペースを入力した場合に対応。

#### プリペアドステートメント（INSERT）

```php
$stmt = $pdo->prepare('INSERT INTO news (title, content, published_at, is_published) VALUES (?, ?, ?, ?)');
$stmt->execute([$title, $content, $published_at, $is_published]);
```

- `prepare()` - SQL文のテンプレートを作成（`?` がプレースホルダー）
- `execute()` - 値を当てはめて実行

**なぜプリペアドステートメントを使うか:**
- SQLインジェクション攻撃を防ぐ
- 値を自動的にエスケープ

#### チェックボックスの処理

```php
$is_published = isset($_POST['is_published']) ? 1 : 0;
```

チェックボックスはチェックされていない時は送信されないので、`isset()` で確認します。

#### try-catch でエラー処理

```php
try {
    $stmt->execute(...);
    $success = true;
} catch (PDOException $e) {
    $errors[] = '登録に失敗しました: ' . $e->getMessage();
}
```

データベース操作でエラーが起きた場合に適切に処理します。

---

## 動作確認

1. http://localhost:8080/user_admin/ にアクセス
2. 一覧が表示されることを確認
3. 「新規作成」からデータを登録
4. 一覧に反映されることを確認

---

📝 **次のChapter**: お知らせの編集・削除の実装
