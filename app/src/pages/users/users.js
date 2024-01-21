import Form from "./form/index.vue";
import Helper from "src/utils/helper.js";
import { date } from "quasar";

export default {
  name: "Users",
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
          name: "name",
          required: true,
          label: "Nome",
          align: "left",
          field: "name",
          sortable: true,
        },
        {
          name: "email",
          align: "left",
          label: "E-mail",
          field: "email",
          sortable: true,
        },
        {
          name: "created_at",
          align: "left",
          label: "Criado em:",
          field: "created_at",
          sortable: true,
        },
      ],
      data: [],
    };
  },

  watch: {
    "$store.getters.users": {
      handler(val) {
        this.data = this.tranformUsers(val);
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
    this.$root.$on("reload-list-users", () => {
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

    this.data = this.tranformUsers(this.$store.getters.users);
  },

  methods: {
    onRowClick(evt, row) {
      this.$q.dialog({
        component: Form,
        text: "something",
        parent: this,
        parameters: row,
      });
    },

    btnAddExpense() {
      this.$q
        .dialog({
          component: Form,
          text: "something",
          parent: this,
        })
        .onOk(() => {})
        .onCancel(() => {})
        .onDismiss(() => {});
    },

    tranformUsers(users) {
      return users.map((user) => {
        return {
          id: user.id,
          name: user.name,
          email: user.email,
          created_at: date.formatDate(user.created_at, "DD/MM/YYYY"),
        };
      });
    },

    async onRequest(props) {
      const { page } = props.pagination;

      this.loading = true;

      const returnedData = await this.$store.dispatch("getUsers", {
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
