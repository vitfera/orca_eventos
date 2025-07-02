<template>
  <q-page class="q-pa-md">
    <div class="row q-mb-md">
      <div class="col">
        <div class="text-h4">Unidades</div>
        <div class="text-subtitle1 text-grey-6">Gerencie as unidades de medida</div>
      </div>
      <div class="col-auto">
        <q-btn
          color="primary"
          icon="add"
          label="Nova Unidade"
          @click="openDialog()"
        />
      </div>
    </div>

    <q-card>
      <q-card-section>
        <div class="row q-mb-md">
          <div class="col-12 col-md-6">
            <q-input
              dense
              debounce="300"
              v-model="search"
              placeholder="Buscar unidade..."
              @update:model-value="onSearch"
            >
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
          </div>
        </div>

        <q-table
          :rows="units"
          :columns="columns"
          row-key="id"
          :loading="loading"
          :pagination="pagination"
          @request="onRequest"
          binary-state-sort
        >
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
                @click="deleteUnit(props.row)"
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
          <div class="text-h6">{{ isEdit ? 'Editar' : 'Nova' }} Unidade</div>
        </q-card-section>

        <q-card-section>
          <q-form @submit="saveUnit" class="q-gutter-md">
            <q-input
              v-model="form.name"
              label="Nome da Unidade *"
              outlined
              :rules="[val => !!val || 'Nome é obrigatório']"
              hint="Ex: kg, litro, unidade, metro, etc."
            />
            
            <q-input
              v-model="form.abbreviation"
              label="Abreviação"
              outlined
              hint="Ex: kg, l, un, m, etc."
            />
            
            <q-input
              v-model="form.description"
              label="Descrição"
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
            @click="saveUnit"
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
  name: 'UnitsPage',

  setup() {
    const $q = useQuasar()
    const units = ref([])
    const loading = ref(false)
    const saving = ref(false)
    const dialog = ref(false)
    const isEdit = ref(false)
    const search = ref('')

    const pagination = ref({
      sortBy: 'name',
      descending: false,
      page: 1,
      rowsPerPage: 10,
      rowsNumber: 0
    })

    const form = ref({
      name: '',
      abbreviation: '',
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
        name: 'abbreviation',
        label: 'Abreviação',
        align: 'center',
        field: 'abbreviation',
        format: val => val || '-'
      },
      {
        name: 'description',
        label: 'Descrição',
        align: 'left',
        field: 'description',
        format: val => val || '-'
      },
      {
        name: 'items_count',
        label: 'Qtd. Itens',
        align: 'center',
        field: 'items_count',
        format: val => val || 0
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

    const fetchUnits = async (props = {}) => {
      loading.value = true
      try {
        const { page = 1, rowsPerPage = 10, sortBy, descending } = props.pagination || pagination.value
        
        const params = {
          page,
          per_page: rowsPerPage,
          search: search.value
        }

        if (sortBy) {
          params.orderBy = sortBy
          params.orderDirection = descending ? 'desc' : 'asc'
        }

        const response = await api.get('/units', { params })
        
        units.value = response.data.data || response.data
        
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
        console.error('Erro ao buscar unidades:', error)
        $q.notify({
          type: 'negative',
          message: 'Erro ao carregar unidades'
        })
      } finally {
        loading.value = false
      }
    }

    const onRequest = (props) => {
      fetchUnits(props)
    }

    const onSearch = () => {
      pagination.value.page = 1
      fetchUnits()
    }

    const openDialog = (unit = null) => {
      isEdit.value = !!unit
      form.value = unit ? { ...unit } : {
        name: '',
        abbreviation: '',
        description: ''
      }
      dialog.value = true
    }

    const closeDialog = () => {
      dialog.value = false
    }

    const saveUnit = async () => {
      saving.value = true
      try {
        if (isEdit.value) {
          await api.put(`/units/${form.value.id}`, form.value)
          $q.notify({
            type: 'positive',
            message: 'Unidade atualizada com sucesso!'
          })
        } else {
          await api.post('/units', form.value)
          $q.notify({
            type: 'positive',
            message: 'Unidade criada com sucesso!'
          })
        }
        
        closeDialog()
        fetchUnits()
      } catch (error) {
        console.error('Erro ao salvar unidade:', error)
        $q.notify({
          type: 'negative',
          message: error.response?.data?.message || 'Erro ao salvar unidade'
        })
      } finally {
        saving.value = false
      }
    }

    const deleteUnit = (unit) => {
      $q.dialog({
        title: 'Confirmar exclusão',
        message: `Deseja excluir a unidade "${unit.name}"?`,
        cancel: true,
        persistent: true
      }).onOk(async () => {
        try {
          await api.delete(`/units/${unit.id}`)
          $q.notify({
            type: 'positive',
            message: 'Unidade excluída com sucesso!'
          })
          fetchUnits()
        } catch (error) {
          console.error('Erro ao excluir unidade:', error)
          $q.notify({
            type: 'negative',
            message: error.response?.data?.message || 'Erro ao excluir unidade'
          })
        }
      })
    }

    onMounted(() => {
      fetchUnits()
    })

    return {
      units,
      columns,
      loading,
      saving,
      dialog,
      isEdit,
      search,
      pagination,
      form,
      onRequest,
      onSearch,
      openDialog,
      closeDialog,
      saveUnit,
      deleteUnit
    }
  }
})
</script>
