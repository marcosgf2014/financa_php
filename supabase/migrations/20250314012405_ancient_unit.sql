/*
  # Criação do banco de dados e tabelas do sistema financeiro

  1. Estrutura
    - Banco de dados: app_db
    - Tabelas:
      - usuarios (autenticação)
      - transacoes (registro financeiro)
*/

CREATE DATABASE IF NOT EXISTS app_db;
USE app_db;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS transacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('entrada', 'saida') NOT NULL,
    tipo_servico VARCHAR(50) NOT NULL,
    data_transacao DATE NOT NULL,
    descricao TEXT,
    valor DECIMAL(10,2) NOT NULL,
    forma_pagamento VARCHAR(50) NOT NULL,
    nota_fiscal VARCHAR(50),
    usuario_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

INSERT INTO usuarios (usuario, senha) 
VALUES ('seu_usuario', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- A senha é 'password', você deve alterá-la após o primeiro login
