<?php
declare(strict_types=1);

function getDb(): mysqli
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $conn = new mysqli('db', 'root', 'root');
    $conn->set_charset('utf8mb4');

    $conn->query("CREATE DATABASE IF NOT EXISTS stu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $conn->select_db('stu');

    $conn->query(
        "CREATE TABLE IF NOT EXISTS kcb (
            id INT(8) NOT NULL,
            name VARCHAR(10) NOT NULL,
            xf INT(11) DEFAULT NULL,
            xq INT(11) DEFAULT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    // 保证课程号可手工录入（去掉自增属性）。
    $conn->query("ALTER TABLE kcb MODIFY id INT(8) NOT NULL");

    $conn->query(
        "INSERT IGNORE INTO kcb (id, name, xf, xq) VALUES
        (1002, '数学', 5, 6),
        (1003, '大学物理', 4, 2),
        (1004, '商务英语', 4, 4),
        (1005, '计算机原理', 4, 5),
        (1006, 'php', 4, 6)"
    );

    return $conn;
}
