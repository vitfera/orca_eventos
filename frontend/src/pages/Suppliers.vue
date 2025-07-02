<template>
  <q-page class="q-pa-md">
    <div class="row q-mb-md">
      <div class="col">
        <div class="text-h4">Fornecedores</div>
        <div class="text-subtitle1 text-grey-6">Gerencie os fornecedores</div>
      </div>
      <div class="col-auto">
        <q-btn
          color="primary"
          icon="add"
          label="Novo Fornecedor"
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
              placeholder="Buscar fornecedor..."
              @update:model-value="onSearch"
            >
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
          </div>
        </div>

        <q-table
          :rows="suppliers"
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
                @click="deleteSupplier(props.row)"
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
          <div class="text-h6">{{ isEdit ? 'Editar' : 'Novo' }} Fornecedor</div>
        </q-card-section>

        <q-card-section>
          <q-form @submit="saveSupplier" class="q-gutter-md">
            <div class="row q-gutter-md">
              <div class="col">
                <q-input
                  v-model="form.name"
                  label="Nome do Fornecedor *"
                  outlined
                  :rules="[val => !!val || 'Nome é obrigatório']"
                />
              </div>
              <div class="col">
                <q-input
                  v-model="form.company_name"
                  label="Razão Social"
                  outlined
                />
              </div>
            </div>

            <div class="row q-gutter-md">
              <div class="col">
                <q-input
                  v-model="form.document"
                  label="CNPJ/CPF"
                  outlined
                />
              </div>
              <div class="col">
                <q-input
                  v-model="form.phone"
                  label="Telefone"
                  outlined
                />
              </div>
            </div>

            <div class="row q-gutter-md">
              <div class="col">
                <q-input
                  v-model="form.email"
                  label="E-mail"
                  type="email"
                  outlined
                />
              </div>
              <div class="col">
                <q-input
                  v-model="form.contact_person"
                  label="Pessoa de Contato"
                  outlined
                />
              </div>
            </div>

            <q-input
              v-model="form.address"
              label="Endereço"
              outlined
            />

            <div class="row q-gutter-md">
              <div class="col">
                <q-input
                  v-model="form.city"
                  label="Cidade"
                  outlined
                />
              </div>
              <div class="col">
                <q-input
                  v-model="form.state"
                  label="Estado"
                  outlined
                />
              </div>
              <div class="col">
                <q-input
                  v-model="form.zip_code"
                  label="CEP"
                  outlined
                />
              </div>
            </div>

            <q-checkbox
              v-model="form.active"
              label="Fornecedor ativo"
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
            @click="saveSupplier"
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
  name: 'SuppliersPage',

  setup() {
    const $q = useQuasar()
    const suppliers = ref([])
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
      company_name: '',
      document: '',
      phone: '',
      email: '',
      contact_person: '',
      address: '',
      city: '',
      state: '',
      zip_code: '',
      active: true,
      notes: ''
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
        name: 'company_name',
        label: 'Razão Social',
        align: 'left',
        field: 'company_name',
        format: val => val || '-'
      },
      {
        name: 'document',
        label: 'CNPJ/CPF',
        align: 'left',
        field: 'document',
        format: val => val || '-'
      },
      {
        name: 'phone',
        label: 'Telefone',
        align: 'left',
        field: 'phone',
        format: val => val || '-'
      },
      {
        name: 'email',
        label: 'E-mail',
        align: 'left',
        field: 'email',
        format: val => val || '-'
      },
      {
        name: 'city',
        label: 'Cidade',
        align: 'left',
        field: 'city',
        format: val => val || '-'
      },
      {
        name: 'status',
        label: 'Status',
        align: 'center',
        field: 'active'
      },
      {
        name: 'actions',
        label: 'Ações',
        align: 'center'
      }
    ]

    const fetchSuppliers = async (props = {}) => {
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

        const response = await api.get('/suppliers', { params })
        
        suppliers.value = response.data.data || response.data
        
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
        console.error('Erro ao buscar fornecedores:', error)
        $q.notify({
          type: 'negative',
          message: 'Erro ao carregar fornecedores'
        })
      } finally {
        loading.value = false
      }
    }

    const onRequest = (props) => {
      fetchSuppliers(props)
    }

    const onSearch = () => {
      pagination.value.page = 1
      fetchSuppliers()
    }

    const openDialog = (supplier = null) => {
      isEdit.value = !!supplier
      form.value = supplier ? { ...supplier } : {
        name: '',
        company_name: '',
        document: '',
        phone: '',
        email: '',
        contact_person: '',
        address: '',
        city: '',
        state: '',
        zip_code: '',
        active: true,
        notes: ''
      }
      dialog.value = true
    }

    const closeDialog = () => {
      dialog.value = false
    }

    const saveSupplier = async () => {
      saving.value = true
      try {
        if (isEdit.value) {
          await api.put(`/suppliers/${form.value.id}`, form.value)
          $q.notify({
            type: 'positive',
            message: 'Fornecedor atualizado com sucesso!'
          })
        } else {
          await api.post('/suppliers', form.value)
          $q.notify({
            type: 'positive',
            message: 'Fornecedor criado com sucesso!'
          })
        }
        
        closeDialog()
        fetchSuppliers()
      } catch (error) {
        console.error('Erro ao salvar fornecedor:', error)
        $q.notify({
          type: 'negative',
          message: error.response?.data?.message || 'Erro ao salvar fornecedor'
        })
      } finally {
        saving.value = false
      }
    }

    const toggleStatus = async (supplier) => {
      try {
        await api.put(`/suppliers/${supplier.id}`, {
          ...supplier,
          active: !supplier.active
        })
        
        $q.notify({
          type: 'positive',
          message: `Fornecedor ${supplier.active ? 'desativado' : 'ativado'} com sucesso!`
        })
        
        fetchSuppliers()
      } catch (error) {
        console.error('Erro ao alterar status:', error)
        $q.notify({
          type: 'negative',
          message: 'Erro ao alterar status do fornecedor'
        })
      }
    }

    const deleteSupplier = (supplier) => {
      $q.dialog({
        title: 'Confirmar exclusão',
        message: `Deseja excluir o fornecedor "${supplier.name}"?`,
        cancel: true,
        persistent: true
      }).onOk(async () => {
        try {
          await api.delete(`/suppliers/${supplier.id}`)
          $q.notify({
            type: 'positive',
            message: 'Fornecedor excluído com sucesso!'
          })
          fetchSuppliers()
        } catch (error) {
          console.error('Erro ao excluir fornecedor:', error)
          $q.notify({
            type: 'negative',
            message: error.response?.data?.message || 'Erro ao excluir fornecedor'
          })
        }
      })
    }

    onMounted(() => {
      fetchSuppliers()
    })

    return {
      suppliers,
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
      saveSupplier,
      toggleStatus,
      deleteSupplier
    }
  }
})
</script>
