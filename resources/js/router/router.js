import { createRouter, createWebHistory } from "vue-router";
import Login from '../components/auth/Login.vue'
import forgotpassword from '../components/auth/forgotpassword.vue'
import Main from '../components/dashboard.vue'
import Test from '../components/ExampleComponent.vue'
import Schedule from '../components/schedule.vue'
import Student from '../components/studentList.vue'
import Profile from '../components/profile.vue'
import Profiles from '../components/profile.vue'
import Generation from '../components/admin/Generation.vue'
import Major from '../components/admin/Major.vue'
import Term from '../components/admin/Term.vue'
import Terms from '../components/term.vue'
import Students from '../components/student.vue'
import Teacher from '../components/teacher.vue'
import Room from '../components/room.vue'
import Course from '../components/admin/Course.vue'
import TeacherDashboard from '../components/teacher/MainPage.vue'
import listCourse from '../components/teacher/CoursePage.vue'


const routes=[
    {
        path: '/login',
        name: 'Login',
        component: Login

    },
    {
      path: '/forgotpassword',
      name: 'forgotpassword',
      component: forgotpassword

    },
    {
        path: '/teacherPage',
        name: 'TeacherPage',
        component: TeacherDashboard,
        meta: { requiresAuth: true },
        children:[
            {
                path: '/teacherPage',
                name: 'courseList',
                component: listCourse,

            },
            {
                path: '/profiles',
                component: Profiles,
                name: 'profiles'
            },

        ]

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
                path: '/:name/:majorId',
                name: 'Generation',
                component: Generation
            },
            {
                path: '/:name/:majorId/:genid',
                name: 'Term',
                component: Term
            },
            {
                path: '/:name/:majorId/:genid/:termId',
                name: 'schedule',
                component: Schedule,
            },
            {
              path: '/profile',
              component: Profile,
              name: 'profile'
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
                path: '/terms',
                component: Terms,
                name: 'terms'
            },
            {
                path: '/room',
                component: Room,
                name: 'room'
            },
            {
                path: '/courses',
                component: Course,
                name: 'Course'
            }

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

]

const router = createRouter({
    history: createWebHistory(),
    routes,
});


export default router
