-- One Source Air & Energy Ltd
-- MySQL security phase 3

CREATE TABLE IF NOT EXISTS admin_audit_log (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_user_id INT UNSIGNED NULL,
    username VARCHAR(100) NULL,
    action VARCHAR(120) NOT NULL,
    details TEXT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_admin_user_id (admin_user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS admin_login_attempts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NULL,
    ip_address VARCHAR(45) NULL,
    success TINYINT(1) NOT NULL DEFAULT 0,
    attempted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username_attempted (username, attempted_at),
    INDEX idx_ip_attempted (ip_address, attempted_at),
    INDEX idx_success (success)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE admin_users
    ADD COLUMN role VARCHAR(40) NOT NULL DEFAULT 'admin';

ALTER TABLE admin_users
    ADD COLUMN password_changed_at DATETIME NULL;

ALTER TABLE admin_users
    ADD COLUMN failed_login_count INT UNSIGNED NOT NULL DEFAULT 0;

ALTER TABLE admin_users
    ADD COLUMN locked_until DATETIME NULL;

UPDATE admin_users
SET role = 'super_admin'
WHERE id = (SELECT id FROM (SELECT MIN(id) AS id FROM admin_users) AS first_admin)
  AND role = 'admin';
