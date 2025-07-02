<template>
  <q-page class="q-pa-md">
    <div class="row q-mb-md" v-if="budget">
      <div class="col">
        <div class="text-h4">{{ budget.event_name }}</div>
        <div class="text-subtitle1 text-grey-6">Orçamento #{{ budget.code }}</div>
      </div>
      <div class="col-auto q-gutter-sm">
        <q-btn
          color="positive"
          icon="picture_as_pdf"
          label="Exportar PDF"
          @click="exportPDF"
        />
        <q-btn
          color="green"
          icon="file_download"
          label="Exportar Excel"
          @click="exportExcel"
        />
        <q-btn
          color="warning"
          icon="share"
          label="Link Público"
          @click="sharePublicLink"
        />
        <q-btn
          color="primary"
          icon="edit"
          label="Editar"
          @click="editBudget"
        />
      </div>
    </div>

    <div v-if="loading" class="text-center q-pa-xl">
      <q-spinner size="3rem" color="primary" />
      <div class="q-mt-md text-h6">Carregando orçamento...</div>
    </div>

    <div v-else-if="error" class="text-center q-pa-xl">
      <q-icon name="error" size="4rem" color="negative" />
      <div class="q-mt-md text-h6 text-negative">{{ error }}</div>
      <q-btn 
        class="q-mt-md" 
        color="primary" 
        label="Voltar"
        @click="$router.push({ name: 'Budgets' })"
      />
    </div>

    <div v-else-if="budget" class="row q-gutter-md">
      <!-- Informações Gerais -->
      <div class="col-12 col-md-8">
        <q-card class="q-mb-md">
          <q-card-section>
            <div class="text-h6 flex items-center q-mb-md">
              <q-icon name="info" class="q-mr-sm" />
              Informações Gerais
            </div>
            
            <div class="row q-gutter-md">
              <div class="col-12 col-md-6">
                <q-item>
                  <q-item-section avatar>
                    <q-icon name="person" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>Cliente</q-item-label>
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
              <div class="col-12 col-md-6" v-if="budget.event_date">
                <q-item>
                  <q-item-section avatar>
                    <q-icon name="calendar_today" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>Data do Evento</q-item-label>
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
            </div>
          </q-card-section>
        </q-card>

        <!-- Itens do Orçamento -->
        <q-card>
          <q-card-section>
            <div class="text-h6 flex items-center justify-between q-mb-md">
              <div class="flex items-center">
                <q-icon name="list" class="q-mr-sm" />
                Itens do Orçamento
              </div>
              <q-btn
                color="primary"
                icon="add"
                label="Adicionar Item"
                size="sm"
                @click="openItemDialog()"
              />
            </div>
            
            <q-table
              :rows="budgetItems"
              :columns="itemColumns"
              row-key="id"
              flat
              :pagination="{ rowsPerPage: 0 }"
              hide-pagination
              :loading="itemsLoading"
            >
              <template v-slot:body-cell-subtotal="props">
                <q-td :props="props">
                  {{ formatCurrency(props.row.quantity * props.row.unit_price) }}
                </q-td>
              </template>

              <template v-slot:body-cell-actions="props">
                <q-td :props="props">
                  <q-btn
                    flat
                    round
                    color="primary"
                    icon="edit"
                    size="sm"
                    @click="openItemDialog(props.row)"
                  >
                    <q-tooltip>Editar</q-tooltip>
                  </q-btn>
                  <q-btn
                    flat
                    round
                    color="negative"
                    icon="delete"
                    size="sm"
                    @click="deleteItem(props.row)"
                  >
                    <q-tooltip>Excluir</q-tooltip>
                  </q-btn>
                </q-td>
              </template>

              <template v-slot:bottom>
                <div class="full-width text-right">
                  <div class="text-h6 text-weight-bold">
                    Total: {{ formatCurrency(totalAmount) }}
                  </div>
                </div>
              </template>
            </q-table>
          </q-card-section>
        </q-card>
      </div>

      <!-- Sidebar -->
      <div class="col-12 col-md-4">
        <!-- Status e Ações -->
        <q-card class="q-mb-md">
          <q-card-section>
            <div class="text-h6 q-mb-md">Status</div>
            <q-chip 
              :color="getStatusColor(budget.status)" 
              text-color="white" 
              size="lg"
              class="q-mb-md"
            >
              {{ getStatusLabel(budget.status) }}
            </q-chip>
            
            <div class="q-mt-md">
              <q-btn
                color="info"
                label="Alterar Status"
                class="full-width"
                @click="openStatusDialog"
              />
            </div>
          </q-card-section>
        </q-card>

        <!-- Resumo Financeiro -->
        <q-card class="q-mb-md">
          <q-card-section>
            <div class="text-h6 q-mb-md">Resumo Financeiro</div>
            <q-list>
              <q-item>
                <q-item-section>
                  <q-item-label>Qtd. Itens</q-item-label>
                  <q-item-label caption>{{ budgetItems.length }}</q-item-label>
                </q-item-section>
              </q-item>
              <q-item>
                <q-item-section>
                  <q-item-label class="text-weight-bold text-h6">
                    Total: {{ formatCurrency(totalAmount) }}
                  </q-item-label>
                </q-item-section>
              </q-item>
            </q-list>
          </q-card-section>
        </q-card>

        <!-- Observações -->
        <q-card v-if="budget.notes">
          <q-card-section>
            <div class="text-h6 q-mb-md">Observações</div>
            <div class="text-body1">{{ budget.notes }}</div>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <!-- Dialog Item -->
    <q-dialog v-model="itemDialog" persistent>
      <q-card style="min-width: 500px">
        <q-card-section>
          <div class="text-h6">{{ isEditItem ? 'Editar' : 'Adicionar' }} Item</div>
        </q-card-section>

        <q-card-section>
          <q-form class="q-gutter-md">
            <q-select
              v-model="itemForm.item_id"
              :options="itemOptions"
              label="Item *"
              outlined
              emit-value
              map-options
              use-input
              @filter="filterItems"
              :rules="[val => !!val || 'Item é obrigatório']"
            />

            <div class="row q-gutter-md">
              <div class="col">
                <q-input
                  v-model="itemForm.quantity"
                  label="Quantidade *"
                  type="number"
                  min="0.01"
                  step="0.01"
                  outlined
                  :rules="[val => !!val || 'Quantidade é obrigatória']"
                />
              </div>
              <div class="col">
                <q-input
                  v-model="itemForm.unit_price"
                  label="Preço Unitário *"
                  type="number"
                  min="0"
                  step="0.01"
                  outlined
                  prefix="R$"
                  :rules="[val => !!val || 'Preço é obrigatório']"
                />
              </div>
            </div>

            <q-input
              v-model="itemForm.notes"
              label="Observações"
              type="textarea"
              outlined
              rows="2"
            />
          </q-form>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Cancelar" color="primary" @click="closeItemDialog" />
          <q-btn 
            label="Salvar" 
            color="primary" 
            @click="saveItem"
            :loading="savingItem"
            :disable="!itemForm.item_id || !itemForm.quantity || !itemForm.unit_price"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <!-- Dialog Status -->
    <q-dialog v-model="statusDialog" persistent>
      <q-card style="min-width: 400px">
        <q-card-section>
          <div class="text-h6">Alterar Status</div>
        </q-card-section>

        <q-card-section>
          <q-select
            v-model="newStatus"
            :options="statusOptions"
            label="Novo Status"
            outlined
            emit-value
            map-options
          />
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Cancelar" color="primary" @click="statusDialog = false" />
          <q-btn 
            label="Alterar" 
            color="primary" 
            @click="updateStatus"
            :loading="updatingStatus"
            :disable="!newStatus"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { api } from '../boot/axios'
