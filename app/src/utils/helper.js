export default {
  convertCurrency: (value) => {
    return parseFloat(value).toLocaleString("pt-BR", {
      style: "currency",
      currency: "BRL",
    });
  },

  convertDate: (date) => {
    let dateObj = new Date(`${date} 23:59:59`);
    return dateObj
      .toLocaleString("pt-BR", {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
      })
      .replace(/\-/g, "/");
  },
};
