-- Mevcut verileri kontrol et
INSERT INTO roles (id, name, created_at, updated_at)
SELECT 1, 'admin', NOW(), NOW()
WHERE NOT EXISTS (SELECT * FROM roles WHERE id = 1);

INSERT INTO roles (id, name, created_at, updated_at)
SELECT 2, 'user', NOW(), NOW()
WHERE NOT EXISTS (SELECT * FROM roles WHERE id = 2);
