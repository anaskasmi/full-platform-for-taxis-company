<template>
    <div>
        <NavbarAdmin></NavbarAdmin>
        <Drawer></Drawer>


            <v-btn color="grey" text tile  class="float-left" :to="dispatchersRoute"> <v-icon class="mr-2">keyboard_return</v-icon>dispatchers </v-btn>
            <v-btn color="success" outlined  class="float-right mx-4" :to="{name : 'AdminDashboard_dispatchers_edit',params:{id:id}}" > <v-icon class="mr-2">edit</v-icon> edit </v-btn>
            <v-btn color="red " outlined  class="float-right" @click="deleteDispatcher(dispatcher.id,dispatcher.name)">  <v-icon class="mr-2">delete</v-icon> delete </v-btn>

<div class="table-responsive">
            <div class="row col-12">

             <div class="col-5">
                        </div>
                        <div class="col-2" v-if="profileImage !== false">
                              <a :href="profileImage">
                                    <img :src="profileImage" alt=""  class="img-fluid img-thumbnail rounded ">
                              </a>
                        </div>
                        <div class="col-5">
                        </div>
                        
                        <div class="col-5" >
                              </div>
                              <div class="col-2" v-if="profileImage === false">
                                    <a :href="BASE_URL()+'/storage/uploads/IMAGES/man.png'">
                                          <img :src="BASE_URL()+'/storage/uploads/IMAGES/man.png'" alt=""  class="img-fluid rounded ">
                                    </a>
                              </div>
                              <div class="col-5">
                  </div> 
                  
                  </div>
                  <br>
            <table class="table table-bordered table-hover">
                  <tbody>

                  <tr>
                        <td style="width: 30%;">Name</td>
                        <td>{{dispatcher.name}}</td>
                  </tr>
                  <tr>

                        <td>EMAIL</td>
                        <td>{{dispatcher.email}}</td>
                  </tr>
                  <tr>

                        <td>created at</td>
                        <td>{{dispatcher.created_at}}</td>
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
axios.get(this.BASE_URL()+'/api/admin/dispatcher/'+this.id)
        .then(res => {
            this.dispatcher = res.data.data;
        })
        .catch(error => {
        console.log(error);
        })
    },
    data() {
        return {
            dispatcher:{},
            id:'',
            dispatchersRoute:{name : 'AdminDashboard_dispatchers'},
        }
    },
    computed:{
      
      profileImage(){
            if(this.dispatcher.image)
            return this.BASE_URL()+'/storage/'+this.dispatcher.image;
            else
            return false;
      },
      
      
            
    },
    methods: {
        BASE_URL()
      {
            return this.$store.state.BASE_URL;
      },
        deleteDispatcher(id,name){
            this.$swal.fire({
                title: 'Are you sure?',
                text: "Dispatcher "+name+" will be deleted !",
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
axios.delete(this.BASE_URL()+'/api/admin/dispatcher/'+id)
                            .then(data => {
                                this.$router.push(this.dispatchersRoute);
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