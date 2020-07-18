<template>
    <div>
                <!-- <v-btn
                
                
                link 
                text
                class="success  addbtn"
                >
                </v-btn> -->
                <v-btn 
                    link
                    tile
                    
                    class="ma-2" 
                    outlined 
                    color="success "
                    :to='button.link' 
                     
                >
                    <v-icon left>mdi-pencil</v-icon> {{button.text}}
                </v-btn>

    </div>
</template>


<script>
export default {
    computed:{
        button : function ()
        {
            return this.$store.state.adminDashboard.button;
        }
    }
}
</script>



<style>
    a, a:hover{
        text-decoration: none;
    }
     
</style>