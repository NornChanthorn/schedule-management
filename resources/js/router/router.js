import { createRouter, createWebHistory } from "vue-router";
import Login from '../components/auth/Login.vue'
import Main from '../components/dashboard.vue'
import Test from '../components/ExampleComponent.vue'
import Schedule from '../components/schedule.vue'
import Student from '../components/studentList.vue'
import Profile from '../components/profile.vue'
import Generation from '../components/admin/Generation.vue'
import Major from '../components/admin/Major.vue'
import Term from '../components/admin/Term.vue'
import Teacher from '../components/admin/teacher.vue'

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
        children:[
            {
                path: '',
                name: 'Major', // Add a comma here
                component: Major,
            },
            {
                path: '/:name',
                name: 'Generation',
                component: Generation
            },
            {
                path: '/:name/:id',
                name: 'Term',
                component: Term
            }

        ]
    },
    {
        path: '/test',
        component: Test,
        name: 'Test'
    },
    {
      path: '/schedule',
      component: Schedule,
      name: 'schedule'
    },
    {
      path: '/studentlist',
      component: Student,
      name: 'studentlist'
    },
    {
      path: '/profile',
      component: Profile,
      name: 'profile'
    },
    {
        path: '/teacherlist',
        component: Teacher,
        name: 'Teacher'
      },


];

const router = createRouter({
    history: createWebHistory(),
    routes,
});
// router.beforeEach((to, from, next) => {

//     if (to.matched.some(record => record.meta.requiresAuth)) {
//       if (!isUserAuthenticated()) {
//         next('/login');
//       } else {
//         // If authenticated, prevent access to the login page
//         if (to.name === 'Login') {
//           next('/');
//         } else {
//           next();
//         }
//       }
//     } else {
//       next();
//     }
// });
// function isUserAuthenticated() {
//     return localStorage.getItem('authToken') !== null;
// }

export default router
