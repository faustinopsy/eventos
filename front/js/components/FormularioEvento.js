export default {
    template: `
      <form @submit.prevent="criarEvento">
        <input v-model="evento.titulo" placeholder="Título" required />
        <input v-model="evento.descricao" placeholder="Descrição" />
        <input v-model="evento.datainicial" type="date" required />
        <input v-model="evento.datafinal" type="date" />
        <select v-model="evento.recorrencia">
          <option value="nenhuma">Nenhuma</option>
          <option value="diaria">Diária</option>
          <option value="semanal">Semanal</option>
          <option value="mensal">Mensal</option>
          <option value="semestral">Semestral</option>
        </select>
        <input v-model="evento.nome" placeholder="Nome do usuário" required />
        <button type="submit">Criar Evento</button>
      </form>
    `,
    data() {
        return { 
            evento: {
                titulo: '', 
                descricao: '', 
                datainicial: '', 
                datafinal: '', 
                recorrencia: 'nenhuma', 
                nome: '' 
            }
        };
    },
    methods: {
        async criarEvento() {
            await fetch('http://localhost:8080/eventos', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(this.evento)
            });
            this.$emit('eventoCriado');
        }
    }
};