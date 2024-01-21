import Vue from "vue";
import Vuex from "vuex";
import { api } from "boot/axios";

export default {
  obter: async (url, params) => {
    let response = await api.get(url, params);
    return response.data;
  },

  inserir: async (url, params, loader) => {
    const _loader = loader ? loader : false;
    let response = await api.post(url, params, {
      loader: _loader,
    });
    return response.data;
  },

  excluir: async (url, id, loader = false) => {
    let response = await api.delete(url + "/" + id, loader);
    return response.data;
  },

  alterar: async (url, params, loader) => {
    const _loader = loader ? loader : false;
    let response = await api.put(url, params, {
      loader: _loader,
    });
    return response.data;
  },
};
