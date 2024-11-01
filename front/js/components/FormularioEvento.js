export default {
    template: `
      <form @submit.prevent="criarEvento">
        <select v-model="evento.titulo" required>
          <option disabled value="">Selecione uma sala</option>
          <option v-for="(cor, titulo) in salas" :key="titulo" :value="titulo">{{ titulo }}</option>
        </select>
        <input v-model="evento.descricao" placeholder="Descrição" />
        <input v-model="evento.datainicial" type="date" required />
         <input v-model="evento.horarioinicial" type="time" required />
        <input v-model="evento.datafinal" type="date" />
        <input v-model="evento.horariofinal" type="time" required />
        <select v-model="evento.recorrencia">
          <option value="nenhuma">Nenhuma</option>
          <option value="diaria">Diária</option>
          <option value="semanal">Semanal</option>
          <option value="mensal">Mensal</option>
          <option value="semestral">Semestral</option>
        </select>
        <select v-model="evento.nome" required>
          <option disabled value="">Nome do usuário</option>
          <option v-for="user in usuarios" :key="user.nome" :value="user.nome">{{ user.nome }}</option>
        </select>
        <button type="submit">Criar Evento</button>
        <p v-if="mensagem">{{ mensagem }}</p>
      </form>
    `,
    data() {
        return { 
            evento: {
                titulo: '', 
                descricao: '', 
                datainicial: '',
                horarioinicial: '', 
                datafinal: '',
                horariofinal: '',  
                recorrencia: 'nenhuma', 
                nome: '',
                cor: ''
            },
            mensagem: '',
            salas: {
                "Sala A": "#FF5733",
                "Sala B": "#33FF57",
                "Sala C": "#3357FF",
                "Sala D": "#FF33A1",
                "Sala E": "#A133FF"
            },
            usuarios:[]
        };
    },
    methods: {
        async criarEvento() {
            this.evento.cor = this.salas[this.evento.titulo];

            const response = await fetch('http://localhost:8080/eventos', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(this.evento)
            });
            const result = await response.json();
            if (result.status) {
                this.mensagem = 'Evento(s) criado(s) com sucesso.';
                this.$emit('eventoCriado');
            } else {
                this.mensagem = 'Evento não criado.';
            }
        },
        async buscaUsuarios() {
            const response = await fetch('http://localhost:8080/users');
            this.usuarios = await response.json();
        },
    },
    async mounted() {
       await this.buscaUsuarios()
    },
};
