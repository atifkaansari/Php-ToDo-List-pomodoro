CREATE DATABASE IF NOT EXISTS todo_list CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE todo_list;

CREATE TABLE IF NOT EXISTS todos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task VARCHAR(500) NOT NULL,
    completed BOOLEAN DEFAULT FALSE,
    due_date DATETIME NULL,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE todos 
ADD COLUMN IF NOT EXISTS due_date DATETIME NULL,
ADD COLUMN IF NOT EXISTS priority ENUM('low', 'medium', 'high') DEFAULT 'medium';

ALTER TABLE todos MODIFY COLUMN due_date DATETIME NULL;

-- Insert some sample data for testing with specific times
INSERT INTO todos (task, completed, due_date, priority) VALUES 
('PHP Todo List uygulamasını tamamla', false, '2025-10-30 15:30:00', 'high'),
('Veritabanı tasarımını optimize et', false, '2025-10-28 09:00:00', 'medium'),
('CSS animasyonlarını ekle', true, '2025-10-27 14:45:00', 'high'),
('Responsive tasarımı test et', false, '2025-11-01 11:15:00', 'low'),
('Kullanıcı deneyimini iyileştir', false, '2025-11-05 16:00:00', 'medium'),
('Günlük planlama sistemini test et', false, '2025-10-29 10:30:00', 'high'),
('Countdown özelliğini kontrol et', false, '2025-10-31 13:45:00', 'medium'),
('Öğle yemeği toplantısı', false, '2025-10-27 12:30:00', 'high'),
('Akşam spor', false, '2025-10-27 19:00:00', 'low'),
('Sabah kahvaltısı hazırlığı', false, '2025-10-28 08:00:00', 'medium');