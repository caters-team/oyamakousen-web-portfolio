<?php
/**
 * データベース接続設定
 */

// データベース接続情報
// const : 定数を定義するためのキーワード。定数は一度定義すると値を変更できない。
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
            
            // PDO（PHP Data Objects）の接続オプションを設定
            $options = [
                // エラーモードを例外に設定。これにより、エラーが発生した場合に例外がスローされるようになります。
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                // デフォルトのフェッチモードを連想配列に設定。つまりSQL実行結果を扱いやすい形式で取得できるようになる。
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // プリペアドステートメントのエミュレーションを無効化。簡単にいうと、SQLの組み立てをMySQL側で行うということ。その方が安全で早い。
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            // PDOクラスのインスタンス作成。これにより、データベースへの接続が確立されます。
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log('Database connection error: ' . $e->getMessage());
            die('データベース接続エラーが発生しました。');
        }
    }
    
    return $pdo;
}