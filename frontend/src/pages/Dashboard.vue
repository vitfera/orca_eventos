<template>
  <q-page class="q-pa-md">
    <div class="row q-gutter-md">
      <!-- Cards de Estatísticas -->
      <div class="col-12">
        <div class="row q-gutter-md">
          <div class="col-12 col-md-3">
            <q-card class="stat-card bg-primary text-white">
              <q-card-section>
                <div class="flex items-center">
                  <q-icon name="description" size="3rem" class="q-mr-md" />
                  <div>
                    <div class="text-h4">{{ stats.budgets }}</div>
                    <div class="text-subtitle1">Orçamentos</div>
                  </div>
                </div>
              </q-card-section>
            </q-card>
          </div>

          <div class="col-12 col-md-3">
            <q-card class="stat-card bg-positive text-white">
              <q-card-section>
                <div class="flex items-center">
                  <q-icon name="inventory" size="3rem" class="q-mr-md" />
                  <div>
                    <div class="text-h4">{{ stats.items }}</div>
                    <div class="text-subtitle1">Itens</div>
                  </div>
                </div>
              </q-card-section>
            </q-card>
          </div>

          <div class="col-12 col-md-3">
            <q-card class="stat-card bg-info text-white">
              <q-card-section>
                <div class="flex items-center">
                  <q-icon name="attach_money" size="3rem" class="q-mr-md" />
                  <div>
                    <div class="text-h4">{{ stats.prices }}</div>
                    <div class="text-subtitle1">Preços</div>
                  </div>
                </div>
              </q-card-section>
            </q-card>
          </div>

          <div class="col-12 col-md-3">
            <q-card class="stat-card bg-warning text-white">
              <q-card-section>
                <div class="flex items-center">
                  <q-icon name="business" size="3rem" class="q-mr-md" />
                  <div>
                    <div class="text-h4">{{ stats.suppliers }}</div>
                    <div class="text-subtitle1">Fornecedores</div>
                  </div>
                </div>
              </q-card-section>
            </q-card>
          </div>
        </div>
      </div>

      <!-- Orçamentos Recentes -->
      <div class="col-12 col-md-8">
        <q-card>
          <q-card-section>
            <div class="text-h6 flex items-center">
              <q-icon name="description" class="q-mr-sm" />
              Orçamentos Recentes
            </div>
          </q-card-section>
          
          <q-card-section>
            <q-table
              :rows="recentBudgets"
              :columns="budgetColumns"
              row-key="id"
              flat
              :loading="loading"
              :pagination="{ rowsPerPage: 5 }"
            >
              <template v-slot:body-cell-status="props">
                <q-td :props="props">
                  <q-chip 
                    :color="getStatusColor(props.value)" 
                    text-color="white" 
                    size="sm"
                  >
                    {{ getStatusLabel(props.value) }}
                  </q-chip>
                </q-td>
              </template>

              <template v-slot:body-cell-actions="props">
                <q-td :props="props">
                  <q-btn 
                    flat 
                    round 
                    color="primary" 
                    icon="visibility"
                    size="sm"
                    @click="viewBudget(props.row.id)"
                  >
                    <q-tooltip>Ver Orçamento</q-tooltip>
                  </q-btn>
                </q-td>
              </template>
            </q-table>
          </q-card-section>
        </q-card>
      </div>

      <!-- Ações Rápidas -->
      <div class="col-12 col-md-4">
        <q-card>
          <q-card-section>
            <div class="text-h6 flex items-center">
              <q-icon name="flash_on" class="q-mr-sm" />
              Ações Rápidas
            </div>
          </q-card-section>
          
          <q-card-section>
            <div class="column q-gutter-md">
              <q-btn 
                color="primary" 
                icon="add" 
                label="Novo Orçamento"
                @click="$router.push({ name: 'Budgets' })"
                class="full-width"
              />
              
              <q-btn 
                color="positive" 
                icon="inventory" 
                label="Gerenciar Itens"
                @click="$router.push({ name: 'Items' })"
                class="full-width"
                outline
              />
              
              <q-btn 
                color="info" 
                icon="attach_money" 
                label="Atualizar Preços"
                @click="$router.push({ name: 'Prices' })"
                class="full-width"
                outline
              />
              
              <q-btn 
                color="warning" 
                icon="business" 
                label="Fornecedores"
                @click="$router.push({ name: 'Suppliers' })"
                class="full-width"
                outline
              />
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { api } from '../boot/axios'
import { useQuasar } from 'quasar'

