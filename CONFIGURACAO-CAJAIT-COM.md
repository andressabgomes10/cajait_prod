# 🌐 Configuração Específica - cajait.com

## 📋 Informações do Site

### 🌐 Domínio:
- **URL Principal:** `https://cajait.com`
- **Painel Admin:** `https://cajait.com/admin/`
- **Teste DB:** `https://cajait.com/test-database.php`

### 🔐 Credenciais de Acesso:

**Painel Administrativo:**
- **URL:** `https://cajait.com/admin/`
- **Usuário:** `admin`
- **Senha:** `cajait2025!`

**Banco de Dados:**
- **Host:** `localhost`
- **Banco:** `u921347543_sitecaja`
- **Usuário:** `u921347543_andressa`
- **Senha:** `-Aa200200229*`

---

## 🚀 Checklist de Deploy

### ✅ Banco de Dados (JÁ FEITO):
- [x] Banco `u921347543_sitecaja` criado
- [ ] Script SQL executado
- [ ] Tabelas criadas
- [ ] Usuário admin inserido

### ⏳ Upload de Arquivos:
- [ ] Fazer upload da pasta `public_html/` para o servidor
- [ ] Verificar permissões dos arquivos
- [ ] Testar acesso ao site

### ⏳ Configurações Finais:
- [ ] Ativar SSL (HTTPS)
- [ ] Testar formulário de contato
- [ ] Verificar widget de ajuda
- [ ] Testar painel administrativo

---

## 📁 Estrutura de Arquivos para Upload

```
public_html/
├── index.html              # Página principal
├── style.css               # CSS minificado
├── script.js               # JS minificado
├── contact.php             # Formulário de contato
├── test-database.php       # Teste de conexão
├── database_setup.sql      # Script do banco
├── config/
│   └── database.php        # Configurações do banco
└── admin/                  # Painel administrativo
    ├── index.php
    ├── login.php
    ├── logout.php
    └── view_contact.php
```

---

## 🔧 Comandos SQL para Verificação

### Verificar Tabelas:
```sql
SHOW TABLES;
```

### Verificar Usuário Admin:
```sql
SELECT username, nome_completo, email FROM usuarios_admin WHERE username = 'admin';
```

### Verificar Configurações:
```sql
SELECT chave, valor FROM configuracoes;
```

### Contar Contatos:
```sql
SELECT COUNT(*) as total FROM contatos;
```

---

## 🛠️ Solução de Problemas

### Se o site não carregar:
1. Verificar se arquivos foram enviados para `public_html/`
2. Confirmar permissões (644 para arquivos, 755 para pastas)
3. Verificar logs de erro no cPanel

### Se o banco não conectar:
1. Acessar `https://cajait.com/test-database.php`
2. Verificar credenciais em `config/database.php`
3. Confirmar se script SQL foi executado

### Se o painel admin não funcionar:
1. Verificar se tabela `usuarios_admin` existe
2. Confirmar usuário `admin` foi inserido
3. Testar login com credenciais corretas

---

## 📞 Contatos e Suporte

### Informações do Site:
- **Email:** andressa@cajait.com
- **Site:** https://cajait.com
- **Título:** Cajá - Tecnologia Artesanal
- **Descrição:** Transforme ideias em produtos digitais únicos

### Configurações de Email:
- **Destinatário:** andressa@cajait.com
- **Remetente:** noreply@cajait.com
- **Notificações:** Ativadas

---

## 🎯 Próximos Passos

1. **Executar script SQL** no phpMyAdmin
2. **Fazer upload dos arquivos** para o servidor
3. **Testar site** em https://cajait.com
4. **Verificar painel admin** em https://cajait.com/admin/
5. **Testar formulário de contato**
6. **Configurar SSL** (se necessário)

---

**🎉 Site pronto para funcionar em https://cajait.com!** 