import { createApp } from 'vue';
import Notizen from './Components/notizen.vue'; // Must match exact file name

const app = createApp({});
app.component('notizen', notizen);
app.mount('#app');