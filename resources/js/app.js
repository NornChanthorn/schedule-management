import { createApp } from 'vue';
import App from './App.vue';
import router from './router/router.js'
import PrimeVue from 'primevue/config'
import Lara from '../../presets/lara';
import Tailwind from 'primevue/passthrough/tailwind';


import "primevue/resources/themes/saga-blue/theme.css"; //theme
import "primevue/resources/primevue.min.css"; //core CSS
import "primeicons/primeicons.css"; //icons

// test component primevue
import Button from "primevue/button"
import SpeedDial from 'primevue/speeddial';
import InputText from 'primevue/inputtext';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';
import axios from 'axios'

import '../css/app.css';
const authToken = localStorage.getItem('authToken');
axios.defaults.baseURL = 'http://139.59.224.162/api/';
if (authToken) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;
}


const app = createApp(App);

app.config.globalProperties.$axios = axios;
app.use(router)
app.use(PrimeVue , { unstyled: true, pt: Tailwind })
// app.use(PrimeVue,  {
//     unstyled: true,
//     pt: Lara
// })
app.component('test', App)
app.component('Button', Button)
app.component('Dialog', Dialog)
app.component('Toast', Toast)
app.component('InputText', InputText)
app.component('SpeedDial', SpeedDial)
app.mount('#app')












