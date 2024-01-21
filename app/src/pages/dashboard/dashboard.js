export default {
  name: "Dashboard",
  data() {
    return {
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
          field: (row) => row.name,
          format: (val) => `${val}`,
          sortable: true,
        },
        {
          name: "email",
          align: "left",
          label: "Email",
          field: "email",
          sortable: true,
        },
      ],
      data: [
        {
          id: "1",
          name: "Frozen Yogurt",
          email: "brunotrinchao@gmail.com",
        },
      ],
    };
  },
};
