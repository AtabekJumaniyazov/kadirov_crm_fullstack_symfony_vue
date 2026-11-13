-- Kadirov Inc CRM - Ma'lumotlar ombori sxemasi (MySQL / MariaDB / SQLite)

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    plain_password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    last_active VARCHAR(100) NOT NULL DEFAULT 'Hozirgina',
    roles JSON NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(180) NOT NULL,
    password VARCHAR(255) NOT NULL,
    last_active VARCHAR(100) NOT NULL DEFAULT 'Hozirgina',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(180) NOT NULL,
    password VARCHAR(255) NOT NULL,
    company_id INT NOT NULL,
    last_active VARCHAR(100) NOT NULL DEFAULT 'Hozirgina',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_customer_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);

-- Boshlang'ich ma'lumotlar (Figma dizayniga mos)
INSERT INTO users (name, email, password, plain_password, last_active, roles) VALUES
('Ameliya', 'ameliya.cer@gmail.com', '$2y$13$hashedpassword', '12345678', 'Hozir onlayn', '["ROLE_ADMIN", "ROLE_USER"]'),
('Lindsey Stroud', 'lindsey.stroud@gmail.com', '$2y$13$hashedpassword', '45638764583', '5 minut oldin', '["ROLE_USER"]'),
('Nicci Troiani', 'nicci.troiani@gmail.com', '$2y$13$hashedpassword', '12345678', '14 minut oldin', '["ROLE_USER"]'),
('George Fields', 'george.fields@gmail.com', '$2y$13$hashedpassword', '12345678', '6 minut oldin', '["ROLE_USER"]'),
('Rebecca Moore', 'rebecca.moore@gmail.com', '$2y$13$hashedpassword', '12345678', 'Dec 14, 2018', '["ROLE_USER"]'),
('Jane Doe', 'jane.doe@gmail.com', '$2y$13$hashedpassword', '12345678', 'Dec 12, 2018', '["ROLE_USER"]'),
('Dermot Jones', 'dermot.jones@gmail.com', '$2y$13$hashedpassword', '12345678', 'Dec 11, 2018', '["ROLE_USER"]'),
('Martin Merces', 'martin.merces@gmail.com', '$2y$13$hashedpassword', '12345678', 'Dec 9, 2018', '["ROLE_USER"]'),
('Franz Ferdinand', 'franz.ferdiand@gmail.com', '$2y$13$hashedpassword', '12345678', 'Dec 6, 2018', '["ROLE_USER"]'),
('John Smith', 'john.smith@gmail.com', '$2y$13$hashedpassword', '12345678', 'Nov 30, 2018', '["ROLE_USER"]'),
('Judith Williams', 'judith.williams@gmail.com', '$2y$13$hashedpassword', '12345678', 'Nov 26, 2018', '["ROLE_USER"]');
