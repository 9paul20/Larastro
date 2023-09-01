import axios from "axios";
import { createApp } from "vue/dist/vue.esm-bundler";
import configureApp from "@src/configureApp";
//
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
//
const { components, directives, uses } = configureApp;
const app = createApp();
//
for (const [key, value] of Object.entries(components)) {
    app.component(key, value);
}
directives.forEach((directive) => {
    app.directive(directive.name, directive.directive);
});
uses.forEach((use) => {
    app.use(use.name, use.parameter);
});
//
app.mount("#app");
