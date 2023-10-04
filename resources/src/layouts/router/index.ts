import {
    createRouter,
    createWebHashHistory,
    createMemoryHistory,
} from "vue-router";
import AppLayoutDashboard from "@src/layouts/dashboard/AppLayout.vue";
import AppLayoutLanding from "@src/layouts/landing/AppLayout.vue";
//
const history =
    typeof window !== "undefined"
        ? createWebHashHistory()
        : createMemoryHistory();
//
const router = createRouter({
    history: history,
    routes: [
        {
            path: "/",
            component: AppLayoutLanding,
            children: [
                {
                    path: "/",
                    name: "Index",
                    component: () =>
                        import("@src/views/pages/landing/index.vue"),
                },
                {
                    path: "/about",
                    name: "About",
                    component: () =>
                        import("@src/views/pages/landing/About.vue"),
                },
                {
                    path: "/developer",
                    name: "Developer",
                    component: () =>
                        import("@src/views/pages/landing/Developer.vue"),
                },
                {
                    path: "/moreInfo",
                    name: "MoreInfo",
                    component: () =>
                        import("@src/views/pages/landing/MoreInfo.vue"),
                },
            ],
        },
        {
            path: "/dashboard",
            component: AppLayoutDashboard,
            children: [
                {
                    path: "/dashboard/",
                    name: "Dashboard",
                    component: () =>
                        import("@src/views/pages/dashboard/Dashboard.vue"),
                },
                {
                    path: "/dashboard/hello",
                    name: "Hello",
                    component: () =>
                        import("@src/views/pages/dashboard/Hello.vue"),
                },
                {
                    path: "/dashboard/users",
                    name: "Users",
                    component: () =>
                        import("@src/views/pages/dashboard/Users.vue"),
                },
                {
                    path: "/dashboard/roles",
                    name: "Roles",
                    component: () =>
                        import("@src/views/pages/dashboard/Roles.vue"),
                },
                {
                    path: "/dashboard/permissions",
                    name: "Permissions",
                    component: () =>
                        import("@src/views/pages/dashboard/Permissions.vue"),
                },
            ],
        },
        {
            path: "/login",
            name: "Login",
            component: () => import("@src/views/pages/auth/Login.vue"),
        },
        {
            path: "/register",
            name: "Register",
            component: () => import("@src/views/pages/auth/Register.vue"),
        },
        {
            path: "/:catchAll(.*)",
            name: "Error",
            component: () => import("@src/views/pages/auth/Error.vue"),
            meta: {
                requiresAuth: false,
            },
        },
    ],
});
//
export default router;
