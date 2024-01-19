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
import '../css/app.css';
const app = createApp(App);
app.use(router)
app.use(PrimeVue , { unstyled: true, pt: Tailwind })
// app.use(PrimeVue,  {
//     unstyled: true,
//     pt: Lara
// })
app.component('test', App)
app.component('Button', Button)
app.component('SpeedDial', SpeedDial)
app.mount('#app')



// createApp(App)
//     .use(Router)
//     .component('example-component', ExampleComponent)
//     .mount('#app');


