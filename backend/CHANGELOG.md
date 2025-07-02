# Changelog

Todos os principais lançamentos e alterações no backend.

## [0.1.0] - 2025-07-02
### Adicionado
- Estrutura inicial do projeto Laravel 12
- Dockerfile (PHP 8.4-fpm), Docker Compose (MariaDB 11.3, Redis)
- CI/CD pipeline (GitHub Actions)
- Autenticação com Laravel Sanctum
- Migrations e Models: User, Supplier, Category, Unit, Item, Price, Budget, BudgetItem
- Controllers e rotas RESTful para todos os recursos
- Policies de acesso (admin/fornecedor)
- Importação de preços via Excel com preview
- Busca avançada de preços com filtros e paginação
- Histórico de preços de itens
- Geração de orçamentos e link público (token curto)
- Exportação de orçamentos em PDF (DomPDF) e Excel (Laravel Excel)
- Seeders iniciais de usuários, categorias e unidades
- Template básico para PDF de orçamento

