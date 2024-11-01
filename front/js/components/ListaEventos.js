export default {
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
            eventos: '',
            filtroNome: '',
            usuarios: [] 
        };
    },
    methods: {
        async buscaEvento() {
            const response = await fetch('http://localhost:8080/eventos');
            this.eventos = await response.json();
        },
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
                initialView: 'timeGridWeek',
                nowIndicator: true,
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                  },
                  locale: 'pt-br',
				buttonText: {
                    prev: "<<",
                    today: "Hoje",
                    next: ">>",
                    month: "Mês",
                    week: "Semana",
                    day: "Dia"
                },
				// dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado'],
				// dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
				initialDate: Date.now(),
				navLinks: true,
				selectable: false,
				nowIndicator: true,
				dayMaxEvents: true,
				editable: false,
				businessHours: true,
				dayMaxEvents: true,
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
   async mounted() {
       await this.buscaEvento()
        this.carregarUsuarios();
        this.renderCalendar(this.eventos);
    }
};
