import { createRouter, createWebHashHistory, createMemoryHistory } from 'vue-router';
import AppLayout from "@src/layouts/AppLayout.vue";

const history = typeof window !== 'undefined' ? createWebHashHistory() : createMemoryHistory();

const router = createRouter({
    history: history,
    routes: [{
        path: '/',
        component: AppLayout,
        children: [
            {
                path: '/',
                name: 'dashboard',
                component: () => import('@src/views/pages/Dashboard.vue')
            },
            {
                path: '/hello',
                name: 'hello',
                component: () => import('@src/views/pages/Hello.vue')
            },
        ]
    }]
});

export default router;