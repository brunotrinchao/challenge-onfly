import Validator from "src/utils/validator";
import { LocalStorage } from "quasar";

export default {
  name: "Login",
  data() {
    return {
      login: {
        email: "admin@admin.com",
        password: "admin",
        isPwd: true,
      },
    };
  },

  beforeMount() {
    let token = LocalStorage.getItem("onfly-autentication");
    if (token) {
      this.$router.push({ name: "dashboard" });
    }
  },

  created() {
    this.validator = new Validator();
  },

  methods: {
    btnSignup() {
      this.$router.push({ name: "signup" });
    },

    async onValidate() {
      const validateFrom = await this.$refs.formLogin.validate();
      if (validateFrom) {
        const params = {
          params: this.login,
          loader: true,
        };
        let returnLogin = await this.$store.dispatch("login", params);

        if (returnLogin.success) {
          this.$router.push({ name: "dashboard" });
        }
      }
    },

    validateEmail(email) {
      return /[a-z0-9]+@[a-z]+\.[a-z]{2,3}/.test(email);
    },
  },
};
