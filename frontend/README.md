# OrçaEventos - Frontend

Interface web moderna para o sistema de gestão de orçamentos de eventos, desenvolvida com Vue.js 3 e Quasar Framework.

## 🚀 Tecnologias

- **Vue.js 3** - Framework progressivo para construção de interfaces
- **Quasar Framework** - Framework Vue.js com componentes prontos
- **Pinia** - Gerenciamento de estado moderno para Vue
- **Vue Router** - Roteamento oficial do Vue.js
- **Axios** - Cliente HTTP para comunicação com a API
- **Vite** - Build tool rápido e moderno

## 📋 Pré-requisitos

- Node.js 16+ ou 18+ ou 20+
- npm 6.13.4+ ou yarn 1.21.1+

## 🔧 Instalação

### 1. Instalar dependências
```bash
npm install
# ou
yarn install
```

### 2. Configurar ambiente
```bash
# Copiar arquivo de configuração
cp .env.example .env

# Editar variáveis de ambiente
# API_URL=http://localhost:8000/api
```

### 3. Executar em desenvolvimento
```bash
npm run dev
# ou
yarn dev
```

### 4. Build para produção
```bash
npm run build
# ou
yarn build
```

## 📁 Estrutura do Projeto

```
src/
├── boot/           # Plugins de inicialização
│   └── axios.js    # Configuração do Axios
├── components/     # Componentes reutilizáveis
├── css/           # Estilos globais
│   └── app.scss   # Estilos principais
├── layouts/       # Layouts da aplicação
│   ├── AuthLayout.vue    # Layout para autenticação
│   ├── MainLayout.vue    # Layout principal
│   └── PublicLayout.vue  # Layout público
├── pages/         # Páginas da aplicação
│   ├── auth/      # Páginas de autenticação
│   ├── public/    # Páginas públicas
│   ├── Dashboard.vue     # Dashboard principal
│   ├── Categories.vue    # Gestão de categorias
│   ├── Budgets.vue      # Gestão de orçamentos
│   └── ...
├── router/        # Configuração de rotas
│   └── index.js   # Definição das rotas
├── stores/        # Gerenciamento de estado (Pinia)
│   └── auth.js    # Store de autenticação
├── App.vue        # Componente raiz
└── main.js        # Arquivo principal
```

## 🎯 Funcionalidades Implementadas

### ✅ Autenticação
- Login com email e senha
- Gerenciamento de sessão com tokens
- Proteção de rotas privadas
- Logout automático em caso de token expirado

### ✅ Dashboard
- Estatísticas gerais do sistema
- Orçamentos recentes
- Ações rápidas para navegação

### ✅ Layouts Responsivos
- **MainLayout**: Layout principal com menu lateral
- **AuthLayout**: Layout limpo para autenticação
- **PublicLayout**: Layout para visualização pública

### ✅ Sistema de Navegação
- Menu lateral com navegação por seções
- Breadcrumbs (futuro)
- Rotas protegidas por autenticação

### ✅ Gestão de Categorias
- Listagem com busca e paginação
- Criação e edição de categorias
- Exclusão com confirmação

### ✅ Gestão de Orçamentos
- Listagem completa com filtros avançados
- Criação e edição de orçamentos
- Exportação em PDF e Excel
- Compartilhamento via link público
- Diferentes status (rascunho, enviado, aprovado, rejeitado)

## 🎨 Design System

### Cores Principais
- **Primary**: Azul (#1976D2)
- **Secondary**: Cinza (#424242)
- **Positive**: Verde (#21BA45)
- **Negative**: Vermelho (#C10015)
- **Info**: Azul claro (#31CCEC)
- **Warning**: Amarelo (#F2C037)

### Componentes
- Cards com bordas arredondadas
- Botões com hover effects
- Tables responsivas
- Forms com validação
- Dialogs modais
- Notifications (toast)

## 🔌 Integração com API

### Configuração
```javascript
// boot/axios.js
const api = axios.create({
  baseURL: process.env.API_URL || 'http://localhost:8000/api',
  timeout: 10000
})
```

### Interceptors
- **Request**: Adiciona token de autenticação automaticamente
- **Response**: Redireciona para login em caso de 401 (Unauthorized)

### Stores (Pinia)
```javascript
// stores/auth.js
export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
    loading: false
  }),
  getters: {
    isAuthenticated: (state) => !!state.token,
    isAdmin: (state) => state.user?.role === 'admin'
  },
  actions: {
    async login(credentials) { /* ... */ },
    async logout() { /* ... */ }
  }
})
```

## 📱 Responsividade

O sistema é totalmente responsivo, adaptando-se a:
- **Desktop**: Layout completo com sidebar
- **Tablet**: Layout adaptado com navegação otimizada
- **Mobile**: Menu hambúrguer e cards empilhados

### Breakpoints Quasar
- **xs**: < 600px
- **sm**: 600px - 1023px
- **md**: 1024px - 1439px
- **lg**: 1440px - 1919px
- **xl**: >= 1920px

## 🔒 Segurança

### Proteção de Rotas
```javascript
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'Login' })
  } else {
    next()
  }
})
```

### Gerenciamento de Tokens
- Armazenamento seguro no localStorage
- Renovação automática (futuro)
- Limpeza em caso de logout

## 🚀 Próximas Funcionalidades

### Em Desenvolvimento
- [ ] Página de detalhes do orçamento
- [ ] Gestão de itens do orçamento
- [ ] Páginas de Fornecedores, Itens, Preços, Unidades
- [ ] Importação de preços via Excel
- [ ] Busca avançada de preços
- [ ] Histórico de preços

### Futuras Melhorias
- [ ] Notificações em tempo real
- [ ] Tema escuro
- [ ] PWA (Progressive Web App)
- [ ] Offline support
- [ ] Internacionalização (i18n)
- [ ] Acessibilidade (a11y)

## 🛠️ Scripts Disponíveis

```bash
# Desenvolvimento
npm run dev

# Build produção
npm run build

# Linting
npm run lint

# Formatação
npm run format

# Testes (futuro)
npm run test
```

## 📚 Documentação

### Quasar Framework
- [Documentação oficial](https://quasar.dev/)
- [Componentes](https://quasar.dev/vue-components)
- [Utilitários CSS](https://quasar.dev/style/spacing)

### Vue.js 3
- [Guia oficial](https://vuejs.org/guide/)
- [Composition API](https://vuejs.org/guide/composition-api-introduction.html)

### Pinia
- [Documentação](https://pinia.vuejs.org/)
- [Cookbook](https://pinia.vuejs.org/cookbook/)

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

---

*Frontend desenvolvido com ❤️ usando Vue.js 3 e Quasar Framework*
