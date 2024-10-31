import ListaEventos from './components/ListaEventos.js';
import FormularioEvento from './components/FormularioEvento.js';
import ExcluirEvento from './components/ExcluirEvento.js';
const routes = [
  { path: '/', component: ListaEventos },
  { path: '/cadastrar', component: FormularioEvento },
  { path: '/excluir', component: ExcluirEvento }
];

const router = VueRouter.createRouter({
  history: VueRouter.createWebHashHistory(),
  routes
});

export default router;