<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar class="bg-primary text-white">
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />

        <q-toolbar-title class="flex items-center">
          <q-icon name="event" size="sm" class="q-mr-sm" />
          OrçaEventos
        </q-toolbar-title>

        <div class="q-mr-md">
          <q-chip 
            :color="user?.role === 'admin' ? 'positive' : 'info'" 
            text-color="white"
            :label="user?.role === 'admin' ? 'Admin' : 'Fornecedor'"
          />
        </div>

        <q-btn-dropdown flat no-caps :label="user?.name || 'Usuário'">
          <q-list>
            <q-item clickable v-close-popup @click="logout">
              <q-item-section avatar>
                <q-icon name="logout" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Sair</q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </q-btn-dropdown>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
      class="bg-grey-1"
    >
      <q-list>
        <q-item-label header class="text-grey-8">
          Menu Principal
        </q-item-label>

        <q-item 
          clickable 
          :to="{ name: 'Dashboard' }"
          exact-active-class="bg-primary text-white"
        >
          <q-item-section avatar>
            <q-icon name="dashboard" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Dashboard</q-item-label>
          </q-item-section>
        </q-item>

        <q-separator />

        <q-item-label header class="text-grey-8">
          Cadastros
        </q-item-label>

        <q-item 
          clickable 
          :to="{ name: 'Categories' }"
          exact-active-class="bg-primary text-white"
        >
          <q-item-section avatar>
            <q-icon name="category" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Categorias</q-item-label>
          </q-item-section>
        </q-item>

        <q-item 
          clickable 
          :to="{ name: 'Units' }"
          exact-active-class="bg-primary text-white"
        >
          <q-item-section avatar>
            <q-icon name="straighten" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Unidades</q-item-label>
          </q-item-section>
        </q-item>

        <q-item 
          clickable 
          :to="{ name: 'Suppliers' }"
          exact-active-class="bg-primary text-white"
        >
          <q-item-section avatar>
            <q-icon name="business" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Fornecedores</q-item-label>
          </q-item-section>
        </q-item>

        <q-item 
          clickable 
          :to="{ name: 'Items' }"
          exact-active-class="bg-primary text-white"
        >
          <q-item-section avatar>
            <q-icon name="inventory" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Itens</q-item-label>
          </q-item-section>
        </q-item>

        <q-separator />

        <q-item-label header class="text-grey-8">
          Preços & Orçamentos
        </q-item-label>

        <q-item 
          clickable 
          :to="{ name: 'Prices' }"
          exact-active-class="bg-primary text-white"
        >
          <q-item-section avatar>
            <q-icon name="attach_money" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Preços</q-item-label>
          </q-item-section>
        </q-item>

        <q-item 
          clickable 
          :to="{ name: 'Budgets' }"
          exact-active-class="bg-primary text-white"
        >
          <q-item-section avatar>
            <q-icon name="description" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Orçamentos</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'
import { useQuasar } from 'quasar'

export default defineComponent({
  name: 'MainLayout',

  setup() {
    const $q = useQuasar()
    const router = useRouter()
    const authStore = useAuthStore()
    const leftDrawerOpen = ref(false)

    const logout = async () => {
      $q.dialog({
        title: 'Confirmar',
        message: 'Deseja realmente sair do sistema?',
        cancel: true,
        persistent: true
      }).onOk(async () => {
        await authStore.logout()
        $q.notify({
          type: 'positive',
          message: 'Logout realizado com sucesso!'
        })
        router.push({ name: 'Login' })
      })
    }

    return {
      leftDrawerOpen,
      user: authStore.user,
      toggleLeftDrawer() {
        leftDrawerOpen.value = !leftDrawerOpen.value
      },
      logout
    }
  }
})
</script>
