<template>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">  
            <p class="lead">Leader Board</p>
			<div class="d-flex justify-content-center" v-if="loader">
				<div class="spinner-grow text-primary" role="status">
					<span class="sr-only">Loading...</span>
				</div>
			</div>
            <div class="table-responsive" v-else>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Username</th>
                            <th scope="col">Total game</th>
                            <th scope="col">Total Won</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="o in leader">
                            <td>{{ o.user_name }}</td>
                            <td>{{ o.total_games }}</td>
                            <td>{{ o.total_won }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
export default {
    name: "GameComponent",
    data: function () {
        return {
            leader:[],
            loader:true,
        };
    },
    mounted(){
        this.getLeader();
    },
    created(){
        Event.$on('getLeader', ()=> this.getLeader() );
    },
    props:{
    },
    methods:{
        getLeader: function () {
        	this.loader = true;
            axios.post(`/leader`,{})
            .then(response => {
                if(response.data.success) {
                    this.leader = response.data.leader;         
                } 
                this.loader = false;
            })
            .catch(e => {
                this.loader = false;
            });      
        }
    }
}
</script>
