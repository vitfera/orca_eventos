<template>
  <q-page class="q-pa-md">
    <div class="row q-mb-md">
      <div class="col">
        <div class="text-h4">{{ title }}</div>
        <div class="text-subtitle1 text-grey-6">{{ subtitle }}</div>
      </div>
    </div>

    <q-card>
      <q-card-section class="text-center q-pa-xl">
        <q-icon :name="icon" size="6rem" color="grey-5" class="q-mb-md" />
        <div class="text-h6 text-grey-6 q-mb-md">
          Esta funcionalidade está em desenvolvimento
        </div>
        <div class="text-body1 text-grey-5 q-mb-lg">
          Em breve você poderá gerenciar {{ title.toLowerCase() }} aqui.
        </div>
        <q-btn
          color="primary"
          label="Voltar ao Dashboard"
          @click="$router.push({ name: 'Dashboard' })"
          outline
        />
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { defineComponent, computed } from 'vue'
import { useRoute } from 'vue-router'

export default defineComponent({
  name: 'ComingSoonPage',

  setup() {
    const route = useRoute()

    const pageConfig = {
      Units: {
        title: 'Unidades',
        subtitle: 'Gerencie as unidades de medida',
        icon: 'straighten'
      },
      Suppliers: {
        title: 'Fornecedores',
        subtitle: 'Gerencie os fornecedores',
        icon: 'business'
      },
      Items: {
        title: 'Itens',
        subtitle: 'Gerencie o catálogo de itens',
        icon: 'inventory'
      },
      Prices: {
        title: 'Preços',
        subtitle: 'Gerencie os preços dos itens',
        icon: 'attach_money'
      },
      BudgetDetail: {
        title: 'Detalhes do Orçamento',
        subtitle: 'Visualize e edite os detalhes do orçamento',
        icon: 'description'
      }
    }

    const config = computed(() => {
      return pageConfig[route.name] || {
        title: 'Página',
        subtitle: 'Em desenvolvimento',
        icon: 'construction'
      }
    })

    return {
      title: config.value.title,
      subtitle: config.value.subtitle,
      icon: config.value.icon
    }
  }
})
</script>
