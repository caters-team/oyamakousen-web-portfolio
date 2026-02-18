# Chapter 2: データベース接続の実装

**所要時間: 10分**

---

## データベース接続ファイルとは

このファイルは、PHPからMySQLデータベースに接続するための設定を行います。
各画面で何度も使うので、共通ファイルとして用意します。

**主なポイント:**
- `getDbConnection()` - データベース接続を取得する関数
- `PDO` - PHPでデータベースを安全に操作する仕組み
- 接続は1度だけ行い、使い回す（`static`変数）

---

## ファイル作成

`public_html/config/database.php` を作成し、以下を貼り付け：

```php
<?php
/**
 * データベース接続設定
 */

// データベース接続情報
const DB_HOST = 'db';
const DB_NAME = 'oyamakousen';
const DB_USER = 'oyamakousen_user';
const DB_PASS = 'oyamakousen_password';
const DB_CHARSET = 'utf8mb4';

/**
 * データベース接続を取得
 * @return PDO
 */
function getDbConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                DB_HOST,
                DB_NAME,
                DB_CHARSET
            );
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log('Database connection error: ' . $e->getMessage());
            die('データベース接続エラーが発生しました。');
        }
    }
    
    return $pdo;
}
```

---

## コードの解説

### 接続情報の定数

```php
const DB_HOST = 'db';        // データベースサーバー
const DB_NAME = 'oyamakousen';  // データベース名
const DB_USER = 'oyamakousen_user';  // ユーザー名
const DB_PASS = 'oyamakousen_password';  // パスワード
```

Docker環境では、ホスト名は `db` を使います。

### getDbConnection 関数

- `static $pdo` - 一度接続したら再利用（毎回接続しない）
- `PDO::ATTR_ERRMODE` - エラー時に例外を投げる設定
- `PDO::FETCH_ASSOC` - 連想配列でデータを取得
- `try-catch` - エラーが起きても安全に処理

---

📝 **次のChapter**: お問い合わせフォーム（入力画面）の作成
