# Changelog - OrçaEventos

Todas as alterações importantes do sistema OrçaEventos serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Semantic Versioning](https://semver.org/lang/pt-BR/).

## [1.0.0] - 2025-07-02

### 🎉 Lançamento Inicial - Sistema OrçaEventos

Sistema SaaS completo para gestão de orçamentos de eventos, desenvolvido com arquitetura moderna e separação clara entre backend e frontend.

---

## 🔧 Backend (API) - v1.0.0

### ✅ Implementado
#### 🏗️ Infraestrutura e DevOps
- **Contêinerização**: Docker + Docker Compose
- **CI/CD**: Pipeline automatizado com GitHub Actions
- **Ambiente**: PHP 8.4, MariaDB 11.3, Redis
- **Framework**: Laravel 12 com melhores práticas

#### 🔐 Segurança e Autenticação
- **Autenticação**: Laravel Sanctum (API tokens)
- **Autorização**: Sistema de roles (admin/supplier)
- **Policies**: Controle granular de acesso
- **Validação**: Validação completa em todos os endpoints

#### 💾 Banco de Dados
- **Migrations**: Estrutura padronizada em inglês
- **Models**: 8 entidades principais com relacionamentos
- **Seeders**: Dados iniciais para desenvolvimento
- **Histórico**: Rastreamento de alterações de preços

#### 🎯 Funcionalidades Principais
- **CRUD Completo**: Todos os recursos implementados
- **Busca Avançada**: Filtros múltiplos e paginação
- **Importação/Exportação**: Excel e PDF profissionais
- **Links Públicos**: Compartilhamento seguro de orçamentos
- **Histórico**: Auditoria de alterações de preços

#### 📊 Recursos do Sistema
- **Categorias**: Organização de itens por tipo
- **Fornecedores**: Gestão completa de parceiros
- **Itens**: Catálogo extenso para eventos
- **Preços**: Sistema dinâmico com histórico
- **Orçamentos**: Criação e gestão profissional
- **Unidades**: Sistema flexível de medidas

---

## 💻 Frontend (Interface) - v1.0.0

### ✅ Base Implementada
- **Framework**: Vue.js 3 + Quasar Framework
- **Arquitetura**: SPA (Single Page Application)
- **Configuração**: Estrutura base preparada
- **Design System**: Quasar components

### 🔄 Em Desenvolvimento
- Telas de autenticação
- Dashboard administrativo
- Interface de gestão de recursos
- Telas de orçamentos
- Interface pública para clientes

---

## 🗂️ Estrutura do Projeto

```
OrçaEventos/
├── 📁 backend/           # API Laravel 12
│   ├── 🐳 Dockerfile
│   ├── 📦 composer.json
│   ├── 🗄️ database/
│   ├── 🎮 app/
│   └── 🛣️ routes/
├── 📁 frontend/          # Interface Vue.js + Quasar
│   ├── 📦 package.json
│   └── ⚙️ quasar.config.js
├── 🐳 docker-compose.yml
├── 🔄 .github/workflows/
└── 📋 CHANGELOG.md
```

---

## 🚀 Como Iniciar

### Backend
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

### Frontend
```bash
cd frontend
npm install
quasar dev
```

### Docker (Recomendado)
```bash
docker-compose up -d
```

---

## 📈 Roadmap

### v1.1.0 - Frontend Completo
- [ ] Telas de autenticação
- [ ] Dashboard principal
- [ ] CRUD interfaces
- [ ] Integração com API

### v1.2.0 - Funcionalidades Avançadas
- [ ] Notificações em tempo real
- [ ] Relatórios avançados
- [ ] Multi-tenancy (SaaS)
- [ ] Integração com pagamentos

### v2.0.0 - Expansão
- [ ] Mobile app
- [ ] Integrações externas
- [ ] IA para sugestões de preços
- [ ] Analytics avançados

---

## 🛠️ Tecnologias

### Backend
- **Framework**: Laravel 12
- **PHP**: 8.4
- **Database**: MariaDB 11.3
- **Cache**: Redis
- **Auth**: Laravel Sanctum
- **Docs**: OpenAPI/Swagger

### Frontend
- **Framework**: Vue.js 3
- **UI Library**: Quasar Framework
- **Build Tool**: Vite
- **HTTP Client**: Axios
- **State Management**: Pinia

### DevOps
- **Containers**: Docker + Docker Compose
- **CI/CD**: GitHub Actions
- **Deployment**: Docker Swarm ready
- **Monitoring**: Laravel Telescope

---

## 👥 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

---

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

---

## 📞 Suporte

Para questões e suporte:
- 📧 Email: suporte@orcaeventos.com
- 📝 Issues: GitHub Issues
- 📖 Docs: Wiki do projeto

---

*Desenvolvido com ❤️ para facilitar a gestão de eventos*
