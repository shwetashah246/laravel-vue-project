<template>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">  
            <p class="lead">Leader Board</p>
            <div class="table-responsive">
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
export default {
    props:{
        leader:{
            type:Array,
            required:true
        },
    }
}
</script>
