# Chapter 1: 環境構築とプロジェクト概要

**所要時間: 10分**

---

## プロジェクト概要

1. **お問い合わせフォーム** - 入力 → 確認 → 完了 の3画面
2. **お知らせCMS** - 一覧、新規作成、編集、削除（CRUD機能）

---

## 環境の起動

ターミナルで以下のコマンドを実行：

```bash
cd _docker
docker compose up -d
```

起動完了まで少し待ちます（初回は数分かかる場合があります）。

---

## 環境確認

以下のURLにアクセスできることを確認：

- http://localhost:8080 （Webサーバー）
- http://localhost:8081 （phpMyAdmin）
- http://localhost:8025 （Mailpit - メール確認）

---

## データベースのテーブル作成

1. http://localhost:8081 にアクセス
2. 左側から `oyamakousen` を選択
3. 上部の「SQL」タブをクリック
4. 以下を貼り付けて「実行」

```sql
CREATE TABLE IF NOT EXISTS `news` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `published_at` DATETIME NOT NULL,
  `is_published` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

5. テスト用データ投入（任意）

```sql
INSERT INTO `news` (`title`, `content`, `published_at`, `is_published`) VALUES
('新年のご挨拶', '明けましておめでとうございます。', '2026-01-01 00:00:00', 1),
('メンテナンスのお知らせ', '2月25日にメンテナンスを実施します。', '2026-02-15 10:00:00', 1);
```

---

## 完成版の確認

- http://localhost:8080/complete-ver/contact/ （お問い合わせフォーム）
- http://localhost:8080/complete-ver/user_admin/ （お知らせCMS）

---

📝 **次のChapter**: データベース接続の実装
