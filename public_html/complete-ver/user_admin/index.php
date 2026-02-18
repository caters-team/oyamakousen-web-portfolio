<?php
// require_once : ファイルを一度だけ読み込むための関数。指定されたファイルがすでに読み込まれている場合は再度読み込まない。
// __DIR__ : 現在のファイルが存在するディレクトリのパスを返す定数。これにより、相対パスでファイルを指定することができる。
require_once __DIR__ . '/../config/database.php';

// DB接続を取得
$pdo = getDbConnection();
// SQLクエリの実行
$stmt = $pdo->query('SELECT * FROM news ORDER BY published_at DESC');
// クエリの結果を配列として取得
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
<script>
    console.log(<?php echo json_encode($news_list); ?>);
</script>
</html>