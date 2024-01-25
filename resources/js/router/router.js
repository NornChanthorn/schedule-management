import { createRouter, createWebHistory } from "vue-router";
import Login from '../components/auth/Login.vue'
import Main from '../components/test.vue'
import Test from '../components/ExampleComponent.vue'
const routes=[
    {
        path: '/login',
        name: 'Login',
        component: Login

    },
    {
        path: '/',
        name: 'Main',
        component: Main,
        meta: { requiresAuth: true },
    },
    {
        path: '/test',
        component: Test,
        name: 'Test'
    }


];

const router = createRouter({
    history: createWebHistory(),
    routes,
});
router.beforeEach((to, from, next) => {

    if (to.matched.some(record => record.meta.requiresAuth)) {
      if (!isUserAuthenticated()) {
        next('/login');
      } else {
        // If authenticated, prevent access to the login page
        if (to.name === 'Login') {
          next('/');
        } else {
          next();
        }
      }
    } else {
      next();
    }
});
function isUserAuthenticated() {
    return localStorage.getItem('authToken') !== null;
}

export default router
