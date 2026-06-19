CREATE DATABASE IF NOT EXISTS destino_certo_turismo
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE destino_certo_turismo;

CREATE TABLE IF NOT EXISTS clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  telefone VARCHAR(30) NOT NULL,
  documento VARCHAR(30) NOT NULL UNIQUE,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS pacotes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  destino VARCHAR(120) NOT NULL,
  descricao TEXT NOT NULL,
  duracao_dias INT NOT NULL,
  preco DECIMAL(10, 2) NOT NULL,
  vagas INT NOT NULL DEFAULT 0,
  ativo TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS reservas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  pacote_id INT NOT NULL,
  data_viagem DATE NOT NULL,
  quantidade_pessoas INT NOT NULL DEFAULT 1,
  observacoes TEXT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reserva_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  CONSTRAINT fk_reserva_pacote FOREIGN KEY (pacote_id) REFERENCES pacotes(id)
);

INSERT INTO pacotes (destino, descricao, duracao_dias, preco, vagas, ativo)
SELECT * FROM (
  SELECT 'Gramado - RS', 'Pacote com hospedagem, café da manhã e city tour.', 5, 2499.90, 20, 1
  UNION ALL
  SELECT 'Maceió - AL', 'Praias paradisíacas com traslado aeroporto-hotel.', 7, 3190.00, 15, 1
  UNION ALL
  SELECT 'Buenos Aires - AR', 'Pacote internacional com seguro viagem incluso.', 6, 3899.00, 10, 1
) AS novos
WHERE NOT EXISTS (SELECT 1 FROM pacotes LIMIT 1);
