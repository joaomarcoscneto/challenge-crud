import App from './App.vue';

require('./bootstrap');

window.Vue = require('vue').default;

const app = new Vue({
    el: '#app',
    render: h => h(App),
});
