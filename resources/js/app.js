
require('./bootstrap');

window.Vue = require('vue');

import Vuex from 'vuex'
import storeVuex from './store/index'
import filter from './filter'

Vue.use(Vuex)

const store = new Vuex.Store(storeVuex)


Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('main-app', require('./components/MainApp.vue').default);


window.onload = function () {
    var app = new Vue({
        el: '#app',
        data: {
            currentActivity: 'home'
        },
        store
    });
}
