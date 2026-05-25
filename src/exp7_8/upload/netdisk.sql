CREATE TABLE IF NOT EXISTS netdisk_file (
    file_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    file_name VARCHAR(255) NOT NULL,
    file_save VARCHAR(255) NOT NULL,
    file_size INT(10) UNSIGNED NOT NULL,
    file_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    folder_id INT(10) UNSIGNED NOT NULL,
    PRIMARY KEY (file_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS netdisk_folder (
    folder_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    folder_name VARCHAR(255) NOT NULL,
    folder_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    folder_path VARCHAR(255) NOT NULL,
    folder_pid INT(10) UNSIGNED NOT NULL,
    PRIMARY KEY (folder_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 示例数据（可选）
INSERT INTO netdisk_folder(folder_id, folder_name, folder_path, folder_pid)
VALUES
(1, 'test01', '0', 0),
(2, 'test02', '0', 0),
(3, 'test03', '1', 1)
ON DUPLICATE KEY UPDATE folder_name = VALUES(folder_name), folder_path = VALUES(folder_path), folder_pid = VALUES(folder_pid);
