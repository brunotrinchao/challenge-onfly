let BASE_API, SERVER, ENVIRONMENT;
if (
  window.location.origin.includes("192") ||
  window.location.origin.includes("localhost")
) {
  BASE_API = `http://${window.location.hostname}:8000/`;
  SERVER = "D";
  ENVIRONMENT = "Desenvolvimento";
} else {
  BASE_API = "";
  SERVER = "P";
  ENVIRONMENT = "Produção";
}

export default {
  BASE_API: BASE_API,
  SERVER: SERVER,
  ENVIRONMENT: ENVIRONMENT,
};
