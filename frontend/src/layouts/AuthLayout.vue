<template>
  <q-layout view="lHh lpr lFf" class="bg-grey-1">
    <q-page-container>
      <div class="fullscreen bg-primary flex flex-center">
        <q-card class="auth-card q-pa-lg" style="width: 400px; max-width: 90vw;">
          <router-view />
        </q-card>
      </div>
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'AuthLayout'
})
</script>

<style lang="sass" scoped>
.auth-card
  border-radius: 16px
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1)
</style>
