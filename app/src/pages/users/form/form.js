import Validator from "src/utils/validator";
import { date } from "quasar";

export default {
  name: "FormExpense",
  props: {
    parameters: { type: Object },
  },
  data() {
    return {
      titulo: "Adicionar",
      action: "insert",
      id: null,
      form: {
        name: null,
        email: null,
        password: null,
      },
    };
  },

  computed: {
    disableInput() {
      return this.id ? true : false;
    },
  },

  mounted() {
    console.log(this.parameters);
    if (typeof this.parameters !== "undefined") {
      this.id = this.parameters.id;
      this.action = "update";
      this.titulo = "Editar";
    }

    this.loadValues();
  },

  created() {
    this.validator = new Validator();
  },

  methods: {
    show() {
      this.$refs.dialog.show();
    },

    hide() {
      this.$refs.dialog.hide();
    },

    onDialogHide() {
      this.$emit("hide");
    },

    async onOKClick() {
      const validateFrom = await this.$refs.formUser.validate();
      if (validateFrom) {
        let returnUser;
        let params = {
          params: this.form,
          loader: true,
        };
        if (this.action == "update") {
          delete params.params.email;
          returnUser = await this.$store.dispatch("updateUser", {
            id: this.id,
            ...params,
          });
        } else {
          returnUser = await this.$store.dispatch("insertUser", params);
        }

        if (returnUser.id) {
          this.$root.$emit("reload-list-users");
          this.hide();
        }
      }
    },

    async onDeleteClick() {
      if (this.id) {
        let returnUser = await this.$store.dispatch("deleteUser", {
          id: this.id,
          loader: true,
        });
        if (returnUser.id) {
          this.$root.$emit("reload-list-users");
          this.hide();
        }
      }
    },

    onCancelClick() {
      this.hide();
    },

    loadValues() {
      if (this.action == "update") {
        let item = this.$store.getters.users.filter((item) => {
          return item.id == this.id;
        });
        if (item.length > 0) {
          this.form.name = item[0].name;
          this.form.password = "";
          this.form.email = item[0].email;
        }
      }
    },
  },
};
