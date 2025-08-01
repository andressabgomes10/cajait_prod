# ⚡ Setup Rápido - Hostinger

## 🎯 Passos para Conectar GitHub + Hostinger

### 1️⃣ Criar Token GitHub
1. Acesse: https://github.com/settings/tokens
2. Clique: **"Generate new token (classic)"**
3. Configure:
   - **Note:** `Hostinger Deploy`
   - **Expiration:** `No expiration`
   - **Scopes:** ✅ `repo`
4. Clique: **"Generate token"**
5. **Copie o token** (guarde bem!)

### 2️⃣ Configurar na Hostinger
1. **Login na Hostinger**
2. **Acesse cPanel**
3. **Procure "Git Version Control"**
4. **Clique "Create"**
5. **Configure:**
   ```
   Repository URL: https://github.com/andressabgomes10/cajait_prod.git
   Branch: main
   Directory: public_html
   Username: andressabgomes10
   Password: [COLE O TOKEN AQUI]
   Auto Deploy: ✅ ON
   ```

### 3️⃣ Deploy Inicial
1. **Clique "Create"**
2. **Aguarde deploy** (2-3 minutos)
3. **Verifique arquivos** em `public_html/`

### 4️⃣ Configurar Banco
1. **Acesse phpMyAdmin**
2. **Crie banco:**
   - Nome: `u921347543_sitecaja`
   - Usuário: `u921347543_andressa`
   - Senha: `-Aa200200229*`
3. **Importe:** `database_setup.sql`

### 5️⃣ Testar Site
- Acesse: `https://cajait.com`
- Teste formulário de contato
- Verifique widget de ajuda

## ✅ Checklist Final

- [ ] Token GitHub criado
- [ ] Git Version Control configurado
- [ ] Deploy inicial realizado
- [ ] Arquivos em `public_html/`
- [ ] Banco de dados criado
- [ ] Site funcionando
- [ ] SSL ativo

## 🚀 Deploy Automático

Após configuração, para atualizar:
```bash
git add .
git commit -m "Nova atualização"
git push origin main
# Deploy automático na Hostinger!
```

---

**🎯 Execute os passos acima e seu site estará online!** 