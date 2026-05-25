CREATE DATABASE IF NOT EXISTS stu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE stu;

CREATE TABLE IF NOT EXISTS kcb (
    id INT(8) NOT NULL,
    name VARCHAR(10) NOT NULL,
    xf INT(11) DEFAULT NULL,
    xq INT(11) DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO kcb (id, name, xf, xq) VALUES
(1002, '数学', 5, 6),
(1003, '大学物理', 4, 2),
(1004, '商务英语', 4, 4),
(1005, '计算机原理', 4, 5),
(1006, 'php', 4, 6)
ON DUPLICATE KEY UPDATE
name = VALUES(name),
xf = VALUES(xf),
xq = VALUES(xq);
