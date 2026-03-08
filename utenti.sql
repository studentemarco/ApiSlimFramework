CREATE TABLE Utenti (
  uid INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  ruolo ENUM('admin','fornitore') NOT NULL,
  fid INT NULL,
  attivo TINYINT(1) NOT NULL DEFAULT 1,
  FOREIGN KEY (fid) REFERENCES Fornitori(fid)
);

-- admin
INSERT INTO Utenti (username, password_hash, ruolo, fid)
VALUES ('admin', '$2y$10$Xf9yQm6wz0sQmTQfE6V8QONb0An5Rz4l9K2QxEw9W9m3Qp9mQ7W4S', 'admin', NULL);
-- password: admin123

-- fornitore
INSERT INTO Utenti (username, password_hash, ruolo, fid)
VALUES ('acme', '$2y$10$QqD5s7hR4f4w7kQk8cK2L.8xw5Q3vK3zP4fM1a6X4S7eC6p0zKJ5a', 'fornitore', 1);
-- password: acme123


-- php -r "echo password_hash('admin123', PASSWORD_DEFAULT), PHP_EOL;"