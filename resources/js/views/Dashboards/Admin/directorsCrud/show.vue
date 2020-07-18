<template>
    <div>
        <NavbarAdmin></NavbarAdmin>
        <Drawer></Drawer>


            <v-btn color="grey" text tile  class="float-left" :to="directorsRoute"> <v-icon class="mr-2">keyboard_return</v-icon>directors </v-btn>
            <v-btn color="success" outlined  class="float-right mx-4" :to="{name : 'AdminDashboard_directors_edit',params:{id:id}}" > <v-icon class="mr-2">edit</v-icon> edit </v-btn>
            <v-btn color="red " outlined  class="float-right" @click="deleteDirector(director.id,director.name)">  <v-icon class="mr-2">delete</v-icon> delete </v-btn>

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
                        <td>{{director.name}}</td>
                  </tr>
                  <tr>

                        <td>EMAIL</td>
                        <td>{{director.email}}</td>
                  </tr>
                  <tr>

                        <td>created at</td>
                        <td>{{director.created_at}}</td>
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
axios.get(this.BASE_URL()+'/api/admin/director/'+this.id)
        .then(res => {
            this.director = res.data.data;
        })
        .catch(error => {
        console.log(error);
        })
    },
    data() {
        return {
            director:{},
            id:'',
            directorsRoute:{name : 'AdminDashboard_directors'},
        }
    },
    computed:{
     
      profileImage(){
            if(this.director.image)
            return this.BASE_URL()+'/storage/'+this.director.image;
            else
            return false;
      },
      
      
            
    },
    methods: {
         BASE_URL()
      {
            return this.$store.state.BASE_URL;
      },
        deleteDirector(id,name){
            this.$swal.fire({
                title: 'Are you sure?',
                text: "Director "+name+" will be deleted !",
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
axios.delete(this.BASE_URL()+'/api/admin/director/'+id)
                            .then(data => {
                                this.$router.push(this.directorsRoute);
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