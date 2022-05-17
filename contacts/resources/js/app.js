require('./bootstrap');
require('./util');

import Vue from 'vue';
Vue.component('maps', require('./components/maps.vue').default);

import VueTheMask from 'vue-the-mask';
Vue.use(VueTheMask);

if (window.componentVue) {
    for (var key in componentVue) {
        Vue.component(key, componentVue[key]);
    }
}

const app = new Vue({
    el: '#app',
});
window.vm = app;