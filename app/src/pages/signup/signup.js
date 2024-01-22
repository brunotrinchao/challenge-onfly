import Validator from "src/utils/validator";
import { LocalStorage } from "quasar";

export default {
  name: "Login",
  data() {
    return {
      form: {
        email: "",
        password: "",
        name: "",
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
    btnLogin() {
      this.$router.push({ name: "login" });
    },
    async onValidate() {
      const validateFrom = await this.$refs.formSignup.validate();
      if (validateFrom) {
        const params = {
          params: this.form,
          loader: true,
        };
        let returnLogin = await this.$store.dispatch("inserir", params);

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
