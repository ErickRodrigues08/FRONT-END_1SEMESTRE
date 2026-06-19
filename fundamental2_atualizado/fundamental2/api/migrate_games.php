<?php

declare(strict_types=1);

/**
 * Execute UMA VEZ no navegador após configurar config.php
 * Ex.: http://localhost/fundamental2/api/migrate_games.php
 * Cria a tabela games e insere os 18 jogos fixos do torneio.
 * Remova ou bloqueie o acesso a este arquivo após o uso em produção.
 */

require __DIR__ . '/db.php';

header('Content-Type: text/plain; charset=utf-8');

// 1. Criar tabela
$pdo->exec("
CREATE TABLE IF NOT EXISTS games (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  modulo TINYINT UNSIGNED NOT NULL,
  modalidade ENUM('volei','handebol_feminino','handebol_masculino') NOT NULL,
  fase ENUM('semifinal','final') NOT NULL,
  jogo_seq TINYINT UNSIGNED NOT NULL,
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
");
echo "Tabela games verificada/criada.\n";

// 2. Verificar se já há registros
$count = (int)$pdo->query('SELECT COUNT(*) FROM games')->fetchColumn();
if ($count >= 18) {
    echo "Jogos já cadastrados ({$count} registros). Nada a inserir.\n";
    exit;
}

// 3. Seed dos 18 jogos fixos
$seed = [
    // Módulo I — Vôlei
    [1, 'volei', 'semifinal', 1, 'Jogo 1', '07:00'],
    [1, 'volei', 'semifinal', 2, 'Jogo 2', '07:30'],
    [1, 'volei', 'final',     1, 'Final',  '08:00'],
    // Módulo I — Handebol Feminino
    [1, 'handebol_feminino', 'semifinal', 1, 'Jogo 3', '08:40'],
    [1, 'handebol_feminino', 'semifinal', 2, 'Jogo 4', '09:10'],
    [1, 'handebol_feminino', 'final',     1, 'Final',  '09:50'],
    // Módulo I — Handebol Masculino
    [1, 'handebol_masculino', 'semifinal', 1, 'Jogo 5', '10:20'],
    [1, 'handebol_masculino', 'semifinal', 2, 'Jogo 6', '10:50'],
    [1, 'handebol_masculino', 'final',     1, 'Final',  '11:30'],
    // Módulo II — Vôlei
    [2, 'volei', 'semifinal', 1, 'Jogo 1', '07:00'],
    [2, 'volei', 'semifinal', 2, 'Jogo 2', '07:30'],
    [2, 'volei', 'final',     1, 'Final',  '08:00'],
    // Módulo II — Handebol Feminino
    [2, 'handebol_feminino', 'semifinal', 1, 'Jogo 3', '08:40'],
    [2, 'handebol_feminino', 'semifinal', 2, 'Jogo 4', '09:10'],
    [2, 'handebol_feminino', 'final',     1, 'Final',  '09:50'],
    // Módulo II — Handebol Masculino
    [2, 'handebol_masculino', 'semifinal', 1, 'Jogo 5', '10:20'],
    [2, 'handebol_masculino', 'semifinal', 2, 'Jogo 6', '10:50'],
    [2, 'handebol_masculino', 'final',     1, 'Final',  '11:30'],
];

$stmt = $pdo->prepare(
    'INSERT IGNORE INTO games (modulo, modalidade, fase, jogo_seq, jogo_label, horario)
     VALUES (?, ?, ?, ?, ?, ?)'
);

$inserted = 0;
foreach ($seed as $row) {
    $stmt->execute($row);
    $inserted += $stmt->rowCount();
}

echo "{$inserted} jogo(s) inserido(s) com sucesso.\n";
echo "\nPor segurança, remova ou bloqueie o acesso a api/migrate_games.php em produção.\n";
