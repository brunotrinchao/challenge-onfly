import Form from "./form/index.vue";
import Helper from "src/utils/helper.js";

export default {
  name: "Expenses",
  components: {
    Form,
  },
  data() {
    return {
      filter: "",
      loading: false,
      pagination: {
        sort: "created_at",
        order: "desc",
        descending: false,
        page: 1,
        rowsPerPage: 5,
        rowsNumber: 10,
      },
      columns: [
        {
          name: "id",
          align: "left",
          label: "#ID",
          field: "id",
          sortable: true,
        },
        {
          name: "description",
          required: true,
          label: "Descrição",
          align: "left",
          field: "description",
          sortable: true,
        },
        {
          name: "amount",
          align: "left",
          label: "Valor",
          field: "amount",
          sortable: true,
        },
        {
          name: "date",
          align: "left",
          label: "Data",
          field: "date",
          sortable: true,
        },
      ],
      data: [],
    };
  },

  watch: {
    "$store.getters.expenses": {
      handler(val) {
        this.data = this.tranformExpenses(val);
      },
      deep: true,
    },
  },

  computed: {
    pagesNumber() {
      return Math.ceil(this.data.length / this.pagination.rowsPerPage);
    },
  },

  beforeMount() {
    this.$root.$on("reload-list-expenses", () => {
      this.onRequest({
        pagination: this.pagination,
        filter: undefined,
      });
    });
  },

  async mounted() {
    await this.onRequest({
      pagination: this.pagination,
      filter: undefined,
    });

    this.data = this.tranformExpenses(this.$store.getters.expenses);
  },

  methods: {
    onRowClick(evt, row) {
      this.$q
        .dialog({
          component: Form,
          text: "something",
          parent: this,
          parameters: row,
        })
        .onOk(() => {
          console.log("OK");
        })
        .onCancel(() => {
          console.log("Cancel");
        })
        .onDismiss(() => {
          console.log("Called on OK or Cancel");
        });
    },

    btnAddExpense() {
      this.$q
        .dialog({
          component: Form,
          text: "something",
          parent: this,
        })
        .onOk(() => {
          console.log("OK");
        })
        .onCancel(() => {
          console.log("Cancel");
        })
        .onDismiss(() => {
          console.log("Called on OK or Cancel");
        });
    },

    tranformExpenses(expenses) {
      return expenses.map((expense) => {
        return {
          id: expense.id,
          description: expense.description,
          amount: Helper.convertCurrency(expense.amount),
          date: Helper.convertDate(expense.date),
        };
      });
    },

    async onRequest(props) {
      const { page } = props.pagination;

      this.loading = true;

      const returnedData = await this.$store.dispatch("getExpenses", {
        params: {
          page: page,
          order: this.pagination.order,
          sort: this.pagination.sort,
        },
        loader: false,
      });

      this.pagination.rowsNumber = returnedData.meta.total;
      this.pagination.page = returnedData.meta.current_page;
      this.pagination.rowsPerPage = returnedData.meta.per_page;

      this.loading = false;
    },
  },
};
