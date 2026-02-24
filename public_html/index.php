<?php
require_once __DIR__ . '/config/database.php';

// 公開中のお知らせを取得
$pdo = getDbConnection();
$stmt = $pdo->query('SELECT * FROM news WHERE is_published = 1 ORDER BY published_at DESC');
$news_list = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お知らせ一覧</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
        }
        h1 {
            margin: 0;
        }
        .news-list {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
        }
        .news-item {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        .news-item:last-child {
            border-bottom: none;
        }
        .news-item:hover {
            background-color: #f9f9f9;
        }
        .news-date {
            color: #999;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .news-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .news-content {
            color: #666;
            line-height: 1.6;
            white-space: pre-wrap;
        }
        .no-news {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        .admin-link {
            text-align: center;
            margin-top: 30px;
        }
        .admin-link a {
            color: #4CAF50;
            text-decoration: none;
            font-size: 14px;
        }
        .admin-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CMSで登録したお知らせを表示させてみよう</h1>
    </div>

    <div class="news-list">
        <?php if (empty($news_list)): ?>
            <div class="no-news">
                現在お知らせはありません
            </div>
        <?php else: ?>
            <?php foreach ($news_list as $news): ?>
                <div class="news-item">
                    <div class="news-date">
                        <?php echo date('Y年m月d日 H:i', strtotime($news['published_at'])); ?>
                    </div>
                    <div class="news-title">
                        <?php echo htmlspecialchars($news['title']); ?>
                    </div>
                    <div class="news-content">
                        <?php echo htmlspecialchars($news['content']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
