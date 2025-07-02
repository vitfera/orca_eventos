# Changelog - Backend API

Todas as alterações importantes na API do OrçaEventos serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Semantic Versioning](https://semver.org/lang/pt-BR/).

## [1.0.0] - 2025-07-02

### Adicionado
#### 🏗️ Infraestrutura
- Estrutura inicial do projeto Laravel 12
- Dockerfile otimizado (PHP 8.4-fpm, extensões necessárias)
- Docker Compose com MariaDB 11.3 e Redis
- CI/CD pipeline com GitHub Actions
- Configuração de ambiente padronizada

#### 🔐 Autenticação e Autorização
- Sistema de autenticação com Laravel Sanctum
- Middleware de autenticação para rotas protegidas
- Políticas de acesso (AuthServiceProvider configurado)
- Roles: admin e supplier (fornecedor)

#### 📊 Modelo de Dados
- **Users**: Sistema completo de usuários com roles
- **Categories**: Categorização de itens de eventos
- **Units**: Unidades de medida (kg, unidade, metro, etc.)
- **Suppliers**: Gestão de fornecedores
- **Items**: Catálogo de itens para eventos
- **Prices**: Sistema de precificação com histórico
- **Budgets**: Orçamentos com código único
- **BudgetItems**: Itens do orçamento com quantidades

#### 🎯 Funcionalidades Core
- **CRUD Completo**: Todos os recursos com validação
- **Busca Avançada**: Filtros por categoria, fornecedor, período, estado
- **Histórico de Preços**: Rastreamento de alterações de preços
- **Links Públicos**: Orçamentos acessíveis via token único

#### 📤 Importação/Exportação
- **Importação Excel**: Preview antes da confirmação
- **Exportação PDF**: Template profissional com dados do orçamento
- **Exportação Excel**: Planilha detalhada do orçamento
- Validação de dados na importação

#### 🔍 API Endpoints
- `POST /api/auth/login` - Autenticação
- `GET|POST /api/categories` - Gestão de categorias
- `GET|POST /api/units` - Gestão de unidades
- `GET|POST /api/suppliers` - Gestão de fornecedores
- `GET|POST /api/items` - Gestão de itens
- `GET|POST /api/prices` - Gestão de preços
- `GET /api/prices/search` - Busca avançada de preços
- `GET /api/prices/history/{item}` - Histórico de preços
- `POST /api/prices/import` - Importação de preços
- `GET|POST /api/budgets` - Gestão de orçamentos
- `GET /api/budgets/{id}/pdf` - Exportação PDF
- `GET /api/budgets/{id}/excel` - Exportação Excel
- `GET /api/public/budgets/{token}` - Acesso público
- `GET|POST /api/budget-items` - Gestão de itens do orçamento

#### 🛡️ Policies Implementadas
- BudgetPolicy: Controle de acesso a orçamentos
- BudgetItemPolicy: Controle de itens do orçamento
- CategoryPolicy, ItemPolicy, PricePolicy: Gestão de recursos
- SupplierPolicy, UnitPolicy: Controle de fornecedores e unidades

#### 🌱 Seeders
- UserSeeder: Usuário admin padrão
- CategorySeeder: Categorias básicas de eventos
- UnitSeeder: Unidades de medida padrão
- DatabaseSeeder: Orquestração dos seeders

#### 📋 Recursos Técnicos
- Validação de dados em todos os endpoints
- Tratamento de erros padronizado
- Paginação em listagens
- Filtros dinâmicos
- Relacionamentos otimizados com Eloquent
- Middleware de autorização
- Rate limiting configurado

### Tecnologias Utilizadas
- **Framework**: Laravel 12
- **PHP**: 8.4
- **Banco de Dados**: MariaDB 11.3
- **Cache**: Redis
- **Autenticação**: Laravel Sanctum
- **Importação**: Laravel Excel (PhpSpreadsheet)
- **PDF**: DomPDF
- **Contêinerização**: Docker & Docker Compose
- **CI/CD**: GitHub Actions

