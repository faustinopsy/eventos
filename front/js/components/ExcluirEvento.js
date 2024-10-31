export default {
    template: `
        <div>
            <h3>Excluir Evento</h3>
            <input v-model="nome" placeholder="Nome do evento para excluir" />
            <button @click="excluirEvento">Excluir Evento</button>
            <p v-if="mensagem">{{ mensagem }}</p>
        </div>
    `,
    data() {
        return {
            nome: '',
            mensagem: ''
        };
    },
    methods: {
        async excluirEvento() {
            const response = await fetch(`http://localhost:8080/eventos/nome/${this.nome}`, {
                method: 'DELETE'
            });
            const result = await response.json();
            if (result.status) {
                this.mensagem = 'Evento(s) excluído(s) com sucesso.';
                this.nome = '';
                this.$emit('eventoExcluido');
            } else {
                this.mensagem = 'Evento não encontrado.';
            }
        }
    }
};
