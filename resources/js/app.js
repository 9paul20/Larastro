import axios from 'axios';
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import '@src/assets/styles.scss';
import configureApp from '@src/configureApp.js';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

if (window.location.href.includes("/dashboard")) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}
const { components, directives, uses } = configureApp;
const app = createApp();

for (const [key, value] of Object.entries(components)) {
    app.component(key, value);
};
directives.forEach(directive => {
    app.directive(directive.name, directive.directive);
});
uses.forEach(use => {
    app.use(use.name, use.parameter);
});

app.mount("#app");