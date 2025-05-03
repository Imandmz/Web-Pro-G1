-- Erstellen der Datenbank
CREATE DATABASE IF NOT EXISTS energyfans CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE energyfans;

-- Tabelle: Kunden
CREATE TABLE kunden (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(128) NOT NULL,
    secret VARCHAR(255) NOT NULL,
    punkte INT DEFAULT 100,
    last_login DATETIME,
    first_login BOOLEAN DEFAULT 1
);

-- Tabelle: Produkte
CREATE TABLE produkte (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    preis DECIMAL(10,2), -- Entfernt NOT NULL, damit NULL erlaubt ist
    bild VARCHAR(255) NOT NULL,
    bestand INT DEFAULT 100
);

-- Tabelle: Warenkorb
CREATE TABLE warenkorb (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    produkt_id INT NOT NULL,
    menge INT DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES kunden(id),
    FOREIGN KEY (produkt_id) REFERENCES produkte(id)
);

-- Tabelle: Bestellungen
CREATE TABLE bestellungen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    gesamtpreis DECIMAL(10,2) NOT NULL,
    versandart VARCHAR(50),
    datum DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES kunden(id)
);

-- Tabelle: Bestellpositionen
CREATE TABLE bestellpositionen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bestellung_id INT NOT NULL,
    produkt_id INT NOT NULL,
    menge INT NOT NULL,
    einzelpreis DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (bestellung_id) REFERENCES bestellungen(id),
    FOREIGN KEY (produkt_id) REFERENCES produkte(id)
);

-- Beispielprodukte einfügen
INSERT INTO produkte (name, preis, bild) VALUES
('EnergyFun Classic', 2.49, 'energy1.jpg'),
('EnergyFun Power', 2.99, 'energy2.jpg'),
('EnergyFun Night', 3.29, 'energy3.jpg'),
('EnergyFun Wildberry', 2.79, 'energy4.jpg'),
('EnergyFun Tropical', 3.19, 'energy5.jpg'),
('EnergyFun Lemon', 2.39, 'energy6.jpg'),
('EnergyFun Strong', 3.49, 'energy7.jpg'),
('EnergyFun Fresh', 2.59, 'energy8.jpg'),
('EnergyÜberraschung', 4.99, 'energy9.jpg'),
('Black & Hot', 2.00, 'energy10.jpg'),
('Coming Soon', 4.00, 'energy11.jpg');

-- Tabelle: Online-Status (für AJAX-Nutzeranzeige)
CREATE TABLE IF NOT EXISTS online (
    user_id INT PRIMARY KEY,
    timestamp DATETIME,
    FOREIGN KEY (user_id) REFERENCES kunden(id) ON DELETE CASCADE
);

CREATE TABLE punkte (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  punkte INT NOT NULL,
  grund VARCHAR(255),
  datum DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES kunden(id)
);

CREATE TABLE online (
    user_id INT NOT NULL,
    last_active TIMESTAMP NOT NULL,
    PRIMARY KEY (user_id),
    FOREIGN KEY (user_id) REFERENCES kunden(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS online (
  user_id INT PRIMARY KEY,
  timestamp DATETIME,
  FOREIGN KEY (user_id) REFERENCES kunden(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS online (
    user_id INT PRIMARY KEY,
    timestamp DATETIME,
    FOREIGN KEY (user_id) REFERENCES kunden(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS punkte (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    punkte INT NOT NULL,
    grund VARCHAR(255),
    datum DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES kunden(id) ON DELETE CASCADE
);
