export default class Validator {
  requiredField = (val) => !!val || "Campo obrigatório";
  validateEmail = (val) =>
    /[a-z0-9]+@[a-z]+\.[a-z]{2,3}/.test(val) || "E-mail inválido";
}
