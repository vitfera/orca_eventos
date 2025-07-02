<template>
  <q-layout view="lHh lpr lFf">
    <q-header elevated class="bg-primary text-white">
      <q-toolbar>
        <q-toolbar-title class="flex items-center">
          <q-icon name="event" size="sm" class="q-mr-sm" />
          OrçaEventos - Visualização Pública
        </q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'PublicLayout'
})
</script>
