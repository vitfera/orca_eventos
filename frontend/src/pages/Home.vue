<template>
  <q-page class="q-pa-none bg-grey-2">
    <!-- Header -->
    <div class="q-pa-md q-px-xl row items-center justify-between bg-grey-2" style="min-height: 80px;">
      <div class="text-h5 text-weight-bold">OrçaEventos</div>
      <q-btn flat class="text-h6 text-weight-bold" to="/auth/login" label="Entrar" />
    </div>

    <!-- Hero Section -->
    <div class="column flex flex-center q-py-xl bg-grey-2" style="min-height: 400px;">
      <div class="text-h2 text-weight-bold text-center q-mb-md" style="max-width: 900px; margin: 0 auto;">
        Simplificando Orçamentos para Eventos
      </div>
      <div class="text-subtitle1 text-center q-mb-xl" style="max-width: 700px; margin: 0 auto;">
        O OrçaEventós é um SaaS que facilita a consulta de preços e a criação de orçamentos para o setor de eventos. Cadastre-se e tenha acesso a uma base atualizada para otimizar suas contratações.
      </div>
      <q-btn outline rounded size="lg" color="dark" label="Cadastre-se" to="/auth/register" class="q-mb-xl" style="width: 180px; font-size: 1.3rem;" />
    </div>

    <!-- Features Row -->
    <div class="row q-pa-xl bg-white justify-around items-start q-gutter-xl" style="border-top: 1px solid #e0e0e0;">
      <div class="col-12 col-md-3 text-center">
        <div class="text-h6 text-weight-bold q-mb-sm">Consulta de Preços Histórica</div>
        <div class="text-body1">Pesquise valores de itens e serviços utilizados em eventos com dados atualizados</div>
      </div>
      <div class="col-12 col-md-3 text-center">
        <div class="text-h6 text-weight-bold q-mb-sm">Criação de Orçamentos</div>
        <div class="text-body1">Monte orçamentos personalizados selecionando itens diretamente na nossa plataforma</div>
      </div>
      <div class="col-12 col-md-3 text-center">
        <div class="text-h6 text-weight-bold q-mb-sm">Importação de Preços</div>
        <div class="text-body1">Atualize preços em lote de forma rápida e prática importando planilhas de custo</div>
      </div>
      <div class="col-12 col-md-3 text-center">
        <div class="text-h6 text-weight-bold q-mb-sm">Exportação de Arquivos</div>
        <div class="text-body1">Exporte orçamentos e listas de preços para planilhas e outros formatos</div>
      </div>
    </div>

    <!-- Banco de Preços Section -->
    <div class="row q-pa-xl items-center justify-center bg-grey-2">
      <div class="col-12 col-md-5 q-mb-lg q-mb-md-none">
        <div class="text-h3 text-weight-bold q-mb-sm">Banco de Preços para o Setor de Eventos</div>
        <div class="text-body1 q-mb-md">Encontre preços atualizados para serviços e produtos na área de eventos</div>
        <q-btn outline rounded color="dark" label="Começar" to="/auth/register" style="width: 140px; font-size: 1.1rem;" />
      </div>
      <div class="col-12 col-md-6 flex flex-center">
        <div style="width: 260px; height: 180px; background: #fafafa; border: 2px solid #e0e0e0; border-radius: 8px;"></div>
      </div>
    </div>

    <!-- Cards: Consulta, Histórico, Emissão -->
    <div class="row q-pa-xl justify-center bg-grey-2 q-gutter-xl">
      <div class="col-12 col-md-3">
        <q-card flat bordered class="q-pa-md flex flex-center column items-center">
          <div style="width: 60px; height: 60px; background: #f5f5f5; border: 1.5px solid #d0d0d0; border-radius: 8px; margin-bottom: 12px;"></div>
          <div class="text-h6 text-weight-bold q-mb-xs text-center">Consulta de Preços</div>
          <div class="text-body2 text-center">Pesquise e filtre preços de diversos serviços e produtos para eventos</div>
        </q-card>
      </div>
      <div class="col-12 col-md-3">
        <q-card flat bordered class="q-pa-md flex flex-center column items-center">
          <div style="width: 60px; height: 60px; background: #f5f5f5; border: 1.5px solid #d0d0d0; border-radius: 8px; margin-bottom: 12px;"></div>
          <div class="text-h6 text-weight-bold q-mb-xs text-center">Histórico de Preços</div>
          <div class="text-body2 text-center">Acesse o histórico de preços para acompanhar as variações ao longo do tempo</div>
        </q-card>
      </div>
      <div class="col-12 col-md-3">
        <q-card flat bordered class="q-pa-md flex flex-center column items-center">
          <div style="width: 60px; height: 60px; background: #f5f5f5; border: 1.5px solid #d0d0d0; border-radius: 8px; margin-bottom: 12px;"></div>
          <div class="text-h6 text-weight-bold q-mb-xs text-center">Emissão de Orçamentos</div>
          <div class="text-body2 text-center">Crie facilmente orçamentos detalhados com base nos preços atuais</div>
        </q-card>
      </div>
    </div>

    <!-- Otimize o Planejamento -->
    <div class="q-pa-xl bg-white text-center">
      <div class="text-h4 text-weight-bold q-mb-md">Otimize o Planejamento de Eventos</div>
      <div class="text-body1" style="max-width: 700px; margin: 0 auto;">
        Tenha acesso a preços atualizados e gere orçamentos de forma rápida e eficiente utilizando OrçaEventos
      </div>
    </div>

    <!-- Footer -->
    <div class="q-pa-xl bg-grey-2 row justify-around items-start q-gutter-xl" style="border-top: 1px solid #e0e0e0;">
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
  </q-page>
</template>

<script setup>
// Página inicial pública
</script>
