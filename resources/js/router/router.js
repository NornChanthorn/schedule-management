import { createRouter, createWebHistory } from "vue-router";
import Login from '../components/auth/Login.vue'
import Main from '../components/test.vue'
const routes=[
    {
        path: '/login',
        name: 'Login',
        component: Login

    },
    {
        path: '/',
        name: 'Main',
        component: Main
    }

];

const router = createRouter({
    history: createWebHistory(),
    routes,
  })

export default router
