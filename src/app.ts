import type { App } from "vue";
// import Button from "primevue/button";
// import Menu from "primevue/menu";
import PrimeVue from "primevue/config";
import { RouterLink } from "vue-router";

const components = {
  // Button,
  // Menu,
  PrimeVue,
  RouterLink,
};
export default (app: App) => {
  app.use(PrimeVue);
  for (const [key, value] of Object.entries(components)) {
    app.component(key, value);
  }
};
