<template>
<div>
      <div>
                            <v-alert 
                                border="left"
                                tile
                                dense
                                dark
                                color="primary "
                                elevation="2"
                                class="mt-12"
                                align="center"
                                
                            >
                               <v-icon class="mb-1" color="primary">description</v-icon> Weekly Shifts
                            </v-alert>
    </div>

                
                
                
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Vehicle id</th>
                        <th scope="col">Shift start time</th>
                        <th scope="col">Shift end time</th>
                    </tr>
                </thead>
                <tbody>

                                <tr
                                    v-for="(shift,i) in shifts"
                                    :key="i"
                                >
                                    <td v-if="shift.is_total_row=='0'">{{shift.FirstName}} {{shift.LastName}}</td>
                                    <td v-if="shift.is_total_row=='0'">{{shift.vehicle_id}}</td>
                                    <td v-if="shift.is_total_row=='0'">{{shift.shift_start_time}}</td>
                                    <td v-if="shift.is_total_row=='0'">{{shift.shift_end_time}}</td>

                                    <td  v-if="shift.is_total_row=='1'" class="table-primary text-uppercase" colspan="2"> vehicle id : {{shift.vehicle_id}}</td>
                                    <td  v-if="shift.is_total_row=='1'" class="table-primary text-uppercase" colspan="2"> Count : {{shift.count}}</td>
                                </tr>

                </tbody>
    </table>
    
    
    </div>
        <v-pagination 
            :total-visible="5"
            v-model="current_page"
            :length="last_page"
            @input="next"
        >
        </v-pagination>

  </div>
</template>
<script>
export default {
    created(){
        this.fetchItems()
    },
    props:{
        date:'',
    },
    data() {
        return {
            last_page:'',
            current_page:'',
            shifts:{},

        }
    },
    methods: {
        BASE_URL()
        {
            return this.$store.state.BASE_URL;
        },
        next (page)
        {
            this.fetchItems(this.BASE_URL()+'/api/admin/reports/WeeklyTaxiShifts/list?date='+this.date+'&page='+page);

        },
        fetchItems(URL)
        {
            axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$store.state.token_admin;
axios.get(URL || this.BASE_URL()+'/api/admin/reports/WeeklyTaxiShifts/list?date='+this.date)
            .then(res=>{
                this.shifts=res.data.shifts.data;
                this.last_page=res.data.shifts.last_page;
                this.current_page=res.data.shifts.current_page;
            }).catch(error=>{
                console.log('error in fetch shifts child weekly taxishifts: ' +error);
            })
        },
    },
}
</script>