import Validator from "src/utils/validator";

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
        description: null,
        amount: null,
        date: null,
      },
    };
  },

  mounted() {
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
      const validateFrom = await this.$refs.formExpense.validate();
      if (validateFrom) {
        let returnExpense;
        let params = {
          params: this.form,
          loader: true,
        };
        if (this.action == "update") {
          returnExpense = await this.$store.dispatch("updateExpense", {
            id: this.id,
            ...params,
          });
        } else {
          returnExpense = await this.$store.dispatch("insertExpense", params);
        }

        if (returnExpense.id) {
          this.$root.$emit("reload-list-expenses");
          this.hide();
        }
      }
    },

    async onDeleteClick() {
      if (this.id) {
        let returnExpense = await this.$store.dispatch("deleteExpense", {
          id: this.id,
          loader: true,
        });
        if (returnExpense.id) {
          this.$root.$emit("reload-list-expenses");
          this.hide();
        }
      }
    },

    onCancelClick() {
      this.hide();
    },

    loadValues() {
      if (this.action == "update") {
        let item = this.$store.getters.expenses.filter((item) => {
          return item.id == this.id;
        });
        if (item.length > 0) {
          this.form.description = item[0].description;
          this.form.amount = item[0].amount;
          this.form.date = item[0].date;
        }
      }
    },
  },
};
