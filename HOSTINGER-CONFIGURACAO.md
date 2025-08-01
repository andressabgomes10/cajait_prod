# 🔧 Configuração Correta - Hostinger

## ✅ Verificação da Estrutura

**Seus arquivos estão na RAIZ do repositório** ✅

```
cajait_prod/
├── 📄 index.html              # ✅ Na raiz
├── 🎨 style.css               # ✅ Na raiz
├── ⚡ script.js                # ✅ Na raiz
├── 📧 contact.php              # ✅ Na raiz
├── ⚙️ config/                  # ✅ Na raiz
├── 🗂️ admin/                   # ✅ Na raiz
└── 📚 README.md                # ✅ Na raiz
```

## 🎯 Configuração na Hostinger

### **Caminho de Instalação:**
Como seus arquivos estão na **raiz**, deixe o campo **"Caminho de instalação"** **VAZIO** ou coloque **`.`** (ponto)

### **Configuração Completa:**
```
Repository URL: https://github.com/andressabgomes10/cajait_prod.git
Branch: main
Directory: public_html
Caminho de instalação: [DEIXE VAZIO] ou .
Username: andressabgomes10
Password: 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
Auto Deploy: ✅ ON
```

## 🔍 Verificação Pós-Deploy

### **1. Verificar Arquivos no public_html:**
No cPanel → File Manager → `public_html/` você deve ver:
- ✅ `index.html`
- ✅ `style.css`
- ✅ `script.js`
- ✅ `contact.php`
- ✅ `config/`
- ✅ `admin/`
- ✅ `database_setup.sql`

### **2. Se public_html estiver vazio:**
Execute via SSH:
```bash
cd public_html
git pull origin main
```

### **3. Se ainda estiver vazio:**
Verifique se o **"Caminho de instalação"** está correto:
- ✅ **Vazio** ou **`.`** (se arquivos na raiz)
- ❌ **NÃO** coloque `frontend`, `site`, etc.

## 🚀 Teste do Deploy

### **1. Faça uma alteração:**
```bash
# Adicione um comentário no index.html
git add .
git commit -m "Teste deploy automático"
git push origin main
```

### **2. Verifique na Hostinger:**
- Acesse cPanel → Git Version Control
- Verifique se o deploy foi executado
- Confirme se os arquivos estão em `public_html/`

### **3. Teste o site:**
- Acesse: `https://cajait.com`
- Verifique se as alterações apareceram

## 🆘 Solução de Problemas

### **Problema: public_html vazio**
**Solução:**
1. Verifique o "Caminho de instalação" (deve estar vazio)
2. Execute via SSH: `cd public_html && git pull origin main`
3. Reconfigure o Git Version Control

### **Problema: Deploy não funciona**
**Solução:**
1. Verifique o webhook no GitHub
2. Confirme as credenciais (token)
3. Verifique os logs de deploy

### **Problema: Arquivos duplicados**
**Solução:**
1. Limpe o `public_html/`
2. Reconfigure o deploy
3. Execute deploy manual via SSH

## ✅ Checklist Final

- [ ] Caminho de instalação: **VAZIO** ou **`.`**
- [ ] Arquivos aparecem em `public_html/`
- [ ] Site carrega em `https://cajait.com`
- [ ] Formulário de contato funciona
- [ ] Deploy automático funcionando

---

**🎯 Configure o "Caminho de instalação" como VAZIO na Hostinger!** 