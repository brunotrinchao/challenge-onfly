<template>
  <q-dialog ref="dialog" @hide="onDialogHide">
    <q-card class="q-dialog-plugin" style="width: 700px; max-width: 80vw">
      <q-card-section>
        <div class="text-h6">
          {{ titulo }}
          <q-btn
            class="float-right"
            flat
            round
            style="color: #000"
            icon="close"
            @click="onCancelClick"
          />
        </div>
      </q-card-section>

      <q-separator />

      <q-card-section>
        <q-form ref="formExpense">
          <q-card-section class="q-pt-none full-width">
            <div class="q-gutter-md row items-start">
              <div class="col">
                <q-input
                  v-model="form.date"
                  filled
                  type="date"
                  label="Data"
                  class="q-mb-md"
                />
              </div>
              <div class="col">
                <q-input
                  filled
                  v-model="form.amount"
                  label="Valor"
                  prefix="R$"
                  type="number"
                  class="q-mb-md"
                />
              </div>
            </div>
            <q-input
              v-model="form.description"
              label="Descrição"
              filled
              type="textarea"
              class="q-mb-md"
            />
          </q-card-section>
        </q-form>
      </q-card-section>

      <q-card-actions align="right">
        <q-btn color="red" label="Excluir" @click="onDeleteClick" />
        <q-btn color="secondary" label="Salvar" @click="onOKClick" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script src="./form.js" />

<style src="./form.scss" lang="scss" scoped />
