<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="leftDrawerOpen = !leftDrawerOpen"
        />

        <q-toolbar-title> Onfly App </q-toolbar-title>

        <div>{{ nameUser }}</div>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
      content-class="bg-grey-1"
    >
      <q-list>
        <q-item-label header class="text-grey-8"> Menu </q-item-label>
        <q-item clickable @click="btnDashboard">
          <q-item-section avatar>
            <q-icon name="dashboard" />
          </q-item-section>

          <q-item-section>
            <q-item-label>Dashboard</q-item-label>
          </q-item-section>
        </q-item>
        <q-item clickable @click="btnExpenses">
          <q-item-section avatar>
            <q-icon name="monetization_on" />
          </q-item-section>

          <q-item-section>
            <q-item-label>Despesas</q-item-label>
          </q-item-section>
        </q-item>
        <q-item clickable @click="btnUsers">
          <q-item-section avatar>
            <q-icon name="group" />
          </q-item-section>

          <q-item-section>
            <q-item-label>Usuários</q-item-label>
          </q-item-section>
        </q-item>
        <q-item clickable @click="btnLogout">
          <q-item-section avatar>
            <q-icon name="exit_to_app" />
          </q-item-section>

          <q-item-section>
            <q-item-label>Logout</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { LocalStorage, SessionStorage } from "quasar";

export default {
  name: "MainLayout",
  data() {
    return {
      user: null,
      token: null,
      leftDrawerOpen: false,
    };
  },

  computed: {
    nameUser() {
      return this.user ? this.user.name : "";
    },
  },

  beforeMount() {
    this.token = this.$store.getters.token;
    this.user = this.$store.getters.user;
    let sessionToken = SessionStorage.getItem("onfly-autentication");
    let sessionUser = SessionStorage.getItem("onfly-session");
    if (!sessionUser) {
      this.btnLogout();
    }

    if (sessionToken) {
      this.$store.commit("SET_TOKEN", sessionToken);
    }

    if (sessionUser) {
      this.user = sessionUser;
      this.$store.commit("SET_USER", sessionUser);
    }
  },

  methods: {
    btnDashboard() {
      this.$router.push({ name: "dashboard" });
    },
    btnExpenses() {
      this.$router.push({ name: "expenses" });
    },
    btnUsers() {
      this.$router.push({ name: "users" });
    },
    btnLogout() {
      LocalStorage.remove("onfly-autentication");
      SessionStorage.remove("onfly-session");
      this.$store.commit("SET_USER", {});
      this.$store.commit("SET_TOKEN", null);
      this.$router.push({ name: "login" });
    },
  },
};
</script>
