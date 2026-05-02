-- loại bỏ cột cũ

USE rpg_game_db;

ALTER TABLE users DROP COLUMN role;
DROP FOREIGN KEY fk_characters_player,
  DROP COLUMN player_id,
  DROP COLUMN class,
  DROP COLUMN attack,
  DROP COLUMN defense;