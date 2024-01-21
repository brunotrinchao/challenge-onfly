<template>
  <q-page class="q-pa-lg">
    <div class="text-h5 q-mb-md text-weight-light row justify-end">
      <q-btn
        color="primary"
        label="Adicionar"
        icon="add"
        @click="btnAddExpense"
      />
    </div>
    <q-table
      title="Despesas"
      :data="data"
      :columns="columns"
      row-key="id"
      no-data-label="Nenhum registro encontrado."
      :hide-pagination="false"
      :rows-per-page-options="[0]"
      @row-click="onRowClick"
      :pagination.sync="pagination"
      :loading="loading"
      @request="onRequest"
    >
    </q-table>
  </q-page>
</template>

<script src="./expenses.js" />

<style src="./expenses.scss" lang="scss" scoped />
