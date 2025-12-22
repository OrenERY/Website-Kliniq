import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from './components/Dashboard.vue';
import Patients from './components/Patients.vue';
import Queues from './components/Queues.vue';
import Poli from './components/Poli.vue';
import Login from './components/Login.vue';
import axios from 'axios';

const routes = [
    { path: '/', component: Dashboard, meta: { requiresAuth: true } },
    { path: '/patients', component: Patients, meta: { requiresAuth: true } },
    { path: '/queues', component: Queues, meta: { requiresAuth: true } },
    { path: '/poli', component: Poli, meta: { requiresAuth: true } },
    { path: '/login', component: Login }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    if (to.meta.requiresAuth && !token) {
        next('/login');
    } else {
        next();
    }
});

// Setup axios interceptor
axios.interceptors.request.use(
    config => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    error => {
        return Promise.reject(error);
    }
);

axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            localStorage.removeItem('token');
            router.push('/login');
        }
        return Promise.reject(error);
    }
);

export default router;