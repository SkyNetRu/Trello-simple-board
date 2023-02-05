import Vue from 'vue'
import Vuex from 'vuex'
import BoardModule from './modules/board'
Vue.use(Vuex)

export default new Vuex.Store ({
    modules: {
        BoardModule
    }
});
