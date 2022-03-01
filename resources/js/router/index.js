import { createWebHistory, createRouter } from "vue-router";

import Login from '../pages/Login';
import Home from '../pages/Home';

export const routes = [

    {
        name: 'login',
        path: '/login',
        component: Login
    },

    {
        name: '',
        path: '/',
        component: Home
    },

    {
        name: 'home',
        path: '/home',
        component: Home
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;
