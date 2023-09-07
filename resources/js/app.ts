import axios from "axios";
import { createApp } from "vue/dist/vue.esm-bundler";
import { createPinia } from 'pinia';
import configureApp from "@src/configureApp";
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
app.use(createPinia());
//
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
//
app.mount("#app");
