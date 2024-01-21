import Vue from "vue";
import Vuex from "vuex";
import { LocalStorage, SessionStorage } from "quasar";
import api from "src/plugins/api";

// import example from './module-example'

import state from "./state";

const modules = {};

Vue.use(Vuex);

export default function (/* { ssrContext } */) {
  const Store = new Vuex.Store({
    modules,
    state,
    mutations: {
      SET_TOKEN(state, token) {
        state.sessao.token = token;
      },
      SET_USER(state, user) {
        state.sessao.user = user;
      },
      SET_EXPENSES(state, expenses) {
        state.expenses = expenses;
      },
      SET_USERS(state, users) {
        state.users = users;
      },
    },
    actions: {
      async login({ commit }, { params, loader }) {
        let retorno = await api.inserir(`/auth/login`, params, loader);

        if (retorno.success) {
          commit("SET_TOKEN", retorno.token);
          commit("SET_USER", retorno.user);
          LocalStorage.set("onfly-autentication", retorno.token);
          SessionStorage.set("onfly-session", retorno.user);
        }
        return retorno;
      },
      async inserir({ commit }, { params, loader }) {
        let retorno = await api.inserir(`/signup`, params, loader);

        if (retorno.success) {
          commit("SET_TOKEN", retorno.token);
          commit("SET_USER", retorno.user);
          LocalStorage.set("onfly-autentication", retorno.token);
          SessionStorage.set("onfly-session", retorno.user);
          retorno.status = true;
        }
        return retorno;
      },
      // Expenses
      async insertExpense({ commit }, { params, loader }) {
        let retorno = await api.inserir(`/expense`, params, loader);

        return retorno.data;
      },
      async updateExpense({ commit }, { id, params, loader }) {
        let retorno = await api.alterar(`/expense/${id}`, params, loader);

        return retorno.data;
      },
      async deleteExpense({ commit }, { id, loader }) {
        let retorno = await api.excluir(`/expense`, id, loader);

        return retorno.data;
      },
      async getExpenses({ commit }, params) {
        let retorno = await api.obter(`/expense`, params);
        commit("SET_EXPENSES", retorno.data);
        return retorno;
      },
      // Users
      async insertUser({ commit }, { params, loader }) {
        let retorno = await api.inserir(`/user`, params, loader);

        return retorno.data;
      },
      async updateUser({ commit }, { id, params, loader }) {
        let retorno = await api.alterar(`/user/${id}`, params, loader);

        return retorno.data;
      },
      async deleteUser({ commit }, { id, loader }) {
        let retorno = await api.excluir(`/user`, id, loader);

        return retorno.data;
      },
      async getUsers({ commit }, params) {
        let retorno = await api.obter(`/user`, params);
        commit("SET_USERS", retorno.data);
        return retorno;
      },
    },
    getters: {
      token(state) {
        return state.sessao.token;
      },
      user(state) {
        return state.sessao.user;
      },
      expenses(state) {
        return state.expenses;
      },
      users(state) {
        return state.users;
      },
    },
    strict: process.env.DEV,
  });

  return Store;
}

// export default new Vuex.Store({
//   modules,
// });
