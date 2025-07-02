<template>
  <q-page class="q-pa-md">
    <div class="row justify-center">
      <div class="col-12 col-md-8">
        <q-card v-if="loading" class="text-center q-pa-xl">
          <q-spinner size="3rem" color="primary" />
          <div class="q-mt-md text-h6">Carregando orçamento...</div>
        </q-card>

        <q-card v-else-if="error" class="text-center q-pa-xl">
          <q-icon name="error" size="4rem" color="negative" />
          <div class="q-mt-md text-h6 text-negative">{{ error }}</div>
          <q-btn 
            class="q-mt-md" 
            color="primary" 
            label="Tentar novamente"
            @click="fetchBudget"
          />
        </q-card>

        <div v-else-if="budget">
          <!-- Header do Orçamento -->
          <q-card class="q-mb-md">
            <q-card-section>
              <div class="text-h4 text-primary">{{ budget.event_name }}</div>
              <div class="text-subtitle1 text-grey-6">Orçamento #{{ budget.code }}</div>
            </q-card-section>
          </q-card>

          <!-- Informações do Cliente -->
          <q-card class="q-mb-md">
            <q-card-section>
              <div class="text-h6 flex items-center q-mb-md">
                <q-icon name="person" class="q-mr-sm" />
                Informações do Cliente
              </div>
              <div class="row q-gutter-md">
                <div class="col-12 col-md-6">
                  <q-item>
                    <q-item-section avatar>
                      <q-icon name="person" />
                    </q-item-section>
                    <q-item-section>
                      <q-item-label>Nome</q-item-label>
                      <q-item-label caption>{{ budget.client_name }}</q-item-label>
                    </q-item-section>
                  </q-item>
                </div>
                <div class="col-12 col-md-6" v-if="budget.client_email">
                  <q-item>
                    <q-item-section avatar>
                      <q-icon name="email" />
                    </q-item-section>
                    <q-item-section>
                      <q-item-label>E-mail</q-item-label>
                      <q-item-label caption>{{ budget.client_email }}</q-item-label>
                    </q-item-section>
                  </q-item>
                </div>
                <div class="col-12 col-md-6" v-if="budget.client_phone">
                  <q-item>
                    <q-item-section avatar>
                      <q-icon name="phone" />
                    </q-item-section>
                    <q-item-section>
                      <q-item-label>Telefone</q-item-label>
                      <q-item-label caption>{{ budget.client_phone }}</q-item-label>
                    </q-item-section>
                  </q-item>
                </div>
              </div>
            </q-card-section>
          </q-card>

          <!-- Informações do Evento -->
          <q-card class="q-mb-md">
            <q-card-section>
              <div class="text-h6 flex items-center q-mb-md">
                <q-icon name="event" class="q-mr-sm" />
                Informações do Evento
              </div>
              <div class="row q-gutter-md">
                <div class="col-12 col-md-6" v-if="budget.event_date">
                  <q-item>
                    <q-item-section avatar>
                      <q-icon name="calendar_today" />
                    </q-item-section>
                    <q-item-section>
                      <q-item-label>Data</q-item-label>
                      <q-item-label caption>{{ formatDate(budget.event_date) }}</q-item-label>
                    </q-item-section>
                  </q-item>
                </div>
                <div class="col-12 col-md-6" v-if="budget.event_location">
                  <q-item>
                    <q-item-section avatar>
                      <q-icon name="location_on" />
                    </q-item-section>
                    <q-item-section>
                      <q-item-label>Local</q-item-label>
                      <q-item-label caption>{{ budget.event_location }}</q-item-label>
                    </q-item-section>
                  </q-item>
                </div>
                <div class="col-12 col-md-6" v-if="budget.guests_count">
                  <q-item>
                    <q-item-section avatar>
                      <q-icon name="group" />
                    </q-item-section>
                    <q-item-section>
                      <q-item-label>Convidados</q-item-label>
                      <q-item-label caption>{{ budget.guests_count }} pessoas</q-item-label>
                    </q-item-section>
                  </q-item>
                </div>
              </div>
            </q-card-section>
          </q-card>

          <!-- Itens do Orçamento -->
          <q-card class="q-mb-md">
            <q-card-section>
              <div class="text-h6 flex items-center q-mb-md">
                <q-icon name="list" class="q-mr-sm" />
                Itens do Orçamento
              </div>
              
              <q-table
                :rows="budget.budget_items || []"
                :columns="itemColumns"
                row-key="id"
                flat
                :pagination="{ rowsPerPage: 0 }"
                hide-pagination
              >
                <template v-slot:body-cell-subtotal="props">
                  <q-td :props="props">
                    {{ formatCurrency(props.row.quantity * props.row.unit_price) }}
                  </q-td>
                </template>
              </q-table>
            </q-card-section>
          </q-card>

          <!-- Resumo Financeiro -->
          <q-card class="q-mb-md">
            <q-card-section>
              <div class="text-h6 flex items-center q-mb-md">
                <q-icon name="attach_money" class="q-mr-sm" />
                Resumo Financeiro
              </div>
              
              <div class="row justify-end">
                <div class="col-12 col-md-6">
                  <q-list>
                    <q-item>
                      <q-item-section>
                        <q-item-label class="text-weight-bold text-h5">
                          Total: {{ formatCurrency(budget.total_amount) }}
                        </q-item-label>
                      </q-item-section>
                    </q-item>
                  </q-list>
                </div>
              </div>
            </q-card-section>
          </q-card>

          <!-- Observações -->
          <q-card v-if="budget.notes" class="q-mb-md">
            <q-card-section>
              <div class="text-h6 flex items-center q-mb-md">
                <q-icon name="note" class="q-mr-sm" />
                Observações
              </div>
              <div class="text-body1">{{ budget.notes }}</div>
            </q-card-section>
          </q-card>

          <!-- Rodapé -->
          <q-card>
            <q-card-section class="text-center">
              <div class="text-grey-6">
                Orçamento gerado em {{ formatDate(budget.created_at) }}
              </div>
              <div class="text-caption text-grey-5 q-mt-sm">
                Sistema OrçaEventos - Gestão de Orçamentos para Eventos
              </div>
            </q-card-section>
          </q-card>
        </div>
      </div>
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { api } from '../../boot/axios'

