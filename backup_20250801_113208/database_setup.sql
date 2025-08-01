-- Script de Criação das Tabelas - Site Cajá
-- Execute este script no phpMyAdmin do Hostinger após criar o banco

-- 1. Tabela para armazenar mensagens de contato
CREATE TABLE IF NOT EXISTS `contatos` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nome` varchar(100) NOT NULL,
    `email` varchar(150) NOT NULL,
    `empresa` varchar(100) DEFAULT NULL,
    `servico` varchar(50) DEFAULT NULL,
    `mensagem` text NOT NULL,
    `ip_address` varchar(45) DEFAULT NULL,
    `user_agent` text DEFAULT NULL,
    `status` enum('novo','lido','respondido') DEFAULT 'novo',
    `data_criacao` timestamp DEFAULT CURRENT_TIMESTAMP,
    `data_atualizacao` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_email` (`email`),
    KEY `idx_status` (`status`),
    KEY `idx_data_criacao` (`data_criacao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabela para usuários administrativos
CREATE TABLE IF NOT EXISTS `usuarios_admin` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL UNIQUE,
    `password` varchar(255) NOT NULL,
    `nome_completo` varchar(100) NOT NULL,
    `email` varchar(150) NOT NULL,
    `ultimo_acesso` timestamp NULL DEFAULT NULL,
    `ativo` tinyint(1) DEFAULT 1,
    `data_criacao` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_username` (`username`),
    UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Inserir usuário administrativo padrão
-- Usuário: admin, Senha: cajait2025!
INSERT INTO `usuarios_admin` (`username`, `password`, `nome_completo`, `email`) 
VALUES ('admin', '$2y$10$dFVgrdQjG2tCJlFg7h6kd.RVoZFCwF3k/c216JVxwuB/TFaTFeB8q', 'Administrador Cajá', 'andressa@cajait.com')
ON DUPLICATE KEY UPDATE password = VALUES(password);

-- 4. Tabela para estatísticas e métricas
CREATE TABLE IF NOT EXISTS `estatisticas` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `data_evento` date NOT NULL,
    `tipo_evento` varchar(50) NOT NULL,
    `valor` int(11) DEFAULT 1,
    `detalhes` json DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_data_tipo` (`data_evento`, `tipo_evento`),
    KEY `idx_data_evento` (`data_evento`),
    KEY `idx_tipo_evento` (`tipo_evento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Tabela para configurações do sistema
CREATE TABLE IF NOT EXISTS `configuracoes` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `chave` varchar(100) NOT NULL UNIQUE,
    `valor` text NOT NULL,
    `descricao` varchar(255) DEFAULT NULL,
    `data_atualizacao` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_chave` (`chave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Inserir configurações padrão
INSERT INTO `configuracoes` (`chave`, `valor`, `descricao`) VALUES
('email_destinatario', 'andressa@cajait.com', 'Email principal para receber mensagens'),
('email_remetente', 'noreply@cajait.com', 'Email remetente do sistema'),
('site_titulo', 'Cajá - Tecnologia Artesanal', 'Título do site'),
('site_descricao', 'Transforme ideias em produtos digitais únicos', 'Descrição do site'),
('notificacoes_email', '1', 'Enviar notificações por email (1=sim, 0=não)')
ON DUPLICATE KEY UPDATE valor = VALUES(valor);

-- Verificar se as tabelas foram criadas
SHOW TABLES;