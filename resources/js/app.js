import axios from 'axios';
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import index from '@srcPages/index.vue';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

if (window.location.href.includes("/dashboard")) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}
const app = createApp({
    mounted() {
        // console.log('The app is working')
    },
    components: {
        index
    },
});
app.mount("#app");