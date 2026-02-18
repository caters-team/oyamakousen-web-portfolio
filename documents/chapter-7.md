# Chapter 7: お知らせCMS（編集・削除）

**所要時間: 20分**

---

## 編集・削除機能とは

既存のデータを更新（UPDATE）または削除（DELETE）する機能です。
これでCRUD操作の全てが揃います。

**主なポイント:**
- UPDATE文でデータ更新
- DELETE文でデータ削除
- URLパラメータでIDを受け取る

---

## 編集画面の作成

`public_html/user_admin/edit.php` を作成し、以下を貼り付け。

```php
<?php
require_once __DIR__ . '/../config/database.php';

$errors = [];
$success = false;
$news = null;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int)$_GET['id'];
$pdo = getDbConnection();

// GET時: データ取得
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare('SELECT * FROM news WHERE id = ?');
    $stmt->execute([$id]);
    $news = $stmt->fetch();
    
    if (!$news) {
        header('Location: index.php');
        exit;
    }
}

// POST時: 更新処理
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
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($news['title']); ?>">
            </div>
            
            <div class="form-group">
                <label for="content">本文</label>
                <textarea id="content" name="content"><?php echo htmlspecialchars($news['content']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="published_at">公開日時</label>
                <input type="datetime-local" id="published_at" name="published_at" value="<?php echo htmlspecialchars(str_replace(' ', 'T', $news['published_at'])); ?>">
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
```

### コードの解説

#### URLパラメータの取得

```php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$id = (int)$_GET['id'];
```

- `$_GET['id']` - URLの `?id=123` から値を取得
- `is_numeric()` - 数値かどうかチェック
- `(int)` - 整数にキャスト（型変換）

#### UPDATE文

```php
$stmt = $pdo->prepare('UPDATE news SET title = ?, content = ?, published_at = ?, is_published = ? WHERE id = ?');
$stmt->execute([$title, $content, $published_at, $is_published, $id]);
```

- `UPDATE テーブル名 SET カラム = 値 WHERE 条件`
- WHERE句で更新対象を指定（これがないと全データが更新される）

#### datetime-local の値変換

```php
value="<?php echo htmlspecialchars(str_replace(' ', 'T', $news['published_at'])); ?>"
```

データベースの日時形式 `2026-02-18 10:00:00` を
HTML5の datetime-local形式 `2026-02-18T10:00:00` に変換します。

---

## 削除機能の作成

`public_html/user_admin/delete.php` を作成し、以下を貼り付け。

```php
<?php
require_once __DIR__ . '/../config/database.php';

// IDの取得
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header('Location: index.php');
    exit;
}

$pdo = getDbConnection();

try {
    $stmt = $pdo->prepare('DELETE FROM news WHERE id = ?');
    $stmt->execute([$id]);
    
    header('Location: index.php?deleted=1');
    exit;
} catch (PDOException $e) {
    error_log('Delete error: ' . $e->getMessage());
    header('Location: index.php?error=1');
    exit;
}
```

### コードの解説

#### シンプルな削除処理

```php
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header('Location: index.php');
    exit;
}
```

- `$_GET['id'] ?? null` - IDが無い場合はnullを設定
- `!$id || !is_numeric($id)` - IDが無いか、数値でない場合は一覧へ

#### DELETE文とリダイレクト

```php
$stmt = $pdo->prepare('DELETE FROM news WHERE id = ?');
$stmt->execute([$id]);

header('Location: index.php?deleted=1');
exit;
```

- `DELETE FROM テーブル名 WHERE 条件`
- 削除後は一覧画面にリダイレクト
- `?deleted=1` でメッセージ表示用のパラメータを付与

#### エラー処理

```php
error_log('Delete error: ' . $e->getMessage());
header('Location: index.php?error=1');
```

- `error_log()` - エラー内容をログに記録
- `?error=1` でエラーメッセージを表示

#### 一覧画面での確認ダイアログ

削除リンクには JavaScript の confirm を使用:

```html
<a href="delete.php?id=<?php echo $news['id']; ?>" onclick="return confirm('本当に削除しますか?')">削除</a>
```

ユーザーが「キャンセル」を押した場合、リンクをクリックしても画面遷移しません。

---

## 動作確認

### 編集機能

1. 一覧画面から「編集」をクリック
2. 内容を変更して「更新」をクリック
3. 一覧に戻って変更が反映されていることを確認

### 削除機能

1. 一覧画面から「削除」をクリック
2. JavaScriptの確認ダイアログで「OK」
3. 削除処理が実行され、一覧画面に戻る
4. データが一覧から消えていることを確認

---

## まとめ

これで以下の機能が完成しました:

### お問い合わせフォーム
- ✅ 入力画面（Chapter 3）
- ✅ バリデーションと確認画面（Chapter 4）
- ✅ メール送信と完了画面（Chapter 5）

### お知らせCMS（CRUD操作）
- ✅ **C**reate - 新規作成（Chapter 6）
- ✅ **R**ead - 一覧表示（Chapter 6）
- ✅ **U**pdate - 編集（Chapter 7）
- ✅ **D**elete - 削除（Chapter 7）

🎉 **お疲れ様でした！** 基本的なWebアプリケーション開発の流れを習得できました。
