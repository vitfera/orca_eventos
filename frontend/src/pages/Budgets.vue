<template>
  <q-page class="q-pa-md">
    <div class="row q-mb-md">
      <div class="col">
        <div class="text-h4">Orçamentos</div>
        <div class="text-subtitle1 text-grey-6">Gerencie os orçamentos de eventos</div>
      </div>
      <div class="col-auto">
        <q-btn
          color="primary"
          icon="add"
          label="Novo Orçamento"
          @click="openDialog()"
        />
      </div>
    </div>

    <q-card>
      <q-card-section>
        <div class="row q-gutter-md q-mb-md">
          <div class="col-12 col-md-4">
            <q-input
              dense
              debounce="300"
              v-model="filters.search"
              placeholder="Buscar orçamento..."
              @update:model-value="onSearch"
            >
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
          </div>
          
          <div class="col-12 col-md-3">
            <q-select
              v-model="filters.status"
              :options="statusOptions"
              label="Status"
              dense
              emit-value
              map-options
              clearable
              @update:model-value="onSearch"
            />
          </div>

          <div class="col-12 col-md-3">
            <q-input
              v-model="filters.date_from"
              label="Data início"
              type="date"
              dense
              @update:model-value="onSearch"
            />
          </div>

          <div class="col-12 col-md-2">
            <q-input
              v-model="filters.date_to"
              label="Data fim"
              type="date"
              dense
              @update:model-value="onSearch"
            />
          </div>
        </div>

        <q-table
          :rows="budgets"
          :columns="columns"
          row-key="id"
          :loading="loading"
          :pagination="pagination"
          @request="onRequest"
          binary-state-sort
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
                <q-tooltip>Visualizar</q-tooltip>
              </q-btn>
              <q-btn
                flat
                round
                color="info"
                icon="edit"
                size="sm"
                @click="openDialog(props.row)"
              >
                <q-tooltip>Editar</q-tooltip>
              </q-btn>
              <q-btn
                flat
                round
                color="positive"
                icon="picture_as_pdf"
                size="sm"
                @click="exportPDF(props.row.id)"
              >
                <q-tooltip>Exportar PDF</q-tooltip>
              </q-btn>
              <q-btn
                flat
                round
                color="green"
                icon="file_download"
                size="sm"
                @click="exportExcel(props.row.id)"
              >
                <q-tooltip>Exportar Excel</q-tooltip>
              </q-btn>
              <q-btn
                flat
                round
                color="warning"
                icon="share"
                size="sm"
                @click="sharePublicLink(props.row)"
              >
                <q-tooltip>Link Público</q-tooltip>
              </q-btn>
              <q-btn
                flat
                round
                color="negative"
                icon="delete"
                size="sm"
                @click="deleteBudget(props.row)"
              >
                <q-tooltip>Excluir</q-tooltip>
              </q-btn>
            </q-td>
          </template>
        </q-table>
      </q-card-section>
    </q-card>

    <!-- Dialog Form -->
    <q-dialog v-model="dialog" persistent>
      <q-card style="min-width: 600px">
        <q-card-section>
          <div class="text-h6">{{ isEdit ? 'Editar' : 'Novo' }} Orçamento</div>
        </q-card-section>

        <q-card-section>
          <q-form @submit="saveBudget" class="q-gutter-md">
            <div class="row q-gutter-md">
              <div class="col">
                <q-input
                  v-model="form.event_name"
                  label="Nome do Evento *"
                  outlined
                  :rules="[val => !!val || 'Nome do evento é obrigatório']"
                />
              </div>
              <div class="col">
                <q-input
                  v-model="form.client_name"
                  label="Nome do Cliente *"
                  outlined
                  :rules="[val => !!val || 'Nome do cliente é obrigatório']"
                />
              </div>
            </div>

            <div class="row q-gutter-md">
              <div class="col">
                <q-input
                  v-model="form.client_email"
                  label="E-mail do Cliente"
                  type="email"
                  outlined
                />
              </div>
              <div class="col">
                <q-input
                  v-model="form.client_phone"
                  label="Telefone do Cliente"
                  outlined
                />
              </div>
            </div>

            <div class="row q-gutter-md">
              <div class="col">
                <q-input
                  v-model="form.event_date"
                  label="Data do Evento"
                  type="date"
                  outlined
                />
              </div>
              <div class="col">
                <q-input
                  v-model="form.event_location"
                  label="Local do Evento"
                  outlined
                />
              </div>
            </div>

            <div class="row q-gutter-md">
              <div class="col">
                <q-input
                  v-model="form.guests_count"
                  label="Número de Convidados"
                  type="number"
                  outlined
                />
              </div>
              <div class="col">
                <q-select
                  v-model="form.status"
                  :options="statusOptions"
                  label="Status"
                  emit-value
                  map-options
                  outlined
                />
              </div>
            </div>

            <q-input
              v-model="form.notes"
              label="Observações"
              type="textarea"
              outlined
              rows="3"
            />
          </q-form>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Cancelar" color="primary" @click="closeDialog" />
          <q-btn 
            label="Salvar" 
            color="primary" 
            @click="saveBudget"
            :loading="saving"
            :disable="!form.event_name || !form.client_name"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { api } from '../boot/axios'
