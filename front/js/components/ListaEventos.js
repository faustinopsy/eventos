export default {
    template: `
        <div>
            <select v-model="filtroNome" @change="buscarEventos">
                <option value="">Todos Usuários</option>
                <option v-for="usuario in usuarios" :key="usuario" :value="usuario">{{ usuario }}</option>
            </select>
            <div id="calendar"></div>

            <div v-if="eventoSelecionado" class="modal">
                <div class="modal-content">
                    <h3>Detalhes do Evento</h3>
                    <p><strong>ID:</strong> {{ eventoSelecionado.evento_base_id }}</p>
                    <p><strong>Título:</strong> {{ eventoSelecionado.titulo }}</p>
                    <p><strong>Descrição:</strong> {{ eventoSelecionado.descricao }}</p>
                    <p><strong>Data Inicial:</strong> {{ eventoSelecionado.datainicial }}</p>
                    <p><strong>Data Final:</strong> {{ eventoSelecionado.datafinal }}</p>
                    <button @click="fecharModal">Fechar</button>
                </div>
            </div>
        </div>
    `,
    props: ['urlbase'],
    data() {
        return { 
            eventos: '',
            filtroNome: '',
            usuarios: [],
            eventoSelecionado: null
        };
    },
    methods: {
        async buscaEvento() {
            const response = await fetch(`${this.urlbase}/eventos`);
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
				selectable: true,
				nowIndicator: true,
				dayMaxEvents: true,
				editable: false,
				businessHours: true,
				dayMaxEvents: true,
                events: eventos.map(evento => ({
                    title: evento.titulo,
                    start: `${evento.datainicial}T${evento.horarioinicial}`,
                    end: `${evento.datafinal}T${evento.horariofinal}`,   
                    backgroundColor: evento.cor,
                    extendedProps: {
                        id: evento.evento_base_id,
                        descricao: evento.descricao,
                    
                    }
                })),
                eventClick: this.mostrarDetalhesEvento
            });
            calendar.render();
        },
        mostrarDetalhesEvento(info) {
            const evento = this.eventos.find(evento => evento.titulo === info.event.title);
            this.eventoSelecionado = evento;
        },
        fecharModal() {
            this.eventoSelecionado = null;
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
