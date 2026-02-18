-- 追加でDB作成したいときとかに
CREATE DATABASE IF NOT EXISTS `cms_test`;
GRANT ALL PRIVILEGES ON `cms_test`.* TO `blade-cms`@`%`;
