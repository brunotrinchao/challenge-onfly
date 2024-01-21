<template>
  <q-page class="flex flex-center bg-primary">
    <q-card id="card-signup">
      <q-card-section>
        <img src="../../assets/onfly-logo.png" ratio="1" width="200px" />
      </q-card-section>
      <q-card-section class="q-pt-none">
        <div class="text-h5">Novo usuário</div>
      </q-card-section>
      <q-form ref="formSignup" class="q-gutter-md full-width">
        <q-card-section class="q-pt-none full-width">
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
            :rules="[validator.requiredField, validator.validateEmail]"
          />
          <q-input
            class="q-mt-sm"
            filled
            v-model="form.password"
            label="Senha"
            :rules="[validator.requiredField]"
          />
        </q-card-section>
        <q-card-actions class="full-width q-pa-sm">
          <q-btn
            class="q-ms-md full-width"
            color="primary"
            label="Cadastrar"
            @click="onValidate()"
          />
        </q-card-actions>
        <q-card-actions class="full-width">
          <q-btn
            flat
            no-caps
            class="q-ms-md full-width"
            color="primary"
            label="Login"
            @click="btnLogin()"
          />
        </q-card-actions>
      </q-form>
    </q-card>
  </q-page>
</template>

<script src="./signup" />

<style src="./signup.scss" lang="scss" scoped />
