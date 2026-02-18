# 小山高専 Web ポートフォリオ

PHP + MySQL によるお問い合わせフォームとお知らせCMSの実装プロジェクトです。

## 技術スタック

- **PHP 8.3 + Apache**: Webサーバー
- **MySQL 8.0**: データベース
- **Mailpit**: メール送信テスト用SMTPサーバー
- **Docker Compose**: コンテナオーケストレーション

## 環境構築

### 起動

```bash
cd _docker
docker compose up -d
```

### 停止

```bash
cd _docker
docker compose down
```

## アクセス

- **アプリケーション**: http://localhost:8080
- **Mailpit Web UI**: http://localhost:8025 (送信メール確認)
- **MySQL**: localhost:3307 (root / password)

## 機能

### お問い合わせフォーム

- 入力画面 (`contact/index.php`)
- バリデーション・確認画面 (`contact/confirm.php`)
- メール送信・完了画面 (`contact/complete.php`)

### お知らせCMS

- 一覧表示 (`user_admin/index.php`)
- 新規作成 (`user_admin/create.php`)
- 編集 (`user_admin/edit.php`)
- 削除 (`user_admin/delete.php`)

## ディレクトリ構成

```
.
├── _docker/              # Docker環境
│   ├── docker-compose.yml
│   ├── Dockerfile
│   └── db/
├── documents/            # 学習用ドキュメント（Chapter 1-7）
├── public_html/          # アプリケーションコード
│   ├── complete-ver/     # 完成版コード
│   └── user_admin/       # トレーニング用（実装対象）
└── README.md
```

## 学習教材

`documents/` ディレクトリに7章構成の学習教材があります（所要時間: 1時間40分）。

- **Chapter 1**: 環境構築・DB作成 (10分)
- **Chapter 2**: データベース接続 (10分)
- **Chapter 3**: お問い合わせフォーム入力 (15分)
- **Chapter 4**: バリデーション・確認 (15分)
- **Chapter 5**: メール送信・完了 (10分)
- **Chapter 6**: お知らせCMS一覧・作成 (20分)
- **Chapter 7**: お知らせCMS編集・削除 (20分)

各章はコピー&ペーストで実装できる形式になっています。

## データベース

### テーブル構成

**news** テーブル:
- `id`: INT (PK, AUTO_INCREMENT)
- `title`: VARCHAR(200)
- `content`: TEXT
- `published_at`: DATETIME
- `is_published`: TINYINT(1)
- `created_at`: TIMESTAMP
- `updated_at`: TIMESTAMP

初期データは `_docker/db/initdb.d/init.sql` で自動投入されます。
