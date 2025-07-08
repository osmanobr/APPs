-- Script para criação do Schema Inicial do Banco de Dados
-- Tecnologia: MySQL

-- Habilitar a extensão para UUIDs se não estiver habilitada (geralmente pgcrypto no PostgreSQL, ou usar UUID() no MySQL)
-- No MySQL, UUIDs podem ser gerados pela aplicação ou usando a função UUID().

CREATE TABLE IF NOT EXISTS `usuarios` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `nome` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `senha_hash` VARCHAR(255) NOT NULL,
    `nivel_acesso` ENUM('admin', 'vendedor', 'funcionario', 'valet') NOT NULL,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `data_modificacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `eventos` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `nome` VARCHAR(255) NOT NULL,
    `data_inicio` DATETIME NOT NULL,
    `data_fim` DATETIME NOT NULL,
    `organizador_id` CHAR(36),
    `descricao` TEXT,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `data_modificacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`organizador_id`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `hoteis` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `nome` VARCHAR(255) NOT NULL,
    `endereco` TEXT,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `data_modificacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `apartamentos` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `hotel_id` CHAR(36) NOT NULL,
    `numero_piso` VARCHAR(50),
    `numero_apartamento` VARCHAR(50) NOT NULL,
    `tipo_acomodacao` ENUM('solteiro', 'duplo', 'casal', 'triplo') NOT NULL,
    `valor_diaria` DECIMAL(10, 2) NOT NULL,
    `vendedor_id` CHAR(36),
    `responsavel_id` CHAR(36), -- Pode ser um inquilino ou outro usuário. Inicialmente, vamos referenciar usuarios.
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `data_modificacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`hotel_id`) REFERENCES `hoteis`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`vendedor_id`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`responsavel_id`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL -- Ajustar se responsável for sempre um inquilino
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `inquilinos` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `nome` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) UNIQUE, -- Email pode ser opcional ou obrigatório dependendo da regra de negócio
    `telefone` VARCHAR(20),
    `documento` VARCHAR(50), -- CPF/CNPJ ou outro
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `data_modificacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `reservas` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `evento_id` CHAR(36) NOT NULL,
    `apartamento_id` CHAR(36) NOT NULL,
    `inquilino_id` CHAR(36) NOT NULL, -- O inquilino principal da reserva
    `data_checkin` DATETIME NOT NULL,
    `data_checkout` DATETIME NOT NULL,
    `valor_total` DECIMAL(10, 2) NOT NULL,
    `status_pagamento` ENUM('pendente', 'pago', 'parcial', 'cancelado') DEFAULT 'pendente',
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `data_modificacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`evento_id`) REFERENCES `eventos`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`apartamento_id`) REFERENCES `apartamentos`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`inquilino_id`) REFERENCES `inquilinos`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de logs para auditoria e rastreamento de erros
CREATE TABLE IF NOT EXISTS `logs` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `usuario_id` CHAR(36), -- Quem realizou a ação (pode ser NULL para ações do sistema)
    `nivel` ENUM('info', 'erro', 'aviso', 'debug') NOT NULL,
    `acao` VARCHAR(255), -- Descrição da ação ou módulo
    `detalhes` TEXT, -- Mensagem de log, erro, etc.
    `ip_origem` VARCHAR(45),
    `data_ocorrencia` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Adicionando uma nota sobre a geração de UUIDs no MySQL:
-- UUIDs podem ser gerados na aplicação (PHP) antes de inserir no banco.
-- Exemplo de como gerar UUID no PHP:
-- $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
--     mt_rand(0, 0xffff), mt_rand(0, 0xffff),
--     mt_rand(0, 0xffff),
--     mt_rand(0, 0x0fff) | 0x4000,
--     mt_rand(0, 0x3fff) | 0x8000,
--     mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
-- );
-- Ou, se estiver usando MySQL 5.7+ ou MariaDB, pode-se usar a função UUID() como default ou via trigger,
-- mas para CHAR(36) é mais comum a aplicação gerar e inserir.
-- Para MySQL 8.0+, pode-se usar UUID_TO_BIN() e BIN_TO_UUID() com colunas BINARY(16) para melhor performance,
-- mas CHAR(36) é mais simples para visualização direta. Manterei CHAR(36) por simplicidade neste script.

-- Considerar adicionar índices para colunas frequentemente usadas em WHERE clauses ou JOINs
-- Exemplo:
-- CREATE INDEX idx_evento_id ON reservas(evento_id);
-- CREATE INDEX idx_apartamento_id ON reservas(apartamento_id);
-- CREATE INDEX idx_inquilino_id ON reservas(inquilino_id);
-- CREATE INDEX idx_hotel_id ON apartamentos(hotel_id);
-- CREATE INDEX idx_email ON usuarios(email);

-- Fim do script.
-- Lembre-se de executar este script no seu servidor MySQL.
