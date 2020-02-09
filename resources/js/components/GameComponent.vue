<template>
    <div class="jumbotron">
        <h1 class="display-4">Hello {{uName}}!</h1>
        <!-- <p class="lead"></p>
        <hr class="my-4"> -->
        <form @submit="checkform">
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
                            <div class="p-2" v-for="c in cardlist" @click="cardfunc(c)">
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
                    <div class="form-group" v-if="machinecardlist.length>0">
                        <label>Machine cards</label>
                        <div class="d-flex flex-row">
                            <div class="p-2" v-for="c in machinecardlist">
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
                            <div class="p-2">  {{ score.user_score > score.machine_score ? 'Congratulations! you won.' : 'Oops! you lost.' }} </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">Play</button>
                    <button type="button" @click="init" v-if="score.user_score" class="btn btn-default btn-lg">Reset</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    computed:{
        uName : function (){
            return this.input.user_name!='' ? this.input.user_name : 'User'; 
        }
    },
    props:{
        errors:{
            type:Array,
            required:true
        },
        input:{
            type:Object,
            required:true
        },
        checkform:{
            type:Function,
            required:true
        },
        cardlist:{
            type:Array,
            required:true
        },
        machinecardlist:{
            type:Array,
            required:true
        },
        cardfunc:{
            type:Function,
            required:true
        },
        init:{
            type:Function,
            required:true
        },
        score:{
            type:Object,
            required:true
        },
    }
}
</script>