import { useQuasar } from 'quasar'

export default defineComponent({
  name: 'BudgetsPage',

  setup() {
    const $q = useQuasar()
    const router = useRouter()
    const budgets = ref([])
    const loading = ref(false)
    const saving = ref(false)
    const dialog = ref(false)
    const isEdit = ref(false)

    const filters = ref({
      search: '',
      status: null,
      date_from: '',
      date_to: ''
    })

    const pagination = ref({
      sortBy: 'created_at',
      descending: true,
      page: 1,
      rowsPerPage: 10,
      rowsNumber: 0
    })

    const form = ref({
      event_name: '',
      client_name: '',
      client_email: '',
      client_phone: '',
      event_date: '',
      event_location: '',
      guests_count: null,
      status: 'draft',
      notes: ''
    })

    const statusOptions = [
      { label: 'Rascunho', value: 'draft' },
      { label: 'Enviado', value: 'sent' },
      { label: 'Aprovado', value: 'approved' },
      { label: 'Rejeitado', value: 'rejected' }
    ]

    const columns = [
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
        name: 'client_name',
        label: 'Cliente',
        align: 'left',
        field: 'client_name',
        sortable: true
      },
      {
        name: 'event_date',
        label: 'Data do Evento',
        align: 'left',
        field: 'event_date',
        format: val => val ? new Date(val).toLocaleDateString('pt-BR') : '-',
        sortable: true
      },
      {
        name: 'total_amount',
        label: 'Valor Total',
        align: 'right',
        field: 'total_amount',
        format: val => val ? `R$ ${parseFloat(val).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}` : 'R$ 0,00',
        sortable: true
      },
      {
        name: 'status',
        label: 'Status',
        align: 'center',
        field: 'status'
      },
      {
        name: 'created_at',
        label: 'Criado em',
        align: 'left',
        field: 'created_at',
        format: val => new Date(val).toLocaleDateString('pt-BR'),
        sortable: true
      },
      {
        name: 'actions',
        label: 'Ações',
        align: 'center'
      }
    ]

    const fetchBudgets = async (props = {}) => {
      loading.value = true
      try {
        const { page = 1, rowsPerPage = 10, sortBy, descending } = props.pagination || pagination.value
        
        const params = {
          page,
          per_page: rowsPerPage,
          search: filters.value.search,
          status: filters.value.status,
          date_from: filters.value.date_from,
          date_to: filters.value.date_to
        }

        if (sortBy) {
          params.orderBy = sortBy
          params.orderDirection = descending ? 'desc' : 'asc'
        }

        const response = await api.get('/budgets', { params })
        
        budgets.value = response.data.data
        pagination.value.rowsNumber = response.data.total
        pagination.value.page = response.data.current_page
        pagination.value.rowsPerPage = response.data.per_page
        
        if (props.pagination) {
          pagination.value.sortBy = props.pagination.sortBy
          pagination.value.descending = props.pagination.descending
        }
      } catch (error) {
        console.error('Erro ao buscar orçamentos:', error)
        $q.notify({
          type: 'negative',
          message: 'Erro ao carregar orçamentos'
        })
      } finally {
        loading.value = false
      }
    }

    const onRequest = (props) => {
      fetchBudgets(props)
    }

    const onSearch = () => {
      pagination.value.page = 1
      fetchBudgets()
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

    const openDialog = (budget = null) => {
      isEdit.value = !!budget
      form.value = budget ? { ...budget } : {
        event_name: '',
        client_name: '',
        client_email: '',
        client_phone: '',
        event_date: '',
        event_location: '',
        guests_count: null,
        status: 'draft',
        notes: ''
      }
      dialog.value = true
    }

    const closeDialog = () => {
      dialog.value = false
    }

    const saveBudget = async () => {
      saving.value = true
      try {
        if (isEdit.value) {
          await api.put(`/budgets/${form.value.id}`, form.value)
          $q.notify({
            type: 'positive',
            message: 'Orçamento atualizado com sucesso!'
          })
        } else {
          await api.post('/budgets', form.value)
          $q.notify({
            type: 'positive',
            message: 'Orçamento criado com sucesso!'
          })
        }
        
        closeDialog()
        fetchBudgets()
      } catch (error) {
        console.error('Erro ao salvar orçamento:', error)
        $q.notify({
          type: 'negative',
          message: error.response?.data?.message || 'Erro ao salvar orçamento'
        })
      } finally {
        saving.value = false
      }
    }

    const viewBudget = (id) => {
      router.push({ name: 'BudgetDetail', params: { id } })
    }

    const exportPDF = async (id) => {
      try {
        const response = await api.get(`/budgets/${id}/pdf`, {
          responseType: 'blob'
        })
        
        const blob = new Blob([response.data], { type: 'application/pdf' })
        const url = window.URL.createObjectURL(blob)
        const link = document.createElement('a')
        link.href = url
        link.download = `orcamento-${id}.pdf`
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

    const exportExcel = async (id) => {
      try {
        const response = await api.get(`/budgets/${id}/excel`, {
          responseType: 'blob'
        })
        
        const blob = new Blob([response.data], { type: 'application/vnd.ms-excel' })
        const url = window.URL.createObjectURL(blob)
        const link = document.createElement('a')
        link.href = url
        link.download = `orcamento-${id}.xlsx`
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

    const sharePublicLink = (budget) => {
      const publicUrl = `${window.location.origin}/#/public/budget/${budget.public_token}`
      
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

    const deleteBudget = (budget) => {
      $q.dialog({
        title: 'Confirmar exclusão',
        message: `Deseja excluir o orçamento "${budget.event_name}"?`,
        cancel: true,
        persistent: true
      }).onOk(async () => {
        try {
          await api.delete(`/budgets/${budget.id}`)
          $q.notify({
            type: 'positive',
            message: 'Orçamento excluído com sucesso!'
          })
          fetchBudgets()
        } catch (error) {
          console.error('Erro ao excluir orçamento:', error)
          $q.notify({
            type: 'negative',
            message: error.response?.data?.message || 'Erro ao excluir orçamento'
          })
        }
      })
    }

    onMounted(() => {
      fetchBudgets()
    })

    return {
      budgets,
      columns,
      loading,
      saving,
      dialog,
      isEdit,
      filters,
      pagination,
      form,
      statusOptions,
      onRequest,
      onSearch,
      getStatusColor,
      getStatusLabel,
      openDialog,
      closeDialog,
      saveBudget,
      viewBudget,
      exportPDF,
      exportExcel,
      sharePublicLink,
      deleteBudget
    }
  }
})
</script>
