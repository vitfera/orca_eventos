<template>
  <q-page class="q-pa-md">
    <div class="row q-mb-md">
      <div class="col">
        <div class="text-h4">Categorias</div>
        <div class="text-subtitle1 text-grey-6">Gerencie as categorias de itens</div>
      </div>
      <div class="col-auto">
        <q-btn
          color="primary"
          icon="add"
          label="Nova Categoria"
          @click="openDialog()"
        />
      </div>
    </div>

    <q-card>
      <q-card-section>
        <q-table
          :rows="categories"
          :columns="columns"
          row-key="id"
          :loading="loading"
          :pagination="pagination"
          @request="onRequest"
          binary-state-sort
        >
          <template v-slot:top-right>
            <q-input
              dense
              debounce="300"
              v-model="filter"
              placeholder="Buscar categoria..."
              @update:model-value="onSearch"
            >
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
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
                color="negative"
                icon="delete"
                size="sm"
                @click="deleteCategory(props.row)"
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
      <q-card style="min-width: 400px">
        <q-card-section>
          <div class="text-h6">{{ isEdit ? 'Editar' : 'Nova' }} Categoria</div>
        </q-card-section>

        <q-card-section>
          <q-form @submit="saveCategory" class="q-gutter-md">
            <q-input
              v-model="form.name"
              label="Nome *"
              outlined
              :rules="[val => !!val || 'Nome é obrigatório']"
            />

            <q-input
              v-model="form.description"
              label="Descrição"
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
            @click="saveCategory"
            :loading="saving"
            :disable="!form.name"
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
  name: 'CategoriesPage',

  setup() {
    const $q = useQuasar()
    const categories = ref([])
    const loading = ref(false)
    const saving = ref(false)
    const dialog = ref(false)
    const isEdit = ref(false)
    const filter = ref('')

    const pagination = ref({
      sortBy: 'name',
      descending: false,
      page: 1,
      rowsPerPage: 10,
      rowsNumber: 0
    })

    const form = ref({
      name: '',
      description: ''
    })

    const columns = [
      {
        name: 'name',
        label: 'Nome',
        align: 'left',
        field: 'name',
        sortable: true
      },
      {
        name: 'description',
        label: 'Descrição',
        align: 'left',
        field: 'description'
      },
      {
        name: 'items_count',
        label: 'Itens',
        align: 'center',
        field: 'items_count',
        sortable: true
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

    const fetchCategories = async (props = {}) => {
      loading.value = true
      try {
        const { page = 1, rowsPerPage = 10, sortBy, descending } = props.pagination || pagination.value
        
        const params = {
          page,
          per_page: rowsPerPage,
          search: filter.value
        }

        if (sortBy) {
          params.orderBy = sortBy
          params.orderDirection = descending ? 'desc' : 'asc'
        }

        const response = await api.get('/categories', { params })
        
        categories.value = response.data.data
        pagination.value.rowsNumber = response.data.total
        pagination.value.page = response.data.current_page
        pagination.value.rowsPerPage = response.data.per_page
        
        if (props.pagination) {
          pagination.value.sortBy = props.pagination.sortBy
          pagination.value.descending = props.pagination.descending
        }
      } catch (error) {
        console.error('Erro ao buscar categorias:', error)
        $q.notify({
          type: 'negative',
          message: 'Erro ao carregar categorias'
        })
      } finally {
        loading.value = false
      }
    }

    const onRequest = (props) => {
      fetchCategories(props)
    }

    const onSearch = () => {
      pagination.value.page = 1
      fetchCategories()
    }

    const openDialog = (category = null) => {
      isEdit.value = !!category
      form.value = category ? { ...category } : { name: '', description: '' }
      dialog.value = true
    }

    const closeDialog = () => {
      dialog.value = false
      form.value = { name: '', description: '' }
    }

    const saveCategory = async () => {
      saving.value = true
      try {
        if (isEdit.value) {
          await api.put(`/categories/${form.value.id}`, form.value)
          $q.notify({
            type: 'positive',
            message: 'Categoria atualizada com sucesso!'
          })
        } else {
          await api.post('/categories', form.value)
          $q.notify({
            type: 'positive',
            message: 'Categoria criada com sucesso!'
          })
        }
        
        closeDialog()
        fetchCategories()
      } catch (error) {
        console.error('Erro ao salvar categoria:', error)
        $q.notify({
          type: 'negative',
          message: error.response?.data?.message || 'Erro ao salvar categoria'
        })
      } finally {
        saving.value = false
      }
    }

    const deleteCategory = (category) => {
      $q.dialog({
        title: 'Confirmar exclusão',
        message: `Deseja excluir a categoria "${category.name}"?`,
        cancel: true,
        persistent: true
      }).onOk(async () => {
        try {
          await api.delete(`/categories/${category.id}`)
          $q.notify({
            type: 'positive',
            message: 'Categoria excluída com sucesso!'
          })
          fetchCategories()
        } catch (error) {
          console.error('Erro ao excluir categoria:', error)
          $q.notify({
            type: 'negative',
            message: error.response?.data?.message || 'Erro ao excluir categoria'
          })
        }
      })
    }

    onMounted(() => {
      fetchCategories()
    })

    return {
      categories,
      columns,
      loading,
      saving,
      dialog,
      isEdit,
      filter,
      pagination,
      form,
      onRequest,
      onSearch,
      openDialog,
      closeDialog,
      saveCategory,
      deleteCategory
    }
  }
})
</script>