import { useQuasar } from 'quasar'

export default defineComponent({
  name: 'BudgetDetailPage',

  setup() {
    const $q = useQuasar()
    const route = useRoute()
    const router = useRouter()
    
    const budget = ref(null)
    const budgetItems = ref([])
    const loading = ref(true)
    const itemsLoading = ref(false)
    const savingItem = ref(false)
    const updatingStatus = ref(false)
    const error = ref('')

    // Dialogs
    const itemDialog = ref(false)
    const statusDialog = ref(false)
    const isEditItem = ref(false)
    const newStatus = ref('')

    // Options
    const itemOptions = ref([])

    const itemForm = ref({
      item_id: null,
      quantity: '',
      unit_price: '',
      notes: ''
    })

    const statusOptions = [
      { label: 'Rascunho', value: 'draft' },
      { label: 'Enviado', value: 'sent' },
      { label: 'Aprovado', value: 'approved' },
      { label: 'Rejeitado', value: 'rejected' }
    ]

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
      },
      {
        name: 'actions',
        label: 'Ações',
        align: 'center'
      }
    ]

    const totalAmount = computed(() => {
      return budgetItems.value.reduce((sum, item) => {
        return sum + (parseFloat(item.quantity) * parseFloat(item.unit_price))
      }, 0)
    })

    const fetchBudget = async () => {
      loading.value = true
      error.value = ''
      
      try {
        const id = route.params.id
        const response = await api.get(`/budgets/${id}`)
        budget.value = response.data
        
        await fetchBudgetItems()
      } catch (err) {
        console.error('Erro ao buscar orçamento:', err)
        if (err.response?.status === 404) {
          error.value = 'Orçamento não encontrado'
        } else {
          error.value = 'Erro ao carregar orçamento'
        }
      } finally {
        loading.value = false
      }
    }

    const fetchBudgetItems = async () => {
      if (!budget.value) return
      
      itemsLoading.value = true
      try {
        const response = await api.get(`/budget-items?budget_id=${budget.value.id}`)
        budgetItems.value = response.data.data || response.data || []
      } catch (error) {
        console.error('Erro ao buscar itens:', error)
        $q.notify({
          type: 'negative',
          message: 'Erro ao carregar itens do orçamento'
        })
      } finally {
        itemsLoading.value = false
      }
    }

    const fetchItems = async () => {
      try {
        const response = await api.get('/items?active=true')
        itemOptions.value = response.data.data?.map(item => ({
          label: item.name,
          value: item.id
        })) || []
      } catch (error) {
        console.error('Erro ao carregar itens:', error)
      }
    }

    const filterItems = (val, update) => {
      update(() => {
        if (val === '') {
          fetchItems()
        } else {
          const needle = val.toLowerCase()
          itemOptions.value = itemOptions.value.filter(
            item => item.label.toLowerCase().indexOf(needle) > -1
          )
        }
      })
    }

    const formatDate = (date) => {
      if (!date) return '-'
      return new Date(date).toLocaleDateString('pt-BR')
    }

    const formatCurrency = (value) => {
      if (!value) return 'R$ 0,00'
      return `R$ ${parseFloat(value).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`
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

    const openItemDialog = (item = null) => {
      isEditItem.value = !!item
      itemForm.value = item ? { ...item } : {
        item_id: null,
        quantity: '',
        unit_price: '',
        notes: ''
      }
      itemDialog.value = true
    }

    const closeItemDialog = () => {
      itemDialog.value = false
    }

    const saveItem = async () => {
      savingItem.value = true
      try {
        const data = {
          ...itemForm.value,
          budget_id: budget.value.id
        }

        if (isEditItem.value) {
          await api.put(`/budget-items/${itemForm.value.id}`, data)
          $q.notify({
            type: 'positive',
            message: 'Item atualizado com sucesso!'
          })
        } else {
          await api.post('/budget-items', data)
          $q.notify({
            type: 'positive',
            message: 'Item adicionado com sucesso!'
          })
        }
        
        closeItemDialog()
        fetchBudgetItems()
      } catch (error) {
        console.error('Erro ao salvar item:', error)
        $q.notify({
          type: 'negative',
          message: error.response?.data?.message || 'Erro ao salvar item'
        })
      } finally {
        savingItem.value = false
      }
    }

    const deleteItem = (item) => {
      $q.dialog({
        title: 'Confirmar exclusão',
        message: `Deseja excluir o item "${item.item?.name}"?`,
        cancel: true,
        persistent: true
      }).onOk(async () => {
        try {
          await api.delete(`/budget-items/${item.id}`)
          $q.notify({
            type: 'positive',
            message: 'Item excluído com sucesso!'
          })
          fetchBudgetItems()
        } catch (error) {
          console.error('Erro ao excluir item:', error)
          $q.notify({
            type: 'negative',
            message: 'Erro ao excluir item'
          })
        }
      })
    }

    const openStatusDialog = () => {
      newStatus.value = budget.value.status
      statusDialog.value = true
    }

    const updateStatus = async () => {
      updatingStatus.value = true
      try {
        await api.put(`/budgets/${budget.value.id}`, {
          ...budget.value,
          status: newStatus.value
        })
        
        budget.value.status = newStatus.value
        statusDialog.value = false
        
        $q.notify({
          type: 'positive',
          message: 'Status atualizado com sucesso!'
        })
      } catch (error) {
        console.error('Erro ao atualizar status:', error)
        $q.notify({
          type: 'negative',
          message: 'Erro ao atualizar status'
        })
      } finally {
        updatingStatus.value = false
      }
    }

    const editBudget = () => {
      router.push({ name: 'Budgets' })
    }

    const exportPDF = async () => {
      try {
        const response = await api.get(`/budgets/${budget.value.id}/pdf`, {
          responseType: 'blob'
        })
        
        const blob = new Blob([response.data], { type: 'application/pdf' })
        const url = window.URL.createObjectURL(blob)
        const link = document.createElement('a')
        link.href = url
        link.download = `orcamento-${budget.value.code}.pdf`
        link.click()
        window.URL.revokeObjectURL(url)
        
        $q.notify({
          type: 'positive',
          message: 'PDF exportado com sucesso!'
        })
      } catch (error) {
        console.error('Erro ao exportar PDF:', error)
        $q.notify({
          type: 'negative',
          message: 'Erro ao exportar PDF'
        })
      }
    }

    const exportExcel = async () => {
      try {
        const response = await api.get(`/budgets/${budget.value.id}/excel`, {
          responseType: 'blob'
        })
        
        const blob = new Blob([response.data], { type: 'application/vnd.ms-excel' })
        const url = window.URL.createObjectURL(blob)
        const link = document.createElement('a')
        link.href = url
        link.download = `orcamento-${budget.value.code}.xlsx`
        link.click()
        window.URL.revokeObjectURL(url)
        
        $q.notify({
          type: 'positive',
          message: 'Excel exportado com sucesso!'
        })
      } catch (error) {
        console.error('Erro ao exportar Excel:', error)
        $q.notify({
          type: 'negative',
          message: 'Erro ao exportar Excel'
        })
      }
    }

    const sharePublicLink = () => {
      const publicUrl = `${window.location.origin}/#/public/budget/${budget.value.public_token}`
      
      navigator.clipboard.writeText(publicUrl).then(() => {
        $q.notify({
          type: 'positive',
          message: 'Link público copiado para a área de transferência!'
        })
      }).catch(() => {
        $q.dialog({
          title: 'Link Público',
          message: `Copie o link abaixo para compartilhar o orçamento:`,
          prompt: {
            model: publicUrl,
            type: 'text',
            readonly: true
          },
          cancel: false,
          persistent: true
        })
      })
    }

    onMounted(() => {
      fetchBudget()
      fetchItems()
    })

    return {
      budget,
      budgetItems,
      loading,
      itemsLoading,
      savingItem,
      updatingStatus,
      error,
      itemDialog,
      statusDialog,
      isEditItem,
      newStatus,
      itemForm,
      itemOptions,
      statusOptions,
      itemColumns,
      totalAmount,
      formatDate,
      formatCurrency,
      getStatusColor,
      getStatusLabel,
      openItemDialog,
      closeItemDialog,
      saveItem,
      deleteItem,
      openStatusDialog,
      updateStatus,
      editBudget,
      exportPDF,
      exportExcel,
      sharePublicLink,
      filterItems
    }
  }
})
</script>
