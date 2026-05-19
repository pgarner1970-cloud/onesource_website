-- One Source Air & Energy Ltd
-- MySQL data migration phase 2
-- Runtime settings/content tables.
-- Safe to run multiple times.

CREATE TABLE IF NOT EXISTS site_settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value LONGTEXT NULL,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS opening_hours (
    day_key VARCHAR(20) PRIMARY KEY,
    status VARCHAR(50) NOT NULL DEFAULT 'Closed',
    open_time VARCHAR(10) NULL,
    close_time VARCHAR(10) NULL,
    sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS social_links (
    platform_key VARCHAR(60) PRIMARY KEY,
    label VARCHAR(100) NOT NULL,
    url TEXT NULL,
    enabled TINYINT(1) NOT NULL DEFAULT 0,
    sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS service_images (
    service_key VARCHAR(80) PRIMARY KEY,
    image_path TEXT NULL,
    default_image_path TEXT NULL,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS projects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NULL,
    category VARCHAR(80) NOT NULL,
    location VARCHAR(255) NULL,
    description TEXT NULL,
    alt_text VARCHAR(255) NULL,
    image_path TEXT NOT NULL,
    featured TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_featured (featured),
    INDEX idx_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS article_drafts (
    id VARCHAR(80) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    excerpt TEXT NULL,
    body LONGTEXT NULL,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    related_service VARCHAR(120) NULL,
    source VARCHAR(80) NULL,
    topic VARCHAR(255) NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS articles (
    id VARCHAR(80) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT NULL,
    body LONGTEXT NULL,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    related_service VARCHAR(120) NULL,
    source VARCHAR(80) NULL,
    topic VARCHAR(255) NULL,
    published TINYINT(1) NOT NULL DEFAULT 1,
    published_at DATETIME NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    INDEX idx_published (published),
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO opening_hours (day_key, status, open_time, close_time, sort_order) VALUES
('monday', 'Open', '08:00', '17:00', 1),
('tuesday', 'Open', '08:00', '17:00', 2),
('wednesday', 'Open', '08:00', '17:00', 3),
('thursday', 'Open', '08:00', '17:00', 4),
('friday', 'Open', '08:00', '17:00', 5),
('saturday', 'By appointment', NULL, NULL, 6),
('sunday', 'Closed', NULL, NULL, 7);

INSERT IGNORE INTO site_settings (setting_key, setting_value) VALUES
('opening_hours_notice', 'Closed bank holidays'),
('trustpilot_enabled', '1'),
('trustpilot_business_unit_id', ''),
('trustpilot_profile_url', 'https://uk.trustpilot.com/review/onesourceairandenergyltd.co.uk'),
('trustpilot_heading', 'Rated by our customers'),
('trustpilot_intro', 'See what customers say about One Source Air & Energy Ltd.'),
('article_ai_enabled', '1'),
('article_notify_email', 'pgarner1970@googlemail.com'),
('article_drafts_per_week', '1'),
('article_openai_api_key', ''),
('article_model', 'gpt-4.1-mini'),
('article_topic_focus', 'UK homeowners and small businesses needing air conditioning, electrical, gas, oil heating, solar PV, battery storage and EV charger advice.');

INSERT IGNORE INTO social_links (platform_key, label, url, enabled, sort_order) VALUES
('facebook', 'Facebook', 'https://www.facebook.com/Onesourceairandenergyltd/', 1, 1),
('instagram', 'Instagram', '', 0, 2),
('whatsapp', 'WhatsApp', 'https://wa.me/447502216131', 1, 3),
('linkedin', 'LinkedIn', '', 0, 4),
('youtube', 'YouTube', '', 0, 5),
('tiktok', 'TikTok', '', 0, 6),
('x', 'X / Twitter', '', 0, 7),
('google_business', 'Google Business', '', 0, 8),
('trustpilot', 'Trustpilot', '', 0, 9),
('checkatrade', 'Checkatrade', '', 0, 10),
('mybuilder', 'MyBuilder', '', 0, 11),
('rated_people', 'Rated People', '', 0, 12);

INSERT IGNORE INTO service_images (service_key, image_path, default_image_path) VALUES
('air-conditioning', 'assets/images/services/air-conditioning.jpg', 'assets/images/services/air-conditioning.jpg'),
('solar-pv', 'assets/images/services/solar-pv.jpg', 'assets/images/services/solar-pv.jpg'),
('battery-storage', 'assets/images/services/battery-storage.jpg', 'assets/images/services/battery-storage.jpg'),
('ev-chargers', 'assets/images/services/ev-chargers.jpg', 'assets/images/services/ev-chargers.jpg'),
('electrical-services', 'assets/images/services/electrical-services.jpg', 'assets/images/services/electrical-services.jpg'),
('gas-services', 'assets/images/services/gas-services.jpg', 'assets/images/services/gas-services.jpg'),
('oil-installations', 'assets/images/services/oil-installations.jpg', 'assets/images/services/oil-installations.jpg');
