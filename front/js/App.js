import Navbar from './components/Navbar.js';
import router from './router.js';

const app = Vue.createApp({
    components: { Navbar },
    template: `
        <Navbar />
        <router-view @eventoCriado="buscarEventos" :eventos="eventos" ></router-view>
    `,
    data() {
        return {
            eventos: []
        };
    },
    methods: {
        async buscarEventos() {
            const response = await fetch('http://localhost:8080/eventos');
            this.eventos = await response.json();
        }
    },
    mounted() {
        this.buscarEventos();
    }
});

app.use(router);
app.mount('#app');
