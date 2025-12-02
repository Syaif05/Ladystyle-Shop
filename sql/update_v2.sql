-- sql/update_v2.sql

-- 1. Update tabel users untuk mendukung role customer dan data tambahan
ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'staff', 'customer') DEFAULT 'customer';
ALTER TABLE users ADD COLUMN phone VARCHAR(20) AFTER email;
ALTER TABLE users ADD COLUMN address TEXT AFTER phone;
ALTER TABLE users ADD COLUMN reset_token VARCHAR(255) NULL;

-- 2. Buat tabel untuk multiple images
CREATE TABLE IF NOT EXISTS product_gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- 3. (Opsional) Data dummy customer
INSERT INTO users (name, email, password, role, phone, address) VALUES 
('Siti Customer', 'siti@gmail.com', '123456', 'customer', '081234567890', 'Jl. Mawar Melati No. 12, Cirebon');