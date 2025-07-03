<template>
  <q-page class="flex flex-center column q-pa-xl">
    <div class="text-h2 text-primary q-mb-md">Bem-vindo ao OrçaEventos!</div>
    <div class="text-subtitle1 q-mb-lg">Gestão de orçamentos para eventos de forma simples e eficiente.</div>
    <q-separator class="q-my-md" />
    <div class="row q-col-gutter-xl q-mb-xl">
      <div class="col-12 col-md-4">
        <q-card flat bordered>
          <q-card-section>
            <div class="text-h6">Serviços</div>
            <div class="text-body2">Controle de orçamentos, fornecedores, itens, preços e muito mais.</div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-md-4">
        <q-card flat bordered>
          <q-card-section>
            <div class="text-h6">Planos</div>
            <div class="text-body2">Escolha o plano ideal para o seu negócio, com recursos sob medida.</div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-md-4">
        <q-card flat bordered>
          <q-card-section>
            <div class="text-h6">Resumo</div>
            <div class="text-body2">Veja estatísticas e resultados dos seus eventos em tempo real.</div>
          </q-card-section>
        </q-card>
      </div>
    </div>
    <q-btn color="primary" label="Entrar" to="/auth/login" />
  </q-page>
</template>

<script setup>
// Página inicial pública
</script>
