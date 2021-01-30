require('./bootstrap');

import Vue from 'vue'
import Buefy from 'buefy'
import subscribers from "./components/subscribers";
import synchronize from "./components/synchronize";
import index from "./index";

Vue.use(Buefy);

Vue.component('subscribers', subscribers);
Vue.component('synchronize', synchronize);
Vue.component('index', index);

new Vue({
    el: '#app'
});
