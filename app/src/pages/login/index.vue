<template>
  <q-page class="flex flex-center bg-primary">
    <q-card id="card-login">
      <q-card-section>
        <img src="../../assets/onfly-logo.png" ratio="1" width="200px" />
      </q-card-section>
      <q-form ref="formLogin" class="q-gutter-md full-width">
        <q-card-section class="q-pt-none q-ml-none">
          <q-input
            filled
            v-model="login.email"
            label="E-mail"
            :rules="[validator.requiredField, validator.validateEmail]"
          />
          <q-input
            class="q-mt-sm"
            v-model="login.password"
            lazy-rules
            label="Senha"
            filled
            :type="login.isPwd ? 'password' : 'text'"
            :rules="[validator.requiredField]"
          >
            <template v-slot:append>
              <q-icon
                :name="login.isPwd ? 'visibility_off' : 'visibility'"
                class="cursor-pointer"
                @click="login.isPwd = !login.isPwd"
              />
            </template>
          </q-input>
        </q-card-section>
        <q-card-actions class="full-width q-pa-md">
          <q-btn
            class="q-ms-md full-width"
            color="primary"
            label="Login"
            @click="onValidate()"
          />
        </q-card-actions>
        <q-card-actions class="full-width">
          <q-btn
            flat
            no-caps
            class="q-ms-md full-width"
            color="primary"
            label="Novo usuário"
            @click="btnSignup()"
          />
        </q-card-actions>
      </q-form>
    </q-card>
  </q-page>
</template>

<script src="./login" />

<style src="./login.scss" lang="scss" scoped />
