<template>
  <q-page class="q-pa-none bg-grey-2">
    <!-- Header -->
    <q-header elevated class="bg-white q-px-xl" height-hint="110" style="border-bottom: 1px solid #e0e0e0; min-height: 110px;">
      <q-toolbar class="q-px-none" style="min-height: 110px;">
        <q-toolbar-title class="flex items-center" style="min-width:unset;">
          <q-img src="~assets/img/logo.png" style="height: 64px; max-width: 180px; margin-right: 18px;" fit="contain" />
        </q-toolbar-title>
        <q-btn unelevated color="primary" class="q-ml-md" to="/auth/login" label="Entrar" style="min-width: 110px; font-weight: 700; font-size: 1.1rem;" />
      </q-toolbar>
    </q-header>

    <!-- Hero Section -->
    <section class="q-py-xl bg-grey-2 flex flex-center column">
      <q-card flat bordered class="q-pa-xl q-mb-xl" style="max-width: 700px; width: 100%; background: #fff9f6;">
        <div class="text-h2 text-weight-bold text-center q-mb-md text-primary">
          Simplificando Orçamentos para Eventos
        </div>
        <div class="text-subtitle1 text-center q-mb-lg">
          O OrçaEventos é um SaaS que facilita a consulta de preços e a criação de orçamentos para o setor de eventos. Cadastre-se e tenha acesso a uma base atualizada para otimizar suas contratações.
        </div>
        <div class="row justify-center">
          <q-btn outline rounded size="lg" color="primary" label="Cadastre-se" to="/auth/register" class="q-mb-md" style="width: 180px; font-size: 1.3rem;" />
        </div>
      </q-card>
    </section>

    <!-- Features Row -->
    <section class="q-px-xl q-mb-xl">
      <q-card flat bordered class="q-pa-lg bg-white">
        <q-card-section>
          <div class="row q-gutter-xl justify-around items-stretch">
            <div class="col-12 col-md-3 flex flex-center column items-center q-mb-md q-mb-md-none">
              <q-icon name="search" size="48px" color="primary" class="q-mb-sm" />
              <div class="text-h6 text-weight-bold q-mb-sm">Consulta de Preços Histórica</div>
              <div class="text-body1 text-center">Pesquise valores de itens e serviços utilizados em eventos com dados atualizados</div>
            </div>
            <div class="col-12 col-md-3 flex flex-center column items-center q-mb-md q-mb-md-none">
              <q-icon name="calculate" size="48px" color="primary" class="q-mb-sm" />
              <div class="text-h6 text-weight-bold q-mb-sm">Criação de Orçamentos</div>
              <div class="text-body1 text-center">Monte orçamentos personalizados selecionando itens diretamente na nossa plataforma</div>
            </div>
            <div class="col-12 col-md-3 flex flex-center column items-center q-mb-md q-mb-md-none">
              <q-icon name="file_upload" size="48px" color="primary" class="q-mb-sm" />
              <div class="text-h6 text-weight-bold q-mb-sm">Importação de Preços</div>
              <div class="text-body1 text-center">Atualize preços em lote de forma rápida e prática importando planilhas de custo</div>
            </div>
            <div class="col-12 col-md-3 flex flex-center column items-center">
              <q-icon name="file_download" size="48px" color="primary" class="q-mb-sm" />
              <div class="text-h6 text-weight-bold q-mb-sm">Exportação de Arquivos</div>
              <div class="text-body1 text-center">Exporte orçamentos e listas de preços para planilhas e outros formatos</div>
            </div>
          </div>
        </q-card-section>
      </q-card>
    </section>

    <!-- Banco de Preços Section -->
    <section class="q-px-xl q-mb-xl">
      <q-card flat bordered class="q-pa-xl row items-center justify-between bg-grey-2" style="min-height: 220px;">
        <div class="col-12 col-md-6">
          <div class="text-h4 text-weight-bold q-mb-sm">Banco de Preços para o Setor de Eventos</div>
          <div class="text-body1 q-mb-md">Encontre preços atualizados para serviços e produtos na área de eventos</div>
          <q-btn outline rounded color="primary" label="Começar" to="/auth/register" style="width: 140px; font-size: 1.1rem;" />
        </div>
        <div class="col-12 col-md-5 flex flex-center">
          <q-img src="https://cdn.quasar.dev/img/material.png" style="width: 220px; height: 140px; border-radius: 8px; background: #fafafa; border: 2px solid #e0e0e0;" />
        </div>
      </q-card>
    </section>

    <!-- Cards: Consulta, Histórico, Emissão -->
    <section class="q-px-xl q-mb-xl">
      <div class="row q-gutter-xl justify-center">
        <div class="col-12 col-md-3">
          <q-card flat bordered class="q-pa-lg flex flex-center column items-center">
            <q-icon name="search" size="40px" color="primary" class="q-mb-sm" />
            <div class="text-h6 text-weight-bold q-mb-xs text-center">Consulta de Preços</div>
            <div class="text-body2 text-center">Pesquise e filtre preços de diversos serviços e produtos para eventos</div>
          </q-card>
        </div>
        <div class="col-12 col-md-3">
          <q-card flat bordered class="q-pa-lg flex flex-center column items-center">
            <q-icon name="history" size="40px" color="primary" class="q-mb-sm" />
            <div class="text-h6 text-weight-bold q-mb-xs text-center">Histórico de Preços</div>
            <div class="text-body2 text-center">Acesse o histórico de preços para acompanhar as variações ao longo do tempo</div>
          </q-card>
        </div>
        <div class="col-12 col-md-3">
          <q-card flat bordered class="q-pa-lg flex flex-center column items-center">
            <q-icon name="description" size="40px" color="primary" class="q-mb-sm" />
            <div class="text-h6 text-weight-bold q-mb-xs text-center">Emissão de Orçamentos</div>
            <div class="text-body2 text-center">Crie facilmente orçamentos detalhados com base nos preços atuais</div>
          </q-card>
        </div>
      </div>
    </section>

    <!-- Otimize o Planejamento -->
    <section class="q-px-xl q-mb-xl">
      <q-card flat bordered class="q-pa-xl bg-white text-center">
        <div class="text-h4 text-weight-bold q-mb-md">Otimize o Planejamento de Eventos</div>
        <div class="text-body1" style="max-width: 700px; margin: 0 auto;">
          Tenha acesso a preços atualizados e gere orçamentos de forma rápida e eficiente utilizando OrçaEventos
        </div>
      </q-card>
    </section>

    <!-- Footer -->
    <div class="q-pa-xl bg-info" style="border-top: 1px solid #e0e0e0;">
      <div class="row justify-around items-start q-gutter-xl">
        <div class="col-12 col-md-3">
          <div class="text-h6 text-weight-bold q-mb-xs">OrçaEventos</div>
          <div class="text-body2">Recursos</div>
          <div class="text-body2">Preços</div>
        </div>
        <div class="col-12 col-md-3">
          <div class="text-h6 text-weight-bold q-mb-xs">Empresa</div>
          <div class="text-body2">Sobre nós</div>
        </div>
        <div class="col-12 col-md-3">
          <div class="text-h6 text-weight-bold q-mb-xs">Suporte</div>
          <div class="text-body2">Contato</div>
          <div class="text-body2">Ajuda</div>
        </div>
      </div>
    </div>
  </q-page>
</template>

<script setup>
// Página inicial pública
</script>
