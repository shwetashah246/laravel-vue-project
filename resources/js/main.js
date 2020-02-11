import GameComponent from './components/GameComponent.vue'
import LeaderListComponent from './components/LeaderListComponent.vue'
import Vue from 'vue'
import axios from 'axios'

new Vue({
    el: '#vueApp',
    components: {
        'game-component':GameComponent,
        'leader-list-component':LeaderListComponent,
    },
    data:{
        errors:[],
        input:{
            user_name:'',
            user_cards:[],
            play:true,
        },
        cardlist:[],
        machineCardList:[],
        user:{},
        score:{},
        leader:[],
    },
    watch: {
    },
    mounted(){
        this.init();
        this.getLeader();
    },
    computed:{
    },
    methods:{
        checkForm: function (e) {

            e.preventDefault();
            this.errors = [];

            if (!this.input.user_name) {  this.errors.push('Please provide username.'); }
            if (this.input.user_cards.length==0) {  this.errors.push('Please select any cards'); }
            if(this.errors.length>0) {  return false; }

            this.input.play = false;
            axios.post(`/user`,this.input)
            .then(response => {
                //console.log(this.input.user_name,response);
                if(response.data.success) {
                    this.user = response.data.user;                   
                    this.machineCardList = response.data.machine_cards;      
                    this.score.user_score = response.data.user_score;             
                    this.score.machine_score = response.data.machine_score;   
                    this.input.play = true;
                    this.getLeader();        
                } 
            })
            .catch(e => {
                if(e.response.data.errors) this.errors = e.response.data.errors;
                this.input.play = true;
            })
            return false;
            
        },
        cardfunc: function (o) {

            o.selected = !o.selected;
            this.cardlist = this.cardlist.map(function(c) { return c.name === o.name ? o : c; }); 

            var index = this.input.user_cards.findIndex(c => c == o.name);
            //add if not found
            if(index ==-1 && o.selected){
                this.input.user_cards.push(o.name);
            }
            //delete if index found
            if(index > -1 && !o.selected){
                this.input.user_cards.splice(index,1);
            }             
        },
        init: function () {
            this.cardlist = [
                {  name:'2',   selected:false, },
                {  name:'3',   selected:false, },
                {  name:'4',   selected:false, },
                {  name:'5',   selected:false, },
                {  name:'6',   selected:false, },
                {  name:'7',   selected:false, },
                {  name:'8',   selected:false, },
                {  name:'9',   selected:false, },
                {  name:'10',  selected:false, },
                {  name:'J',   selected:false, },
                {  name:'Q',   selected:false, },
                {  name:'K',   selected:false, },
                {  name:'A',   selected:false, },
            ];
            this.errors = [];    
            this.input.user_cards = [];
            this.input.play = true;
            this.machineCardList=[];
            this.score={};       
        },
        getLeader: function () {
            axios.post(`/leader`,{})
            .then(response => {
                if(response.data.success) {
                    this.leader = response.data.leader;         
                } 
            });      
        }
    }
 
})