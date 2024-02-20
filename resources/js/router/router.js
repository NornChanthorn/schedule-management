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
import Students from '../components/student.vue'
import Teacher from '../components/teacher.vue'
import Room from '../components/room.vue'


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
                path: '/:name:id',
                name: 'Generation',
                component: Generation
            },
            {
                path: '/:name:id/:genid',
                name: 'Term',
                component: Term
            },
            {
              path: '/schedule',
              component: Schedule,
              name: 'schedule'
            },
            {
              path: '/profile',
              component: Profile,
              name: 'profile'
            },

        ]
    },
    {
        path: '/test',
        component: Test,
        name: 'Test'
    },
    {
      path: '/studentlist',
      component: Student,
      name: 'studentlist'
    },

    {
      path: '/student',
      component: Students,
      name: 'student'
  },
  {
      path: '/teacher',
      component: Teacher,
      name: 'teacher'
  },
  {
    path: '/room',
    component: Room,
    name: 'room'
},



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
