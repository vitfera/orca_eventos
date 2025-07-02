<template>
  <q-page class="q-pa-md">
    <div class="row q-mb-md">
      <div class="col">
        <div class="text-h4">Preços</div>
        <div class="text-subtitle1 text-grey-6">Gerencie os preços dos itens</div>
      </div>
      <div class="col-auto q-gutter-sm">
        <q-btn
          color="positive"
          icon="file_upload"
          label="Importar Excel"
          @click="openImportDialog"
        />
        <q-btn
          color="primary"
          icon="add"
          label="Novo Preço"
          @click="openDialog()"
        />
      </div>
    </div>

    <!-- Filtros Avançados -->
    <q-card class="q-mb-md">
      <q-card-section>
        <div class="text-h6 q-mb-md">Busca Avançada</div>
        <div class="row q-gutter-md">
          <div class="col-12 col-md-3">
            <q-input
              dense
              debounce="300"
              v-model="filters.search"
              placeholder="Nome do item..."
              @update:model-value="onSearch"
            >
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
          </div>
          
          <div class="col-12 col-md-2">
            <q-select
              v-model="filters.category_id"
              :options="categoryOptions"
              label="Categoria"
              dense
              emit-value
              map-options
              clearable
              @update:model-value="onSearch"
            />
          </div>

          <div class="col-12 col-md-2">
            <q-select
              v-model="filters.supplier_id"
              :options="supplierOptions"
              label="Fornecedor"
              dense
              emit-value
              map-options
              clearable
              @update:model-value="onSearch"
            />
          </div>

          <div class="col-12 col-md-2">
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

          <div class="col-12 col-md-2">
            <q-input
              v-model="filters.date_from"
              label="Data início"
              type="date"
              dense
              @update:model-value="onSearch"
            />
          </div>

          <div class="col-12 col-md-1">
            <q-input
              v-model="filters.date_to"
              label="Data fim"
              type="date"
              dense
              @update:model-value="onSearch"
            />
          </div>
        </div>
      </q-card-section>
    </q-card>

    <q-card>
      <q-card-section>
        <q-table
          :rows="prices"
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
                :color="props.value ? 'positive' : 'warning'" 
                text-color="white" 
                size="sm"
              >
                {{ props.value ? 'Aprovado' : 'Pendente' }}
              </q-chip>
            </q-td>
          </template>

          <template v-slot:body-cell-actions="props">
            <q-td :props="props">
              <q-btn
                flat
                round
                color="info"
                icon="history"
                size="sm"
                @click="viewHistory(props.row.item_id)"
              >
                <q-tooltip>Histórico</q-tooltip>
              </q-btn>
              <q-btn
                flat
                round
                color="primary"
                icon="edit"
                size="sm"
                @click="openDialog(props.row)"
              >
                <q-tooltip>Editar</q-tooltip>
              </q-btn>
              <q-btn
                flat
                round
                color="negative"
                icon="delete"
                size="sm"
                @click="deletePrice(props.row)"
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
          <div class="text-h6">{{ isEdit ? 'Editar' : 'Novo' }} Preço</div>
        </q-card-section>

        <q-card-section>
          <q-form @submit="savePrice" class="q-gutter-md">
            <div class="row q-gutter-md">
              <div class="col">
                <q-select
                  v-model="form.item_id"
                  :options="itemOptions"
                  label="Item *"
                  outlined
                  emit-value
                  map-options
                  use-input
                  @filter="filterItems"
                  :rules="[val => !!val || 'Item é obrigatório']"
                />
              </div>
              <div class="col">
                <q-select
                  v-model="form.supplier_id"
                  :options="supplierOptions"
                  label="Fornecedor *"
                  outlined
                  emit-value
                  map-options
                  :rules="[val => !!val || 'Fornecedor é obrigatório']"
                />
              </div>
            </div>

            <div class="row q-gutter-md">
              <div class="col">
                <q-input
                  v-model="form.price"
                  label="Preço *"
                  type="number"
                  step="0.01"
                  min="0"
                  outlined
                  prefix="R$"
                  :rules="[val => !!val || 'Preço é obrigatório']"
                />
              </div>
              <div class="col">
                <q-input
                  v-model="form.effective_date"
                  label="Data de Vigência"
                  type="date"
                  outlined
                />
              </div>
            </div>

            <q-checkbox
              v-model="form.approved"
              label="Preço aprovado"
            />

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
            @click="savePrice"
            :loading="saving"
            :disable="!form.item_id || !form.supplier_id || !form.price"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <!-- Dialog Histórico -->
    <q-dialog v-model="historyDialog" maximized>
      <q-card>
        <q-card-section>
          <div class="text-h6">Histórico de Preços</div>
        </q-card-section>

        <q-card-section>
          <q-table
            :rows="priceHistory"
            :columns="historyColumns"
            row-key="id"
            :loading="historyLoading"
            flat
          />
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Fechar" color="primary" @click="historyDialog = false" />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <!-- Dialog Importação -->
    <q-dialog v-model="importDialog" persistent>
      <q-card style="min-width: 500px">
        <q-card-section>
          <div class="text-h6">Importar Preços via Excel</div>
        </q-card-section>

        <q-card-section>
          <q-file
            v-model="importFile"
            label="Selecione o arquivo Excel"
            accept=".xlsx,.xls"
            outlined
            @update:model-value="onFileSelected"
          >
            <template v-slot:prepend>
              <q-icon name="attach_file" />
            </template>
          </q-file>
          
          <div class="q-mt-md text-caption text-grey-6">
            Formatos aceitos: .xlsx, .xls<br>
            Colunas esperadas: Nome do Item, Fornecedor, Preço, Data de Vigência
          </div>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Cancelar" color="primary" @click="closeImportDialog" />
          <q-btn 
            label="Importar" 
            color="primary" 
            @click="importPrices"
            :loading="importing"
            :disable="!importFile"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue'
