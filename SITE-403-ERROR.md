# 🚨 Erro 403 - Site Não Acessível

## ❌ Problema Detectado

O site `https://cajait.com` está retornando **erro 403 (Forbidden)**.

## 🔍 Possíveis Causas

### **1. Arquivos não foram enviados corretamente**
- Verificar se os arquivos estão em `public_html/`
- Confirmar se o `index.html` está na raiz

### **2. Permissões incorretas**
- Arquivos devem ter permissão 644
- Diretórios devem ter permissão 755

### **3. Configuração de domínio**
- Domínio pode não estar apontando para `public_html`
- SSL pode não estar configurado

### **4. Arquivo .htaccess**
- Pode estar bloqueando o acesso
- Configuração incorreta

## 🔧 Soluções

### **Solução 1: Verificar Arquivos**
1. **Acesse cPanel → File Manager**
2. **Vá para `public_html/`**
3. **Verifique se existem:**
   - ✅ `index.html`
   - ✅ `style.css`
   - ✅ `script.js`
   - ✅ `contact.php`

### **Solução 2: Corrigir Permissões**
Via SSH ou File Manager:
```bash
chmod 644 public_html/*.html
chmod 644 public_html/*.css
chmod 644 public_html/*.js
chmod 644 public_html/*.php
chmod 755 public_html/config/
chmod 755 public_html/admin/
```

### **Solução 3: Verificar Domínio**
1. **Acesse cPanel → Domains**
2. **Verifique se `cajait.com` aponta para `public_html`**
3. **Configure redirecionamento www → non-www**

### **Solução 4: Verificar .htaccess**
1. **Verifique se existe arquivo `.htaccess`**
2. **Se existir, renomeie temporariamente**
3. **Teste o site novamente**

### **Solução 5: Deploy Manual**
Execute via SSH:
```bash
cd public_html
rm -rf *
git clone https://github.com/andressabgomes10/cajait_prod.git .
chmod 644 *.html *.css *.js *.php
chmod 755 config/ admin/
```

## 🚀 Verificação Rápida

### **1. Acesse cPanel → File Manager**
- Vá para `public_html/`
- Verifique se `index.html` existe

### **2. Teste via SSH**
```bash
cd public_html
ls -la
cat index.html | head -5
```

### **3. Verificar Logs**
- Acesse cPanel → Error Logs
- Verifique se há erros específicos

## 📞 Próximos Passos

### **Se arquivos não existem:**
1. Reconfigure o Git Version Control
2. Execute deploy manual via SSH
3. Verifique configurações

### **Se arquivos existem:**
1. Corrija permissões
2. Verifique configuração de domínio
3. Teste sem .htaccess

### **Se ainda não funcionar:**
1. Contate suporte da Hostinger
2. Verifique logs de erro
3. Configure domínio manualmente

---

**🎯 Primeiro passo: Verifique se os arquivos estão em `public_html/` no cPanel!** 