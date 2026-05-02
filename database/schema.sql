-- RPG Game Manager - Tạo database và bảng

CREATE DATABASE IF NOT EXISTS rpg_game_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE rpg_game_db;

-- Chỉ lưu tài khoản quản trị (đăng nhập qua bảng users)
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(64) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS players (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(180) NOT NULL,
  kill_character_id INT UNSIGNED NULL,
  item_id INT UNSIGNED NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS characters (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  level INT UNSIGNED NOT NULL DEFAULT 1,
  hp INT UNSIGNED NOT NULL DEFAULT 100,
  mana INT UNSIGNED NOT NULL DEFAULT 50,
  kill_by_player_id INT UNSIGNED NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS skills (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  damage INT UNSIGNED NOT NULL DEFAULT 0,
  mana_cost INT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  type VARCHAR(64) NOT NULL,
  power INT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS character_skills (
  character_id INT UNSIGNED NOT NULL,
  skill_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (character_id, skill_id),
  CONSTRAINT fk_cs_character FOREIGN KEY (character_id) REFERENCES characters(id) ON DELETE CASCADE,
  CONSTRAINT fk_cs_skill FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS character_items (
  character_id INT UNSIGNED NOT NULL,
  item_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (character_id, item_id),
  CONSTRAINT fk_ci_character FOREIGN KEY (character_id) REFERENCES characters(id) ON DELETE CASCADE,
  CONSTRAINT fk_ci_item FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
) ENGINE=InnoDB;

ALTER TABLE players
  ADD CONSTRAINT fk_players_kill_character
    FOREIGN KEY (kill_character_id) REFERENCES characters(id)
    ON DELETE SET NULL,
  ADD CONSTRAINT fk_players_item
    FOREIGN KEY (item_id) REFERENCES items(id)
    ON DELETE SET NULL;

ALTER TABLE characters
  ADD CONSTRAINT fk_characters_kill_by_player
    FOREIGN KEY (kill_by_player_id) REFERENCES players(id)
    ON DELETE SET NULL;

-- Tài khoản mặc định: admin / admin123 (password đã hash bcrypt)
INSERT INTO users (username, password) VALUES
('admin', '$2y$10$JUgO1w2Vhlefphf7nrQ7ZepcYgGoVor6AfUd0Az1OHT4rr52swMa.');

-- Dữ liệu mẫu (tùy chọn)
INSERT INTO players (name, email) VALUES
('Player One', 'p1@example.com'),
('Player Two', 'p2@example.com');

INSERT INTO skills (name, damage, mana_cost) VALUES
('Fireball', 40, 25),
('Heal', 0, 15),
('Arrow Shot', 22, 8);

INSERT INTO items (name, type, power) VALUES
('Iron Sword', 'weapon', 12),
('Leather Armor', 'armor', 5),
('Health Potion', 'potion', 20);

INSERT INTO characters (name, level, hp, mana) VALUES
('Aldric', 5, 200, 40),
('Lyra', 4, 90, 120);

INSERT INTO character_skills (character_id, skill_id) VALUES (1, 3), (2, 1), (2, 2);
INSERT INTO character_items (character_id, item_id) VALUES (1, 1), (1, 3), (2, 2);
