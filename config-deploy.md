# ⚡ Configuração Rápida - Deploy Hostinger

## 🚀 Deploy Manual (Mais Simples)

### 1. Executar Deploy Local
```bash
# Na pasta do projeto
./deploy.sh
```

### 2. Upload para Hostinger
- Acesse **cPanel > File Manager**
- Vá para pasta `public_html/`
- Faça upload dos arquivos da pasta `public_html/` (criada pelo script)

---

## 🔄 Deploy Automático (GitHub Actions)

### 1. Configurar Secrets no GitHub
No seu repositório GitHub:
- **Settings > Secrets and variables > Actions**
- Adicione:
  - `FTP_SERVER`: `seu-dominio.com`
  - `FTP_USERNAME`: `seu-usuario-ftp`
  - `FTP_PASSWORD`: `sua-senha-ftp`

### 2. Push para Deploy
```bash
git add .
git commit -m "Nova atualização"
git push origin main
# Deploy automático acontece!
```

---

## 📋 Checklist de Deploy

### ✅ Pré-Deploy
- [ ] Site testado localmente
- [ ] Banco de dados configurado na Hostinger
- [ ] Credenciais do banco atualizadas em `config/database.php`
- [ ] Domínio configurado no cPanel

### ✅ Pós-Deploy
- [ ] Site carrega corretamente
- [ ] Formulário de contato funciona
- [ ] Widget de ajuda aparece
- [ ] SSL ativo
- [ ] Painel admin acessível

---

## 🆘 Problemas Comuns

### Site não carrega
- Verificar se arquivos estão em `public_html/`
- Confirmar permissões (644 para arquivos)

### Formulário não funciona
- Verificar conexão com banco
- Confirmar se `contact.php` foi enviado

### Erro 500
- Verificar logs de erro no cPanel
- Confirmar versão do PHP (8.1+)

---

**📞 Precisa de ajuda? Consulte `hostinger-deploy.md` para guia completo!** 