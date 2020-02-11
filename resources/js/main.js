import GameComponent from './components/GameComponent.vue'
import LeaderListComponent from './components/LeaderListComponent.vue'
import Vue from 'vue'

window.Event = new Vue();

new Vue({
    el: '#vueApp',
    components: {
        'game-component':GameComponent,
        'leader-list-component':LeaderListComponent,
    },
    data:{
    },
    watch: {
    },
    mounted(){
    },
    computed:{
    },
    methods:{
    }
})