export default defineComponent({
  name: 'PublicBudgetPage',

  setup() {
    const route = useRoute()
    const budget = ref(null)
    const loading = ref(true)
    const error = ref('')

    const itemColumns = [
      {
        name: 'item_name',
        label: 'Item',
        align: 'left',
        field: row => row.item?.name || 'Item não encontrado'
      },
      {
        name: 'quantity',
        label: 'Quantidade',
        align: 'center',
        field: 'quantity'
      },
      {
        name: 'unit',
        label: 'Unidade',
        align: 'center',
        field: row => row.item?.unit?.name || '-'
      },
      {
        name: 'unit_price',
        label: 'Preço Unit.',
        align: 'right',
        field: 'unit_price',
        format: val => `R$ ${parseFloat(val || 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`
      },
      {
        name: 'subtotal',
        label: 'Subtotal',
        align: 'right'
      }
    ]

    const fetchBudget = async () => {
      loading.value = true
      error.value = ''
      
      try {
        const token = route.params.token
        const response = await api.get(`/public/budgets/${token}`)
        budget.value = response.data
      } catch (err) {
        console.error('Erro ao buscar orçamento:', err)
        if (err.response?.status === 404) {
          error.value = 'Orçamento não encontrado ou link inválido'
        } else {
          error.value = 'Erro ao carregar orçamento. Tente novamente.'
        }
      } finally {
        loading.value = false
      }
    }

    const formatDate = (date) => {
      if (!date) return '-'
      return new Date(date).toLocaleDateString('pt-BR')
    }

    const formatCurrency = (value) => {
      if (!value) return 'R$ 0,00'
      return `R$ ${parseFloat(value).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`
    }

    onMounted(() => {
      fetchBudget()
    })

    return {
      budget,
      loading,
      error,
      itemColumns,
      fetchBudget,
      formatDate,
      formatCurrency
    }
  }
})
</script>

<style lang="sass" scoped>
.q-card
  border-radius: 12px
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1)
</style>
