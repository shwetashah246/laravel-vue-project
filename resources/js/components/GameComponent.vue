<template>
    <div class="jumbotron" >
        <h1 class="display-4">Hello {{uName}}!</h1>
        <!-- <p class="lead"></p>
        <hr class="my-4"> -->
        <form @submit="checkForm">
            <p v-if="errors.length" class="error">
                <ul><li v-for="error in errors">{{ error }}</li></ul>
            </p>
            <div class="row">
                <div class="col-md-6 col-sm-8 col-xs-6">  
                    <div class="form-group">
                        <label for="">Enter username</label>
                        <input type="text" v-model="input.user_name" :maxlength="55" maxlength="55" class="form-control" placeholder="Enter Username">
                    </div>
                    <div class="form-group">
                        <label for="">Select cards</label>
                        <div class="d-flex flex-row">
                            <div class="p-2" v-for="c in cardlist" @click="cardFunc(c)">
                                <div :class="[c.selected ? 'card text-dark border-danger bg-danger' : 'card text-dark border-info bg-info']"> 
                                    <div class="card-body">
                                    <h5 class="card-title">{{ c.name }}</h5>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <div class="form-group" v-if="input.user_cards.length>0">
                        <label>Your cards</label>
                        <div class="d-flex flex-row">
                            <div class="p-2" v-for="c in input.user_cards">
                                <div class="card text-dark border-success bg-success"> 
                                    <div class="card-body">
                                    <h5 class="card-title">{{ c }}</h5>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <div class="form-group" v-if="machineCardList.length>0">
                        <label>Machine cards</label>
                        <div class="d-flex flex-row">
                            <div class="p-2" v-for="c in machineCardList">
                                <div class="card text-dark border-success bg-success"> 
                                    <div class="card-body">
                                    <h5 class="card-title">{{ c }}</h5>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <div class="form-group" v-if="score.user_score">
                        <div class="d-flex flex-column">
                            <div class="p-2"> Your score : {{ score.user_score }}</div>
                            <div class="p-2"> Machine score : {{ score.machine_score }} </div>
                            <div class="p-2">  {{ score.user_score > score.machine_score ? 'Congratulations! You Won.' : 'Sorry! You Lost.' }} </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg" :disabled="!input.play">
                        <span v-if="!input.play" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span v-else class="">Play</span>
                    </button>
                    <button type="button" @click="init" v-if="score.user_score" class="btn btn-default btn-lg">Reset</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>

import axios from 'axios'
export default {
    name: "GameComponent",
    data: function () {
        return {
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
        };
    },
    computed:{
        uName : function (){
            return this.input.user_name!='' ? this.input.user_name : 'User'; 
        }
    },
    mounted(){
        this.init();
    },
    props:{
    },
    methods:{
        checkForm: function (e) {

            e.preventDefault();
            this.errors = [];

            if (!this.input.user_name) {  this.errors.push('Please provide username.'); }
            if (this.input.user_cards.length==0) {  this.errors.push('Please select any cards'); }
            if (this.errors.length>0) {  return false; }

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
                    Event.$emit('getLeader'); 
                } 
            })
            .catch(e => {
                if(e.response.data.errors) this.errors = e.response.data.errors;
                this.input.play = true;
            })
            return false;
            
        },
        cardFunc: function (o) {

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
    }
}
</script>
