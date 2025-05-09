import './bootstrap';
import {createApp} from 'vue'
import {createPinia} from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'

import App from './components/App.vue';

import router from './router';
import vuetify from './vuetify';

const app = createApp(App);
const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)
app.use(pinia)
app.use(router)
app.use(vuetify)
app.mount('#app')


