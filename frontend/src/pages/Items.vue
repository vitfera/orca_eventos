<template>
  <q-page class="q-pa-md">
    <div class="row q-mb-md">
      <div class="col">
        <div class="text-h4">Itens</div>
        <div class="text-subtitle1 text-grey-6">Gerencie o catálogo de itens</div>
      </div>
      <div class="col-auto">
        <q-btn
          color="primary"
          icon="add"
          label="Novo Item"
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
              placeholder="Buscar item..."
              @update:model-value="onSearch"
            >
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
          </div>
          
          <div class="col-12 col-md-3">
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

          <div class="col-12 col-md-3">
            <q-select
              v-model="filters.unit_id"
              :options="unitOptions"
              label="Unidade"
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
        </div>

        <q-table
          :rows="items"
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
                :color="props.value ? 'positive' : 'negative'" 
                text-color="white" 
                size="sm"
              >
                {{ props.value ? 'Ativo' : 'Inativo' }}
              </q-chip>
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
                @click="openDialog(props.row)"
              >
                <q-tooltip>Editar</q-tooltip>
              </q-btn>
              <q-btn
                flat
                round
                :color="props.row.active ? 'negative' : 'positive'"
                :icon="props.row.active ? 'block' : 'check'"
                size="sm"
                @click="toggleStatus(props.row)"
              >
                <q-tooltip>{{ props.row.active ? 'Desativar' : 'Ativar' }}</q-tooltip>
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
        </q-table>
      </q-card-section>
    </q-card>

    <!-- Dialog Form -->
    <q-dialog v-model="dialog" persistent>
      <q-card style="min-width: 600px">
        <q-card-section>
          <div class="text-h6">{{ isEdit ? 'Editar' : 'Novo' }} Item</div>
        </q-card-section>

        <q-card-section>
          <q-form @submit="saveItem" class="q-gutter-md">
            <div class="row q-gutter-md">
              <div class="col">
                <q-input
                  v-model="form.name"
                  label="Nome do Item *"
                  outlined
                  :rules="[val => !!val || 'Nome é obrigatório']"
                />
              </div>
              <div class="col">
                <q-input
                  v-model="form.code"
                  label="Código/SKU"
                  outlined
                />
              </div>
            </div>

            <div class="row q-gutter-md">
              <div class="col">
                <q-select
                  v-model="form.category_id"
                  :options="categoryOptions"
                  label="Categoria *"
                  outlined
                  emit-value
                  map-options
                  :rules="[val => !!val || 'Categoria é obrigatória']"
                />
              </div>
              <div class="col">
                <q-select
                  v-model="form.unit_id"
                  :options="unitOptions"
                  label="Unidade *"
                  outlined
                  emit-value
                  map-options
                  :rules="[val => !!val || 'Unidade é obrigatória']"
                />
              </div>
            </div>

            <q-input
              v-model="form.description"
              label="Descrição"
              type="textarea"
              outlined
              rows="3"
            />

            <div class="row q-gutter-md">
              <div class="col">
                <q-input
                  v-model="form.minimum_stock"
                  label="Estoque Mínimo"
                  type="number"
                  min="0"
                  outlined
                />
              </div>
              <div class="col">
                <q-input
                  v-model="form.current_stock"
                  label="Estoque Atual"
                  type="number"
                  min="0"
                  outlined
                />
              </div>
            </div>

            <q-checkbox
              v-model="form.active"
              label="Item ativo"
            />

            <q-input
              v-model="form.notes"
              label="Observações"
              type="textarea"
              outlined
              rows="2"
            />
          </q-form>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Cancelar" color="primary" @click="closeDialog" />
          <q-btn 
            label="Salvar" 
            color="primary" 
            @click="saveItem"
            :loading="saving"
            :disable="!form.name || !form.category_id || !form.unit_id"
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
  name: 'ItemsPage',

  setup() {
    const $q = useQuasar()
    const items = ref([])
    const loading = ref(false)
    const saving = ref(false)
    const dialog = ref(false)
    const isEdit = ref(false)

    // Options
    const categoryOptions = ref([])
    const unitOptions = ref([])

    const filters = ref({
      search: '',
      category_id: null,
      unit_id: null,
      status: null
    })

    const pagination = ref({
      sortBy: 'name',
      descending: false,
      page: 1,
      rowsPerPage: 15,
      rowsNumber: 0
    })

    const form = ref({
      name: '',
      code: '',
      description: '',
      category_id: null,
      unit_id: null,
      minimum_stock: null,
      current_stock: null,
      active: true,
      notes: ''
    })

    const statusOptions = [
      { label: 'Ativo', value: true },
      { label: 'Inativo', value: false }
    ]

    const columns = [
      {
        name: 'name',
        label: 'Nome',
        align: 'left',
        field: 'name',
        sortable: true
      },
      {
        name: 'code',
        label: 'Código',
        align: 'left',
        field: 'code',
        format: val => val || '-'
      },
      {
        name: 'category',
        label: 'Categoria',
        align: 'left',
        field: row => row.category?.name || '-'
      },
      {
        name: 'unit',
        label: 'Unidade',
        align: 'center',
        field: row => row.unit?.name || '-'
      },
      {
        name: 'current_stock',
        label: 'Estoque',
        align: 'right',
        field: 'current_stock',
        format: val => val || 0
      },
      {
        name: 'prices_count',
        label: 'Preços',
        align: 'center',
        field: 'prices_count',
        format: val => val || 0
      },
      {
        name: 'status',
        label: 'Status',
        align: 'center',
        field: 'active'
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

    const fetchItems = async (props = {}) => {
      loading.value = true
      try {
        const { page = 1, rowsPerPage = 15, sortBy, descending } = props.pagination || pagination.value
        
        const params = {
          page,
          per_page: rowsPerPage,
          search: filters.value.search,
          category_id: filters.value.category_id,
          unit_id: filters.value.unit_id,
          active: filters.value.status
        }

        if (sortBy) {
          params.orderBy = sortBy
          params.orderDirection = descending ? 'desc' : 'asc'
        }

        const response = await api.get('/items', { params })
        
        items.value = response.data.data || response.data
        
        if (response.data.total !== undefined) {
          pagination.value.rowsNumber = response.data.total
          pagination.value.page = response.data.current_page
          pagination.value.rowsPerPage = response.data.per_page
        }
        
        if (props.pagination) {
          pagination.value.sortBy = props.pagination.sortBy
          pagination.value.descending = props.pagination.descending
        }
      } catch (error) {
        console.error('Erro ao buscar itens:', error)
        $q.notify({
          type: 'negative',
          message: 'Erro ao carregar itens'
        })
      } finally {
        loading.value = false
      }
    }

    const fetchOptions = async () => {
      try {
        const [categoriesRes, unitsRes] = await Promise.all([
          api.get('/categories'),
          api.get('/units')
        ])

        categoryOptions.value = categoriesRes.data.data?.map(cat => ({
          label: cat.name,
          value: cat.id
        })) || []

        unitOptions.value = unitsRes.data.data?.map(unit => ({
          label: unit.name,
          value: unit.id
        })) || []
      } catch (error) {
        console.error('Erro ao carregar opções:', error)
      }
    }

    const onRequest = (props) => {
      fetchItems(props)
    }

    const onSearch = () => {
      pagination.value.page = 1
      fetchItems()
    }

    const openDialog = (item = null) => {
      isEdit.value = !!item
      form.value = item ? { ...item } : {
        name: '',
        code: '',
        description: '',
        category_id: null,
        unit_id: null,
        minimum_stock: null,
        current_stock: null,
        active: true,
        notes: ''
      }
      dialog.value = true
    }

    const closeDialog = () => {
      dialog.value = false
    }

    const saveItem = async () => {
      saving.value = true
      try {
        if (isEdit.value) {
          await api.put(`/items/${form.value.id}`, form.value)
          $q.notify({
            type: 'positive',
            message: 'Item atualizado com sucesso!'
          })
        } else {
          await api.post('/items', form.value)
          $q.notify({
            type: 'positive',
            message: 'Item criado com sucesso!'
          })
        }
        
        closeDialog()
        fetchItems()
      } catch (error) {
        console.error('Erro ao salvar item:', error)
        $q.notify({
          type: 'negative',
          message: error.response?.data?.message || 'Erro ao salvar item'
        })
      } finally {
        saving.value = false
      }
    }

    const toggleStatus = async (item) => {
      try {
        await api.put(`/items/${item.id}`, {
          ...item,
          active: !item.active
        })
        
        $q.notify({
          type: 'positive',
          message: `Item ${item.active ? 'desativado' : 'ativado'} com sucesso!`
        })
        
        fetchItems()
      } catch (error) {
        console.error('Erro ao alterar status:', error)
        $q.notify({
          type: 'negative',
          message: 'Erro ao alterar status do item'
        })
      }
    }

    const deleteItem = (item) => {
      $q.dialog({
        title: 'Confirmar exclusão',
        message: `Deseja excluir o item "${item.name}"?`,
        cancel: true,
        persistent: true
      }).onOk(async () => {
        try {
          await api.delete(`/items/${item.id}`)
          $q.notify({
            type: 'positive',
            message: 'Item excluído com sucesso!'
          })
          fetchItems()
        } catch (error) {
          console.error('Erro ao excluir item:', error)
          $q.notify({
            type: 'negative',
            message: error.response?.data?.message || 'Erro ao excluir item'
          })
        }
      })
    }

    onMounted(() => {
      fetchItems()
      fetchOptions()
    })

    return {
      items,
      columns,
      loading,
      saving,
      dialog,
      isEdit,
      filters,
      pagination,
      form,
      statusOptions,
      categoryOptions,
      unitOptions,
      onRequest,
      onSearch,
      openDialog,
      closeDialog,
      saveItem,
      toggleStatus,
      deleteItem
    }
  }
})
</script>
