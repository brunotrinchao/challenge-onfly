<template>
  <q-dialog ref="dialog" @hide="onDialogHide">
    <q-card class="q-dialog-plugin" style="width: 450px; max-width: 80vw">
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
        <q-form ref="formUser">
          <q-card-section class="q-pt-none full-width">
            <div class="q-gutter-md items-start">
              <q-input
                filled
                v-model="form.name"
                label="Nome"
                :rules="[validator.requiredField]"
              />
              <q-input
                class="q-mt-sm"
                filled
                v-model="form.email"
                label="E-mail"
                :readonly="disableInput"
                :rules="[validator.requiredField, validator.validateEmail]"
              />

              <q-input
                class="q-mt-sm"
                filled
                v-model="form.password"
                label="Senha"
                :rules="[validator.requiredField]"
              />
            </div>
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
