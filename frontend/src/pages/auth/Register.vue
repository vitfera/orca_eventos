<template>
  <q-page class="flex flex-center bg-grey-2">
    <q-card class="q-pa-xl" style="min-width:340px; max-width:400px; width:100%;">
      <q-card-section class="text-center">
        <q-icon name="person_add" size="4rem" color="primary" />
        <div class="text-h4 text-primary q-mt-md">Criar Conta</div>
        <div class="text-subtitle1 text-grey-6">Preencha os dados para se cadastrar</div>
      </q-card-section>
      <q-card-section>
        <q-form @submit="handleRegister" class="q-gutter-md">
          <q-input
            v-model="form.name"
            label="Nome"
            outlined
            :rules="[val => !!val || 'Nome é obrigatório']"
          >
            <template v-slot:prepend>
              <q-icon name="person" />
            </template>
          </q-input>
          <q-input
            v-model="form.email"
            label="E-mail"
            type="email"
            outlined
            :rules="[val => !!val || 'E-mail é obrigatório']"
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
          <q-input
            v-model="form.password_confirmation"
            label="Confirmar Senha"
            :type="showPassword ? 'text' : 'password'"
            outlined
            :rules="[val => !!val || 'Confirmação obrigatória']"
          >
            <template v-slot:prepend>
              <q-icon name="lock" />
            </template>
          </q-input>
          <div class="row q-mt-lg">
            <div class="col">
              <q-btn
                type="submit"
                color="primary"
                label="Cadastrar"
                class="full-width"
                :loading="loading"
                :disable="!form.name || !form.email || !form.password || !form.password_confirmation"
              />
            </div>
          </div>
        </q-form>
      </q-card-section>
      <q-card-section class="text-center">
        <q-btn flat color="primary" to="/auth/login" label="Já tenho conta" />
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useQuasar } from 'quasar'

const $q = useQuasar()
const router = useRouter()

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})
const showPassword = ref(false)
const loading = ref(false)

function handleRegister() {
  loading.value = true
  // Aqui você pode implementar a chamada para a API de cadastro
  setTimeout(() => {
    loading.value = false
    $q.notify({ type: 'positive', message: 'Cadastro realizado!' })
    router.push({ name: 'Login' })
  }, 1200)
}
</script>

<style lang="sass" scoped>
.q-card
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15)
</style>
