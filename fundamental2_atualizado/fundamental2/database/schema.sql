-- Banco: sesi_interclasse (crie no phpMyAdmin antes de importar)
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS student_modalities;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS admins;

CREATE TABLE admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(64) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE students (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(180) NOT NULL,
  age TINYINT UNSIGNED NOT NULL,
  grade TINYINT UNSIGNED NOT NULL,
  class CHAR(1) NOT NULL,
  gender ENUM('masculino','feminino') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT chk_grade CHECK (grade BETWEEN 6 AND 9),
  CONSTRAINT chk_class CHECK (class IN ('A','B')),
  INDEX idx_grade (grade),
  INDEX idx_class (class),
  INDEX idx_gender (gender),
  INDEX idx_full_name (full_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE student_modalities (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id INT UNSIGNED NOT NULL,
  modality ENUM('handebol_feminino','handebol_masculino','volei_misto') NOT NULL,
  UNIQUE KEY uq_student_modality (student_id, modality),
  CONSTRAINT fk_sm_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS games (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  modulo TINYINT UNSIGNED NOT NULL COMMENT '1 = 6º/7º anos · 2 = 8º/9º anos',
  modalidade ENUM('volei','handebol_feminino','handebol_masculino') NOT NULL,
  fase ENUM('semifinal','final') NOT NULL,
  jogo_seq TINYINT UNSIGNED NOT NULL COMMENT '1 ou 2 para semis; 1 para final',
  jogo_label VARCHAR(20) NOT NULL DEFAULT '',
  horario VARCHAR(8) NOT NULL DEFAULT '',
  time1 VARCHAR(100) NULL DEFAULT NULL,
  time2 VARCHAR(100) NULL DEFAULT NULL,
  placar1 TINYINT UNSIGNED NULL DEFAULT NULL,
  placar2 TINYINT UNSIGNED NULL DEFAULT NULL,
  vencedor VARCHAR(100) NULL DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_game (modulo, modalidade, fase, jogo_seq),
  INDEX idx_modulo (modulo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
