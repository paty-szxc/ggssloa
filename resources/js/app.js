// import './bootstrap';
// // import { createApp } from 'vue';
// import { createApp } from 'vue/dist/vue.esm-bundler.js';
// import router from './router';
// import vuetify from './vuetify';
// import App from '../vue/templates/App.vue'
// import Login from '../vue/pages/Login.vue';
// import 'vuetify/styles'; 

// const app = createApp({})
// app.component('App', App)
// app.component('Login', Login)
// app.use(router)
// app.use(vuetify)
// app.mount('#app')
import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import router from './router';
import vuetify from './vuetify';
import App from '../vue/templates/App.vue'
import Login from '../vue/pages/Login.vue';
import 'vuetify/styles'; 

const app = createApp(App) // Pass App as the root component
app.use(router)
app.use(vuetify)
app.mount('#app')