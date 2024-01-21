import Vue from "vue";
import { boot } from "quasar/wrappers";
import axios from "axios";
import urls from "src/plugins/urls";
import { Notify } from "quasar";
import { LocalStorage } from "quasar";
import { Loading } from "quasar";

let api = axios.create({
  baseURL: urls.BASE_API + "api",
  withCredentials: false,
});

export default ({ store, Vue }) => {
  let loader = null;

  let token = LocalStorage.getItem("onfly-autentication");

  api.interceptors.request.use(
    (config) => {
      config.headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: token ? `Bearer ${token}` : "",
      };
      loader = config.loader;
      if (!token) {
        config.headers.Authorization = formatToken();
      }
      console.log("headers", config.headers);

      if (config.loader) {
        Loading.show();
      }

      return config;
    },
    (error) => {
      if (loader) {
        Loading.hide();
      }
      notification(error.message);
      return Promise.reject(error);
    }
  );

  api.interceptors.response.use(
    (response) => {
      loader = response.config.loader;

      if (response.config.loader) {
        if (loader) {
          Loading.show();
        }
      }
      if (response.data.success === false) {
        if (typeof response.data.message == "object") {
          Object.keys(response.data.message).forEach((element) => {
            notification(response.data.message[element][0]);
          });
        } else {
          notification(response.data.message);
        }
      }
      Loading.hide();

      return response;
    },
    (error) => {
      const msg = error.response.data.message || error.message;
      if (loader) {
        Loading.hide();
      }
      notification(msg);
      return Promise.reject({ error });
    }
  );

  function notification(msg) {
    Notify.create({
      message: msg,
      html: true,
    });
  }

  function formatToken() {
    let token = LocalStorage.getItem("onfly-autentication");
    return token ? `Bearer ${token}` : "";
  }

  Vue.prototype.$axios = api;
  store.$api = api;
};

export { axios, api };
