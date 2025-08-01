# 🌐 Integração GitHub + Hostinger

## 🎯 Objetivo
Conectar seu repositório GitHub com a Hostinger para deploy automático.

## 🔧 Configuração na Hostinger

### 1. Acessar cPanel da Hostinger
- Faça login na sua conta Hostinger
- Acesse o **cPanel** do seu domínio
- Procure por **"Git Version Control"**

### 2. Configurar Repositório Git
1. **Clique em "Create"**
2. **Configure:**
   - **Repository URL:** `https://github.com/andressabgomes10/cajait_prod.git`
   - **Branch:** `main`
   - **Directory:** `public_html`
   - **Auto Deploy:** ✅ Ativado

### 3. Configurar Credenciais
- **Username:** `andressabgomes10`
- **Password:** Use um **Personal Access Token** do GitHub

## 🔑 Criar Token do GitHub

### 1. Acessar GitHub Settings
- Vá para: https://github.com/settings/tokens
- Clique em **"Generate new token (classic)"**

### 2. Configurar Token
- **Note:** `Hostinger Deploy`
- **Expiration:** `No expiration`
- **Scopes:** ✅ `repo` (Full control of private repositories)

### 3. Copiar Token
- Clique em **"Generate token"**
- **Copie o token** (você só verá uma vez!)

## 🚀 Configuração Final na Hostinger

### 1. Voltar ao cPanel
- **Repository URL:** `https://github.com/andressabgomes10/cajait_prod.git`
- **Username:** `andressabgomes10`
- **Password:** `SEU_TOKEN_AQUI`

### 2. Deploy Inicial
- Clique em **"Create"**
- Aguarde o deploy automático
- Verifique se os arquivos aparecem em `public_html`

## 📋 Verificações Pós-Deploy

### 1. Verificar Arquivos
No cPanel → File Manager → `public_html/`:
- ✅ `index.html`
- ✅ `style.css`
- ✅ `script.js`
- ✅ `contact.php`
- ✅ `config/`
- ✅ `admin/`

### 2. Testar Site
- Acesse: `https://cajait.com` (ou seu domínio)
- Verifique se o site carrega
- Teste o formulário de contato

### 3. Configurar Banco de Dados
1. **Acesse phpMyAdmin**
2. **Crie o banco:**
   - Nome: `u921347543_sitecaja`
   - Usuário: `u921347543_andressa`
   - Senha: `-Aa200200229*`

3. **Importar estrutura:**
   - Execute `database_setup.sql`

## 🔄 Deploy Automático

### Após Configuração:
1. **Fazer alterações no código**
2. **Commit e push:**
   ```bash
   git add .
   git commit -m "Nova atualização"
   git push origin main
   ```
3. **Deploy automático** na Hostinger

## 🆘 Problemas Comuns

### Erro de Conexão Git
- Verificar URL do repositório
- Confirmar token do GitHub
- Verificar permissões do repositório

### Arquivos não Aparecem
- Verificar diretório `public_html`
- Confirmar branch `main`
- Verificar logs de deploy

### Site não Carrega
- Verificar permissões dos arquivos (644)
- Confirmar domínio configurado
- Verificar SSL ativo

## 📞 Suporte

- **Hostinger:** Suporte oficial da Hostinger
- **GitHub:** Verificar logs de deploy
- **Documentação:** Consultar `hostinger-deploy.md`

---

**🎯 Próximo passo:** Configure o Git Version Control no cPanel da Hostinger! 