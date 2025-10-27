-- Perfect Todo List Database Setup (Simplified)

CREATE DATABASE IF NOT EXISTS todo_list
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

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

INSERT INTO todos (task, completed, due_date, priority) VALUES 
('Sabah kahvaltısı hazırlığı', false, '2025-10-28 08:00:00', 'medium');
