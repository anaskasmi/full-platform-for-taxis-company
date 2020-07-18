<template>
  <v-form>
    <div class="wrapper_box">
            <div class="input_box">
                <v-text-field v-model="searchValue">
                    <template v-slot:label>
                    find a <strong>driver</strong> by his name or Badge ID
                    </template>
                </v-text-field>
            </div>
            <div class="icon_box">
 
                <v-icon size="31" color="light-blue">search</v-icon>

            </div>
    </div>
  </v-form>
</template>


<script>
export default {
    
    data() {
        return {
            searchValue:''
        }
    },
    
}
</script>



<style scoped>
    .wrapper_box{
        display: grid;
        grid-template-columns: 15fr 1fr; 
        justify-items: stretch;
        align-items: center;
    }
    .icon_box{
        align-self: center;
        justify-self: center;
    }
</style>