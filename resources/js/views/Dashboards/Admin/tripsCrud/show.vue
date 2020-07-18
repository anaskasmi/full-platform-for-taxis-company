<template>
    <div>
        <NavbarAdmin></NavbarAdmin>
        <Drawer></Drawer>


            <v-btn color="grey" text tile  class="float-left" :to="tripsRoute"> <v-icon class="mr-2">keyboard_return</v-icon>trips </v-btn>
            <v-btn color="success" outlined  class="float-right mx-4" :to="{name : 'AdminDashboard_trips_edit',params:{id:id}}" > <v-icon class="mr-2">edit</v-icon> edit </v-btn>
            <v-btn color="red " outlined  class="float-right" @click="deleteTrip(trip.trip_id,trip.name)">  <v-icon class="mr-2">delete</v-icon> delete </v-btn>

<div class="table-responsive">

                  <br>
            <table class="table table-bordered table-hover">
                <tbody>
                  <tr class="table-primary text-uppercase text-center font-weight-bold" >
                        <td colspan="2">trip info</td>
                  </tr>
                  <tr >
                        <td class="text-uppercase">Trip id</td>
                        <td>{{trip.trip_id}}</td>
                  </tr>
                  <tr>
                        
                        <td class="text-uppercase">Job id</td>
                        <td> {{trip.job_id}}</td>
                  </tr>
                  <tr>
                        
                        <td class="text-uppercase">Date and time</td>
                        <td> {{trip.date}}</td>
                  </tr>
                  


                  <tr class="table-primary text-uppercase text-center font-weight-bold" >
                              <td colspan="2">driver info</td>
                        </tr>
                  <tr >
                        <td class="text-uppercase">Driver name</td>
                        <td>{{trip.FirstName}} {{trip.LastName}}</td>
                  </tr>
                  <tr>
                        <td class="text-uppercase">Driver badge id</td>
                        <td>{{trip.badge_id}}</td>
                  </tr>
                  <tr>
                        <td class="text-uppercase">Vehicle</td>
                        <td>{{trip.vehicle}}</td>
                  </tr>

                  <tr class="table-primary text-uppercase text-center font-weight-bold" >
                              <td colspan="2">customer info</td>
                        </tr>
                  <tr >
                  <tr>
                        <td class="text-uppercase">Customer name</td>
                        <td>{{trip.customer_name}}</td>
                  </tr>
                  <tr>
                        <td class="text-uppercase">Fare</td>
                        <td>{{trip.fare}}</td>
                  </tr>
                  <tr>
                        <td class="text-uppercase">Tip</td>
                        <td>{{trip.tip}}</td>
                  </tr>
                  <tr>
                        <td class="text-uppercase">Total</td>
                        <td>{{trip.total}}</td>
                  </tr>
                  <tr>
                        <td class="text-uppercase">Account</td>
                        <td>{{trip.account}}</td>
                  </tr>
                  <tr>
                        <td class="text-uppercase">PO</td>
                        <td>{{trip.po}}</td>
                  </tr>

                  <tr class="table-primary text-uppercase text-center font-weight-bold" >
                              <td colspan="2">Time and localisation info</td>
                        </tr>
                  <tr >
                  <tr>
                        <td class="text-uppercase">Pickup address</td>
                        <td>{{trip.pickup}}</td>
                  </tr>
                  <tr>
                        <td class="text-uppercase">Drop off address</td>
                        <td>{{trip.dropoff}}</td>
                  </tr>

                  <tr>
                        <td class="text-uppercase">Pickup date</td>
                        <td>{{trip.pickup_date}}</td>
                  </tr>
                  <tr>
                        <td class="text-uppercase">Dropoff date</td>
                        <td>{{trip.dropoff__date}}</td>
                  </tr>

                  
                </tbody>
            </table>
</div>
    </div>
</template>
<script>
import NavbarAdmin from '@/js/components/navbars/Admin.vue';
import Drawer from '@/js/components/drawers/Admin.vue';

export default {
    components: {
        NavbarAdmin,
        Drawer,
    },
    created(){
        this.id=this.$route.params.id;
        axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$store.state.token_admin;
axios.get(this.BASE_URL()+'/api/admin/trip/'+this.id)
        .then(res => {
            this.trip = res.data.data;
        })
        .catch(error => {
        console.log(error);
        })
    },
    data() {
        return {
            trip:{},
            id:'',
            tripsRoute:{name : 'AdminDashboard_trips'},
        }
    },
    computed:{
     
      profileImage(){
            if(this.trip.image)
            return this.BASE_URL()+'/storage/'+this.trip.image;
            else
            return false;
      },
      
      
            
    },
    methods: {
           BASE_URL()
      {
            return this.$store.state.BASE_URL;
      },
        deleteTrip(id,name){
            this.$swal.fire({
                title: 'Are you sure?',
                text: "Trip "+name+" will be deleted !",
                type: 'question',
                animation:false,
                focusConfirm:false,
                padding:"2rem",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#424242',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.value) {
        
                    axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$store.state.token_admin;
axios.delete(this.BASE_URL()+'/api/admin/trip/'+id)
                            .then(data => {
                                this.$router.push(this.tripsRoute);
                            })
                            .catch(err => console.log(err));
                            this.$swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                                )
                        }
                    })
        }
    },
}
</script>


<style scoped>
    a:hover{
        text-decoration: none;
    }
</style>