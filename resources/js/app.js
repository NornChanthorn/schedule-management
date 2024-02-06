/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import { createApp } from 'vue';
import App from './App.vue';
import router from './router/router.js'
import PrimeVue from 'primevue/config'
import Lara from '../../presets/lara';
import Tailwind from 'primevue/passthrough/tailwind';

import "primevue/resources/themes/saga-blue/theme.css"; //theme
import "primevue/resources/primevue.min.css"; //core CSS
import "primeicons/primeicons.css"; //icons
import Header from "./components/header.vue";

// test component primevue
import Button from "primevue/button"
import SpeedDial from 'primevue/speeddial';
import InputText from 'primevue/inputtext';
import axios from 'axios'

import '../css/app.css';
const authToken = localStorage.getItem('authToken');
if (authToken) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;
}


const app = createApp(App);
app.use(router)
app.use(PrimeVue , { unstyled: true, pt: Tailwind })
// app.use(PrimeVue,  {
//     unstyled: true,
//     pt: Lara
// })
app.component('test', App)
app.component('Button', Button)
app.component('InputText', InputText)
app.component('SpeedDial', SpeedDial)
app.component('Header', Header)
app.mount('#app')



// createApp(App)
//     .use(Router)
//     .component('example-component', ExampleComponent)
//     .mount('#app');
// import axios from 'axios'
// window.axios = axios

// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */



/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