export default defineComponent({
  name: 'Dashboard',

  setup() {
    const $q = useQuasar()
    const router = useRouter()
    const loading = ref(false)
    
    const stats = ref({
      budgets: 0,
      items: 0,
      prices: 0,
      suppliers: 0
    })

    const recentBudgets = ref([])

    const budgetColumns = [
      {
        name: 'code',
        label: 'Código',
        align: 'left',
        field: 'code',
        sortable: true
      },
      {
        name: 'event_name',
        label: 'Evento',
        align: 'left',
        field: 'event_name',
        sortable: true
      },
      {
        name: 'event_date',
        label: 'Data',
        align: 'left',
        field: 'event_date',
        sortable: true,
        format: (val) => val ? new Date(val).toLocaleDateString('pt-BR') : '-'
      },
      {
        name: 'total_amount',
        label: 'Valor Total',
        align: 'right',
        field: 'total_amount',
        sortable: true,
        format: (val) => val ? `R$ ${parseFloat(val).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}` : 'R$ 0,00'
      },
      {
        name: 'status',
        label: 'Status',
        align: 'center',
        field: 'status'
      },
      {
        name: 'actions',
        label: 'Ações',
        align: 'center'
      }
    ]

    const fetchStats = async () => {
      try {
        const [budgetsRes, itemsRes, pricesRes, suppliersRes] = await Promise.all([
          api.get('/budgets?limit=1'),
          api.get('/items?limit=1'),
          api.get('/prices?limit=1'),
          api.get('/suppliers?limit=1')
        ])

        stats.value.budgets = budgetsRes.data.total || 0
        stats.value.items = itemsRes.data.total || 0
        stats.value.prices = pricesRes.data.total || 0
        stats.value.suppliers = suppliersRes.data.total || 0
      } catch (error) {
        console.error('Erro ao buscar estatísticas:', error)
      }
    }

    const fetchRecentBudgets = async () => {
      loading.value = true
      try {
        const response = await api.get('/budgets?limit=5&orderBy=created_at&orderDirection=desc')
        recentBudgets.value = response.data.data || []
      } catch (error) {
        console.error('Erro ao buscar orçamentos recentes:', error)
        $q.notify({
          type: 'negative',
          message: 'Erro ao carregar orçamentos recentes'
        })
      } finally {
        loading.value = false
      }
    }

    const getStatusColor = (status) => {
      const colors = {
        draft: 'grey',
        sent: 'info',
        approved: 'positive',
        rejected: 'negative'
      }
      return colors[status] || 'grey'
    }

    const getStatusLabel = (status) => {
      const labels = {
        draft: 'Rascunho',
        sent: 'Enviado',
        approved: 'Aprovado',
        rejected: 'Rejeitado'
      }
      return labels[status] || 'Desconhecido'
    }

    const viewBudget = (id) => {
      router.push({ name: 'BudgetDetail', params: { id } })
    }

    onMounted(() => {
      fetchStats()
      fetchRecentBudgets()
    })

    return {
      stats,
      recentBudgets,
      budgetColumns,
      loading,
      getStatusColor,
      getStatusLabel,
      viewBudget
    }
  }
})
</script>

<style lang="sass" scoped>
.stat-card
  border-radius: 12px
  min-height: 120px
</style>
