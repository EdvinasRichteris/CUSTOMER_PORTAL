import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';

const app = createApp({});

import Home from './Home.vue';
import LoadList from './LoadList.vue';
import InvoiceList from './InvoiceList.vue';
import LoadDetails from './LoadDetails.vue';
import InvoiceListLoadDetails from './InvoiceListLoadDetails.vue';
import InvoiceDetails from './InvoiceDetails.vue';
import CommentDetails from './CommentDetails.vue';

axios.interceptors.request.use(function (config) {
    const token = localStorage.getItem('access_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, function (error) {
    return Promise.reject(error);
});
app.component('portal-home', Home);
app.component('load-list', LoadList);
app.component('invoice-list', InvoiceList);
app.component('load-details', LoadDetails);
app.component('invoice-list-load-details', InvoiceListLoadDetails);
app.component('invoice-details', InvoiceDetails);
app.component('comment-details', CommentDetails);



const routes = [
    { path: '/portal-home', component: Home },
    { path: '/load-list', component: LoadList },
    { path: '/invoice-list', component: InvoiceList },
    { path: '/load-details', component: LoadDetails },
    { path: '/invoice-details', component: InvoiceDetails },
    { path: '/comment-details', component: CommentDetails }

];

const router = createRouter({
    history: createWebHistory(),
    routes
});

app.use(router);

app.mount('#app');
