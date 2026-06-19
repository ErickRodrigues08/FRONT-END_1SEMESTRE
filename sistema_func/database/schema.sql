CREATE DATABASE IF NOT EXISTS sistema_func CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_func;

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS funcionarios (
    id VARCHAR(20) PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    cep VARCHAR(10) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    idade INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS registro_ponto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    funcionario_id VARCHAR(20) NOT NULL,
    data DATE NOT NULL,
    hora_entrada TIME NOT NULL,
    hora_saida TIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_ponto_func_data (funcionario_id, data),
    FOREIGN KEY (funcionario_id) REFERENCES funcionarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS faltas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    funcionario_id VARCHAR(20) NOT NULL,
    data DATE NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    anexo VARCHAR(255) DEFAULT NULL,
    status ENUM('pendente', 'aprovada', 'rejeitada') DEFAULT 'pendente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (funcionario_id) REFERENCES funcionarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS folha_pagamento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    funcionario_id VARCHAR(20) NOT NULL,
    mes INT NOT NULL,
    ano INT NOT NULL,
    salario DECIMAL(10,2) NOT NULL DEFAULT 0,
    recebido TINYINT(1) NOT NULL DEFAULT 0,
    descontos_manuais DECIMAL(10,2) NOT NULL DEFAULT 0,
    vale_transporte DECIMAL(10,2) NOT NULL DEFAULT 0,
    vale_alimentacao DECIMAL(10,2) NOT NULL DEFAULT 0,
    desconto_faltas DECIMAL(10,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_folha_func_mes (funcionario_id, mes, ano),
    FOREIGN KEY (funcionario_id) REFERENCES funcionarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    funcionario_id VARCHAR(20) NOT NULL,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    data_hora_solicitado DATETIME NOT NULL,
    hora_execucao TIME DEFAULT NULL,
    em_andamento TINYINT(1) NOT NULL DEFAULT 0,
    data_termino DATE DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (funcionario_id) REFERENCES funcionarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS mensagens_chat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    funcionario_id VARCHAR(20) NOT NULL,
    remetente ENUM('funcionario', 'admin') NOT NULL,
    mensagem TEXT NOT NULL,
    lida TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (funcionario_id) REFERENCES funcionarios(id) ON DELETE CASCADE
);

-- Execute database/install.php para criar o admin com senha admin123
