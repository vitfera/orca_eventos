import { createRouter, createWebHashHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const routes = [
  // Página inicial pública
  {
    path: '/',
    component: () => import('../layouts/HomeLayout.vue'),
    children: [
      {
        path: '',
        name: 'Home',
        component: () => import('../pages/Home.vue')
      }
    ]
  },
  // Rotas protegidas (dashboard e demais)
  {
    path: '/app',
    component: () => import('../layouts/MainLayout.vue'),
    children: [
      { 
        path: 'dashboard', 
        name: 'Dashboard',
        component: () => import('../pages/Dashboard.vue'),
        meta: { requiresAuth: true }
      },
      { 
        path: 'categories', 
        name: 'Categories',
        component: () => import('../pages/Categories.vue'),
        meta: { requiresAuth: true }
      },
      { 
        path: 'units', 
        name: 'Units',
        component: () => import('../pages/Units.vue'),
        meta: { requiresAuth: true }
      },
      { 
        path: 'suppliers', 
        name: 'Suppliers',
        component: () => import('../pages/Suppliers.vue'),
        meta: { requiresAuth: true }
      },
      { 
        path: 'items', 
        name: 'Items',
        component: () => import('../pages/Items.vue'),
        meta: { requiresAuth: true }
      },
      { 
        path: 'prices', 
        name: 'Prices',
        component: () => import('../pages/Prices.vue'),
        meta: { requiresAuth: true }
      },
      { 
        path: 'budgets', 
        name: 'Budgets',
        component: () => import('../pages/Budgets.vue'),
        meta: { requiresAuth: true }
      },
      { 
        path: 'budgets/:id', 
        name: 'BudgetDetail',
        component: () => import('../pages/BudgetDetail.vue'),
        meta: { requiresAuth: true }
      }
    ]
  },
  {
    path: '/auth',
    component: () => import('../layouts/AuthLayout.vue'),
    children: [
      { 
        path: 'login', 
        name: 'Login',
        component: () => import('../pages/auth/Login.vue')
      }
    ]
  },
  {
    path: '/public',
    component: () => import('../layouts/PublicLayout.vue'),
    children: [
      { 
        path: 'budget/:token', 
        name: 'PublicBudget',
        component: () => import('../pages/public/Budget.vue')
      }
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('../pages/ErrorNotFound.vue')
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

// Navigation guard
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'Login' })
  } else if (to.name === 'Login' && authStore.isAuthenticated) {
    next({ name: 'Dashboard' })
  } else if (to.name === 'Dashboard' && !authStore.isAuthenticated) {
    next({ name: 'Home' })
  } else {
    next()
  }
})

export default router
