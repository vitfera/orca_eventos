<template>
  <div>
    <q-card-section class="text-center">
      <q-icon name="event" size="4rem" color="primary" />
      <div class="text-h4 text-primary q-mt-md">OrçaEventos</div>
      <div class="text-subtitle1 text-grey-6">Faça login para continuar</div>
    </q-card-section>

    <q-card-section>
      <q-form @submit="handleLogin" class="q-gutter-md">
        <q-input
          v-model="form.email"
          label="E-mail"
          type="email"
          outlined
          :rules="[val => !!val || 'E-mail é obrigatório']"
          :error="hasError"
          error-message=""
        >
          <template v-slot:prepend>
            <q-icon name="email" />
          </template>
        </q-input>

        <q-input
          v-model="form.password"
          label="Senha"
          :type="showPassword ? 'text' : 'password'"
          outlined
          :rules="[val => !!val || 'Senha é obrigatória']"
          :error="hasError"
          :error-message="errorMessage"
        >
          <template v-slot:prepend>
            <q-icon name="lock" />
          </template>
          <template v-slot:append>
            <q-icon
              :name="showPassword ? 'visibility_off' : 'visibility'"
              class="cursor-pointer"
              @click="showPassword = !showPassword"
            />
          </template>
        </q-input>

        <div class="row q-mt-lg">
          <div class="col">
            <q-btn
              type="submit"
              color="primary"
              label="Entrar"
              class="full-width"
              :loading="loading"
              :disable="!form.email || !form.password"
            />
          </div>
        </div>
      </q-form>
    </q-card-section>

    <q-card-section class="text-center">
      <div class="text-grey-6">
        Sistema de Gestão de Orçamentos para Eventos
      </div>
    </q-card-section>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { useAuthStore } from '../../stores/auth'
import { useRouter } from 'vue-router'
import { useQuasar } from 'quasar'

export default defineComponent({
  name: 'LoginPage',

  setup() {
    const $q = useQuasar()
    const router = useRouter()
    const authStore = useAuthStore()
    
    const form = ref({
      email: '',
      password: ''
    })
    
    const showPassword = ref(false)
    const loading = ref(false)
    const hasError = ref(false)
    const errorMessage = ref('')

    const handleLogin = async () => {
      loading.value = true
      hasError.value = false
      errorMessage.value = ''

      try {
        const result = await authStore.login(form.value)
        
        if (result.success) {
          $q.notify({
            type: 'positive',
            message: 'Login realizado com sucesso!'
          })
          
          router.push({ name: 'Dashboard' })
        } else {
          hasError.value = true
          errorMessage.value = result.message
          
          $q.notify({
            type: 'negative',
            message: result.message
          })
        }
      } catch (error) {
        hasError.value = true
        errorMessage.value = 'Erro interno do servidor'
        
        $q.notify({
          type: 'negative',
          message: 'Erro ao fazer login. Tente novamente.'
        })
      } finally {
        loading.value = false
      }
    }

    return {
      form,
      showPassword,
      loading,
      hasError,
      errorMessage,
      handleLogin
    }
  }
})
</script>

<style lang="sass" scoped>
.q-card
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15)
</style>
