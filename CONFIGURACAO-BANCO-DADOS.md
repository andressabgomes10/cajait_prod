# 🗄️ Guia de Configuração do Banco de Dados - Hostinger

## 📋 Informações do Banco de Dados

### Credenciais de Conexão:
- **Host:** `localhost`
- **Nome do Banco:** `u921347543_sitecaja`
- **Usuário:** `u921347543_andressa`
- **Senha:** `-Aa200200229*`
- **Porta:** `3306` (padrão MySQL)

---

## 🔧 Passo a Passo - Configuração na Hostinger

### 1. Acessar o phpMyAdmin

1. **Faça login no cPanel** da Hostinger
2. **Procure por "phpMyAdmin"** na seção de bancos de dados
3. **Clique em "phpMyAdmin"** para abrir o painel

### 2. Criar o Banco de Dados

1. **No phpMyAdmin, clique em "Novo"** no menu lateral esquerdo
2. **Digite o nome do banco:** `u921347543_sitecaja`
3. **Selecione a collation:** `utf8mb4_unicode_ci`
4. **Clique em "Criar"**

### 3. Criar o Usuário do Banco (se necessário)

1. **Vá em "Usuários"** no menu superior
2. **Clique em "Adicionar usuário"**
3. **Preencha os dados:**
   - **Nome de usuário:** `u921347543_andressa`
   - **Host:** `localhost`
   - **Senha:** `-Aa200200229*`
4. **Clique em "Executar"**

### 4. Conceder Privilégios ao Usuário

1. **Selecione o usuário** `u921347543_andressa`
2. **Clique em "Editar privilégios"**
3. **Selecione o banco:** `u921347543_sitecaja`
4. **Marque "Selecionar tudo"** para dar todos os privilégios
5. **Clique em "Executar"**

### 5. Importar a Estrutura do Banco

1. **Selecione o banco** `u921347543_sitecaja`
2. **Clique na aba "SQL"**
3. **Copie e cole o conteúdo** do arquivo `database_setup.sql`
4. **Clique em "Executar"**

---

## 📄 Conteúdo do Script SQL

```sql
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
```

---

## ✅ Verificações Pós-Configuração

### 1. Verificar Tabelas Criadas
Após executar o script, você deve ver estas tabelas:
- ✅ `contatos`
- ✅ `usuarios_admin`
- ✅ `estatisticas`
- ✅ `configuracoes`

### 2. Verificar Usuário Admin
- **Login:** `admin`
- **Senha:** `cajait2025!`
- **Email:** `andressa@cajait.com`

### 3. Testar Conexão
Execute este comando SQL para testar:
```sql
SELECT COUNT(*) as total_contatos FROM contatos;
SELECT COUNT(*) as total_usuarios FROM usuarios_admin;
```

---

## 🔐 Credenciais de Acesso

### Painel Administrativo:
- **URL:** `https://seu-dominio.com/admin/`
- **Usuário:** `admin`
- **Senha:** `cajait2025!`

### Banco de Dados:
- **Host:** `localhost`
- **Banco:** `u921347543_sitecaja`
- **Usuário:** `u921347543_andressa`
- **Senha:** `-Aa200200229*`

---

## 🛠️ Solução de Problemas

### Erro de Conexão:
1. **Verificar credenciais** em `config/database.php`
2. **Confirmar se o banco existe**
3. **Verificar privilégios do usuário**
4. **Testar conexão via phpMyAdmin**

### Tabelas não Criadas:
1. **Verificar se o script SQL foi executado**
2. **Confirmar permissões do usuário**
3. **Verificar logs de erro no phpMyAdmin**

### Usuário Admin não Funciona:
1. **Verificar se a tabela `usuarios_admin` foi criada**
2. **Confirmar se o usuário foi inserido**
3. **Verificar se a senha está correta**

---

## 📞 Suporte

Se encontrar problemas:
1. **Verificar logs de erro** no cPanel
2. **Testar conexão** via phpMyAdmin
3. **Confirmar configurações** no arquivo `database.php`
4. **Contatar suporte** da Hostinger se necessário

---

**🎉 Banco de dados configurado com sucesso! O site está pronto para funcionar.** 