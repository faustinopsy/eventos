import ListaEventos from './components/ListaEventos.js';
import FormularioEvento from './components/FormularioEvento.js';
import ExcluirEvento from './components/ExcluirEvento.js';

const BASE_URL = 'http://localhost:8080';

const routes = [
  { path: '/', component: ListaEventos, props: { urlbase: BASE_URL } },
  { path: '/cadastrar', component: FormularioEvento, props: { urlbase: BASE_URL } },
  { path: '/excluir', component: ExcluirEvento, props: { urlbase: BASE_URL } }
];

const router = VueRouter.createRouter({
  history: VueRouter.createWebHashHistory(),
  routes
});

export default router;