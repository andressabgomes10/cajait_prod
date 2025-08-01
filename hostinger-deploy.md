# 🚀 Guia de Deploy Automático - Hostinger

## Configuração Completa para Deploy Automático do Site Cajá

### 📋 Pré-requisitos

1. **Conta na Hostinger** com plano de hospedagem
2. **Git** instalado no seu computador
3. **Acesso SSH** à sua conta Hostinger (recomendado)
4. **GitHub/GitLab** para repositório (opcional)

---

## 🔧 Passo a Passo

### 1. Preparar o Repositório Git

```bash
# Inicializar Git no projeto (se ainda não feito)
git init

# Adicionar todos os arquivos
git add .

# Fazer commit inicial
git commit -m "Versão inicial do site Cajá"

# Criar repositório no GitHub/GitLab (opcional)
# Depois conectar com:
git remote add origin https://github.com/seu-usuario/caja-website.git
git push -u origin main
```

### 2. Configurar Acesso SSH na Hostinger

#### Via cPanel:
1. Acesse o **cPanel** da sua conta Hostinger
2. Vá em **SSH Access**
3. Gere uma nova chave SSH
4. Baixe a chave privada

#### Via Terminal (recomendado):
```bash
# Gerar chave SSH
ssh-keygen -t rsa -b 4096 -C "seu-email@exemplo.com"

# Copiar chave pública
cat ~/.ssh/id_rsa.pub
```

### 3. Configurar Deploy Automático

#### Opção A: Deploy Manual (Mais Simples)

```bash
# Executar script de deploy
chmod +x deploy.sh
./deploy.sh

# Fazer upload via FTP/cPanel
# Os arquivos estarão na pasta public_html/
```

#### Opção B: Deploy Automático via Git (Avançado)

1. **Configurar Webhook na Hostinger:**
   - Acesse **cPanel > Git Version Control**
   - Clique em **Create**
   - Configure:
     - Repository: `https://github.com/seu-usuario/caja-website.git`
     - Branch: `main`
     - Directory: `public_html`

2. **Configurar GitHub Actions (opcional):**
   - Crie arquivo `.github/workflows/deploy.yml`
   - Configure deploy automático

### 4. Configurar Banco de Dados

1. **Acesse phpMyAdmin** na Hostinger
2. **Crie o banco de dados:**
   - Nome: `u921347543_sitecaja` (ou similar)
   - Usuário: `u921347543_andressa` (ou similar)
   - Senha: `-Aa200200229*` (ou sua senha)

3. **Importar estrutura:**
   - Execute o arquivo `database_setup.sql`
   - Ou copie e cole o conteúdo no phpMyAdmin

### 5. Configurar Domínio

1. **Acesse cPanel > Domains**
2. **Configure o domínio principal:**
   - `cajait.com` (ou seu domínio)
   - Aponte para `public_html`

3. **Configurar SSL:**
   - Vá em **SSL/TLS**
   - Ative SSL gratuito da Hostinger

---

## 🔄 Processo de Deploy

### Deploy Manual (Recomendado para início):

```bash
# 1. Fazer alterações no código
# 2. Testar localmente
php -S localhost:8000

# 3. Executar deploy
./deploy.sh

# 4. Fazer upload via FTP/cPanel
# 5. Testar o site online
```

### Deploy Automático (Após configuração):

```bash
# 1. Fazer alterações
# 2. Commit e push
git add .
git commit -m "Nova funcionalidade"
git push origin main

# 3. Deploy automático acontece
# 4. Site atualizado automaticamente
```

---

## 📁 Estrutura de Arquivos

```
projeto/
├── index.html              # Página principal
├── style.css               # Estilos
├── script.js               # JavaScript
├── contact.php             # Formulário produção
├── config/
│   └── database.php        # Config banco produção
├── admin/                  # Painel administrativo
├── deploy.sh               # Script de deploy
├── .gitignore              # Arquivos ignorados
└── README.md               # Documentação
```

---

## 🔍 Verificações Pós-Deploy

### 1. Testar Funcionalidades:
- [ ] Site carrega corretamente
- [ ] Formulário de contato funciona
- [ ] Widget de ajuda aparece
- [ ] Links funcionam
- [ ] Design responsivo

### 2. Verificar Banco de Dados:
- [ ] Conexão com banco funciona
- [ ] Contatos são salvos
- [ ] Painel admin acessível

### 3. Verificar Performance:
- [ ] Site carrega rápido
- [ ] Imagens otimizadas
- [ ] SSL funcionando

---

## 🛠️ Comandos Úteis

```bash
# Verificar status do Git
git status

# Ver logs de deploy
tail -f deploy.log

# Backup manual
cp -r public_html backup_manual

# Limpar cache (se necessário)
rm -rf cache/
```

---

## 🆘 Solução de Problemas

### Erro de Conexão com Banco:
- Verificar credenciais em `config/database.php`
- Confirmar se banco foi criado
- Verificar permissões do usuário

### Site não Carrega:
- Verificar se arquivos estão em `public_html/`
- Confirmar permissões (644 para arquivos, 755 para pastas)
- Verificar logs de erro

### Formulário não Funciona:
- Verificar se `contact.php` está presente
- Confirmar configurações de email
- Verificar logs de erro do PHP

---

## 📞 Suporte

Para dúvidas sobre:
- **Hostinger:** Suporte oficial da Hostinger
- **Deploy:** Verificar logs e documentação
- **Desenvolvimento:** Consultar README.md

---

**🎉 Parabéns! Seu site está pronto para produção!** 