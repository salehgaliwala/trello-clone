// require('./bootstrap');
import Vue from 'vue'
import VueRouter from 'vue-router'
import Tasks from './views/Tasks'

    Vue.use(VueRouter)



    const router = new VueRouter({
        mode: 'history',
        routes: [
            {
                path: '/',
                name: 'tasks',
                component: Tasks
            }
        ]
    });
    
const app = new Vue({
        el: '#app',
        components: { Tasks },
        router,
    });