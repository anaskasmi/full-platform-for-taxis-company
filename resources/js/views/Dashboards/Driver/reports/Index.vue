<template>

    <div class="wrapper_box">
        <NavbarDriver></NavbarDriver>
        <Drawer></Drawer>

        <!-- header -->
            <div class="wrapper_header mt-10">

                <div
                    class="title_header text-uppercase" 
                >   
                    Reports
                </div>
                
            </div>
                <hr>
                
                <div class="sentence">
                    Pick a report !
                </div>
                    <v-row
                    class="mb-6"
                    no-gutters
                    >

                            <div class="text-center ma-12">
                                
                                    <v-btn outlined x-large tile color="primary" dark @click="dailyLogSheet"><v-icon class="mr-5">description</v-icon> Daily Log sheet</v-btn>
                            </div>
                                  <v-spacer></v-spacer>

                            <div class="text-center ma-12">
                                    <v-btn outlined x-large tile color="primary" dark @click="DriversHours"><v-icon class="mr-5">description</v-icon>Driver's Hours</v-btn>
                            </div>
                    </v-row>

    </div>
</template>
<script>
import Drawer from '@/js/components/drawers/Driver.vue';
import NavbarDriver from '@/js/components/navbars/Driver.vue';

export default {

    components:{
        NavbarDriver,
        Drawer
    },
    methods: {
        dailyLogSheet(){
            this.$router.push({name: 'DriverDashboard_reports_dailyLogSheet'});
        },

        DriversHours(){
            this.$router.push({name: 'DriverDashboard_reports_weeklydriverHours_index'});
        },
    }
}
</script>
<style scoped>
    .wrapper_box{
        display: grid;
        grid-gap: 2em;
        grid-auto-rows: minmax(10px,auto);
    }
    .search_box{
        background-color: rgb(255, 51, 61);
    }
    .table_box{
        background-color: rgb(65, 35, 150);
    }
    .pagination_box{
        background-color: rgb(255, 153, 0);
    }
    a, a:hover{
        text-decoration: none;
    }
       .wrapper_header{
        display: grid;
        grid-template-columns: 10fr 2fr;
        grid-auto-rows: minmax(50px,auto);
        align-items: center;
        /* background-color: rgb(255, 153, 0); */

    }
    .title_header{
        justify-self: start;
        font-family: 'roboto';
        font-size: 3em;
        color:rgb(124, 124, 124);

    }
    .button_header{
        justify-self: end;
    }
      .sentence{
    margin-top: 2em;
    font-family: 'Quicksand', sans-serif;
    justify-self: center;
    font-size: 2.5em;
  }
</style>