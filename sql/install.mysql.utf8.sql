CREATE TABLE IF NOT EXISTS `#__photoorders_albums` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `media_folder` VARCHAR(255) NOT NULL, -- Path relative to media manager (e.g., 'local-images:/client_a')
    `allowed_counts` TEXT NOT NULL,       -- JSON: {"4x6": 10, "5x7": 2, "8x10": 1}
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `#__photoorders_orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `album_id` INT NOT NULL,
    `client_name` VARCHAR(255) NOT NULL,
    `order_data` LONGTEXT NOT NULL,       -- JSON array of items [{filename, size, qty}]
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`album_id`) REFERENCES `#__photoorders_albums`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;