import { api } from '../boot/axios'
import { useQuasar } from 'quasar'

export default defineComponent({
  name: 'PricesPage',

  setup() {
    const $q = useQuasar()
    const prices = ref([])
    const priceHistory = ref([])
    const loading = ref(false)
    const historyLoading = ref(false)
    const saving = ref(false)
    const importing = ref(false)
    const dialog = ref(false)
    const historyDialog = ref(false)
    const importDialog = ref(false)
    const isEdit = ref(false)
    const importFile = ref(null)

    // Options
    const categoryOptions = ref([])
    const supplierOptions = ref([])
    const itemOptions = ref([])

    const filters = ref({
      search: '',
      category_id: null,
      supplier_id: null,
      status: null,
      date_from: '',
      date_to: ''
    })

    const pagination = ref({
      sortBy: 'created_at',
      descending: true,
      page: 1,
      rowsPerPage: 15,
      rowsNumber: 0
    })

    const form = ref({
      item_id: null,
      supplier_id: null,
      price: '',
      effective_date: '',
      approved: false,
      notes: ''
    })

    const statusOptions = [
      { label: 'Aprovado', value: true },
      { label: 'Pendente', value: false }
    ]

    const columns = [
      {
        name: 'item_name',
        label: 'Item',
        align: 'left',
        field: row => row.item?.name || '-',
        sortable: true
      },
      {
        name: 'category',
        label: 'Categoria',
        align: 'left',
        field: row => row.item?.category?.name || '-'
      },
      {
        name: 'supplier_name',
        label: 'Fornecedor',
        align: 'left',
        field: row => row.supplier?.name || '-',
        sortable: true
      },
      {
        name: 'price',
        label: 'Preço',
        align: 'right',
        field: 'price',
        format: val => `R$ ${parseFloat(val || 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`,
        sortable: true
      },
      {
        name: 'effective_date',
        label: 'Data de Vigência',
        align: 'left',
        field: 'effective_date',
        format: val => val ? new Date(val).toLocaleDateString('pt-BR') : '-',
        sortable: true
      },
      {
        name: 'status',
        label: 'Status',
        align: 'center',
        field: 'approved'
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

    const historyColumns = [
      {
        name: 'price',
        label: 'Preço',
        align: 'right',
        field: 'price',
        format: val => `R$ ${parseFloat(val || 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`
      },
      {
        name: 'supplier_name',
        label: 'Fornecedor',
        align: 'left',
        field: row => row.supplier?.name || '-'
      },
      {
        name: 'effective_date',
        label: 'Data de Vigência',
        align: 'left',
        field: 'effective_date',
        format: val => val ? new Date(val).toLocaleDateString('pt-BR') : '-'
      },
      {
        name: 'approved',
        label: 'Status',
        align: 'center',
        field: 'approved',
        format: val => val ? 'Aprovado' : 'Pendente'
      },
      {
        name: 'created_at',
        label: 'Criado em',
        align: 'left',
        field: 'created_at',
        format: val => new Date(val).toLocaleDateString('pt-BR')
      }
    ]

    const fetchPrices = async (props = {}) => {
      loading.value = true
      try {
        const { page = 1, rowsPerPage = 15, sortBy, descending } = props.pagination || pagination.value
        
        const params = {
          page,
          per_page: rowsPerPage,
          search: filters.value.search,
          category_id: filters.value.category_id,
          supplier_id: filters.value.supplier_id,
          approved: filters.value.status,
          date_from: filters.value.date_from,
          date_to: filters.value.date_to
        }

        if (sortBy) {
          params.orderBy = sortBy
          params.orderDirection = descending ? 'desc' : 'asc'
        }

        const response = await api.get('/prices/search', { params })
        
        prices.value = response.data.data
        pagination.value.rowsNumber = response.data.total
        pagination.value.page = response.data.current_page
        pagination.value.rowsPerPage = response.data.per_page
        
        if (props.pagination) {
          pagination.value.sortBy = props.pagination.sortBy
          pagination.value.descending = props.pagination.descending
        }
      } catch (error) {
        console.error('Erro ao buscar preços:', error)
        $q.notify({
          type: 'negative',
          message: 'Erro ao carregar preços'
        })
      } finally {
        loading.value = false
      }
    }

    const fetchOptions = async () => {
      try {
        const [categoriesRes, suppliersRes, itemsRes] = await Promise.all([
          api.get('/categories'),
          api.get('/suppliers'),
          api.get('/items')
        ])

        categoryOptions.value = categoriesRes.data.data?.map(cat => ({
          label: cat.name,
          value: cat.id
        })) || []

        supplierOptions.value = suppliersRes.data.data?.map(sup => ({
          label: sup.name,
          value: sup.id
        })) || []

        itemOptions.value = itemsRes.data.data?.map(item => ({
          label: item.name,
          value: item.id
        })) || []
      } catch (error) {
        console.error('Erro ao carregar opções:', error)
      }
    }

    const onRequest = (props) => {
      fetchPrices(props)
    }

    const onSearch = () => {
      pagination.value.page = 1
      fetchPrices()
    }

    const filterItems = (val, update) => {
      update(() => {
        if (val === '') {
          fetchOptions()
        } else {
          const needle = val.toLowerCase()
          itemOptions.value = itemOptions.value.filter(
            item => item.label.toLowerCase().indexOf(needle) > -1
          )
        }
      })
    }

    const openDialog = (price = null) => {
      isEdit.value = !!price
      form.value = price ? { ...price } : {
        item_id: null,
        supplier_id: null,
        price: '',
        effective_date: '',
        approved: false,
        notes: ''
      }
      dialog.value = true
    }

    const closeDialog = () => {
      dialog.value = false
    }

    const savePrice = async () => {
      saving.value = true
      try {
        if (isEdit.value) {
          await api.put(`/prices/${form.value.id}`, form.value)
          $q.notify({
            type: 'positive',
            message: 'Preço atualizado com sucesso!'
          })
        } else {
          await api.post('/prices', form.value)
          $q.notify({
            type: 'positive',
            message: 'Preço criado com sucesso!'
          })
        }
        
        closeDialog()
        fetchPrices()
      } catch (error) {
        console.error('Erro ao salvar preço:', error)
        $q.notify({
          type: 'negative',
          message: error.response?.data?.message || 'Erro ao salvar preço'
        })
      } finally {
        saving.value = false
      }
    }

    const deletePrice = (price) => {
      $q.dialog({
        title: 'Confirmar exclusão',
        message: `Deseja excluir o preço de "${price.item?.name}"?`,
        cancel: true,
        persistent: true
      }).onOk(async () => {
        try {
          await api.delete(`/prices/${price.id}`)
          $q.notify({
            type: 'positive',
            message: 'Preço excluído com sucesso!'
          })
          fetchPrices()
        } catch (error) {
          console.error('Erro ao excluir preço:', error)
          $q.notify({
            type: 'negative',
            message: error.response?.data?.message || 'Erro ao excluir preço'
          })
        }
      })
    }

    const viewHistory = async (itemId) => {
      historyLoading.value = true
      historyDialog.value = true
      
      try {
        const response = await api.get(`/prices/history/${itemId}`)
        priceHistory.value = response.data
      } catch (error) {
        console.error('Erro ao buscar histórico:', error)
        $q.notify({
          type: 'negative',
          message: 'Erro ao carregar histórico de preços'
        })
      } finally {
        historyLoading.value = false
      }
    }

    const openImportDialog = () => {
      importDialog.value = true
    }

    const closeImportDialog = () => {
      importDialog.value = false
      importFile.value = null
    }

    const onFileSelected = (file) => {
      console.log('Arquivo selecionado:', file)
    }

    const importPrices = async () => {
      if (!importFile.value) return

      importing.value = true
      try {
        const formData = new FormData()
        formData.append('file', importFile.value)

        await api.post('/prices/import', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })

        $q.notify({
          type: 'positive',
          message: 'Preços importados com sucesso!'
        })
        
        closeImportDialog()
        fetchPrices()
      } catch (error) {
        console.error('Erro ao importar preços:', error)
        $q.notify({
          type: 'negative',
          message: error.response?.data?.message || 'Erro ao importar preços'
        })
      } finally {
        importing.value = false
      }
    }

    onMounted(() => {
      fetchPrices()
      fetchOptions()
    })

    return {
      prices,
      priceHistory,
      columns,
      historyColumns,
      loading,
      historyLoading,
      saving,
      importing,
      dialog,
      historyDialog,
      importDialog,
      isEdit,
      filters,
      pagination,
      form,
      importFile,
      statusOptions,
      categoryOptions,
      supplierOptions,
      itemOptions,
      onRequest,
      onSearch,
      filterItems,
      openDialog,
      closeDialog,
      savePrice,
      deletePrice,
      viewHistory,
      openImportDialog,
      closeImportDialog,
      onFileSelected,
      importPrices
    }
  }
})
</script>
