import { createWebHistory, createRouter } from "vue-router";
import User from "../components/User.vue";
import Account from "../components/Account.vue";
import Login from "../components/Login.vue";
import About from "../components/About.vue";
const routes = [
    {
        path: "/user",
        name: "User",
        component: User,
    },
    {
        path: "/account",
        name: "Account",
        component: Account,
    },
    {
        path: "/login",
        name: "Login",
        component: Login,
    },
    {
        path: "/about",
        name: "About",
        component: About,
    },


];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
