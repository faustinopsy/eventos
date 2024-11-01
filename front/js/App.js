import Navbar from './components/Navbar.js';
import router from './router.js';

const app = Vue.createApp({
    components: { Navbar },
    template: `
        <Navbar />
        <router-view ></router-view>
    `,
    
});

app.use(router);
app.mount('#app');
