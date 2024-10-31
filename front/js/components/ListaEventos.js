export default {
    props: ['eventos'],
    template: `
        <div>
            <select v-model="filtroNome" @change="buscarEventos">
                <option value="">Todos</option>
                <option v-for="usuario in usuarios" :key="usuario" :value="usuario">{{ usuario }}</option>
            </select>
            <div id="calendar"></div>
        </div>
    `,
    data() {
        return { 
            filtroNome: '',
            usuarios: [] 
        };
    },
    methods: {
        buscarEventos() {
            const eventosFiltrados = this.filtroNome 
                ? this.eventos.filter(evento => evento.nome === this.filtroNome) 
                : this.eventos;

            this.renderCalendar(eventosFiltrados);
        },
        renderCalendar(eventos) {
            const calendarEl = document.getElementById('calendar');
            calendarEl.innerHTML = '';

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: eventos.map(evento => ({
                    title: evento.titulo,
                    start: evento.datainicial,
                    end: evento.datafinal
                }))
            });
            calendar.render();
        },
        carregarUsuarios() {
            const nomes = this.eventos.map(evento => evento.nome);
            this.usuarios = [...new Set(nomes)];
        }
    },
    mounted() {
        this.carregarUsuarios();
        this.renderCalendar(this.eventos);
    }
};
