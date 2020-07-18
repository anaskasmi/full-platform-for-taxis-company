<template>
    <div>
        <NavbarAdmin></NavbarAdmin>
        <Drawer></Drawer>


            <v-btn color="grey" text tile  class="float-left" :to="archivesRoute"> <v-icon class="mr-2">keyboard_return</v-icon>archives </v-btn>
            <v-btn color="red " outlined  class="float-right" @click="deleteArchive(archive.id,archive.name)">  <v-icon class="mr-2">delete</v-icon> delete </v-btn>

<div class="table-responsive">
            <div class="col-5">
            </div>
            <div class="col-5" >
            </div>
            <table class="table table-bordered table-hover">
                <tbody>
                   
                  <tr class="table-primary text-uppercase text-center font-weight-bold" >
                        <td colspan="2">archive info</td>
                  </tr>


                  <tr >
                        <td class="text-uppercase">Driver</td>

                        <td>{{archive.FirstName}} {{archive.LastName}} </td>
                  </tr>
                  <tr >
                        <td class="text-uppercase">Driver badge id</td>
                        <td>{{archive.badge_id}}</td>
                  </tr>
                  <tr>
                        
                        <td class="text-uppercase">Archive date</td>
                        <td>{{archive.created_at}}</td>
                  </tr>


                  <tr>
                        <td class="text-uppercase">LICENSE FILE</td>
                        <td>
                            <div v-if="archive.LICENSE_PATH">
                                <a :href="LICENSE_PATH_link()">
                                    <img :src="LICENSE_PATH_link()">
                                </a>
                            </div> 
                            <div v-if="!archive.LICENSE_PATH" class="text-muted"> - </div>

                        </td>
                  </tr>

                  <tr>
                        <td class="text-uppercase">PERMIT FILE</td>
                        <td>
                            <div v-if="archive.PERMIT_PATH">
                                <a :href="PERMIT_PATH_link()">
                                    <img :src="PERMIT_PATH_link()">
                                </a>
                            </div> 
                            <div v-if="!archive.PERMIT_PATH" class="text-muted"> - </div>
                        </td>
                  </tr>

                  <tr>
                        <td class="text-uppercase">TAXIHOST FILE</td>
                        <td>
                            <div v-if="archive.TAXIHOST_PATH">
                                <a :href="TAXIHOST_PATH_link()">
                                    <img :src="TAXIHOST_PATH_link()">
                                </a>
                            </div> 
                            <div v-if="!archive.TAXIHOST_PATH" class="text-muted"> - </div>
                        </td>
                  </tr>

                  <tr>
                        <td class="text-uppercase">ABSTRACT FILE</td>
                        <td>
                            <div v-if="archive.ABSTRACT_PATH">
                                <a :href="ABSTRACT_PATH_link()">
                                    <img :src="ABSTRACT_PATH_link()">
                                </a>
                            </div> 
                            <div v-if="!archive.ABSTRACT_PATH" class="text-muted"> - </div>
                        </td>
                  </tr>

                  <tr>
                        <td class="text-uppercase">SP FILE</td>
                        <td>
                            <div v-if="archive.SP_FILE_PATH">
                                <a :href="SP_FILE_PATH_link()">
                                    <img :src="SP_FILE_PATH_link()">
                                </a>
                            </div> 
                            <div v-if="!archive.SP_FILE_PATH" class="text-muted"> - </div>
                        </td>
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
axios.get(this.BASE_URL()+'/api/admin/archive/'+this.id)
        .then(res => {
            this.archive = res.data.data;
            
        })
        .catch(error => {
        console.log(error);
        })
    },
    afterCreate()
    {
                    console.log('after create is :');
            console.log(this.archive);

    },
    data() {
        return {
            archive:{},
            id:'',
            archivesRoute:{name : 'AdminDashboard_archives'},
        }
    },
    computed:{

      
      
            
    },
    methods: {
              BASE_URL()
      {
            return this.$store.state.BASE_URL;
      },
      LICENSE_PATH_link(){
            if(this.archive.LICENSE_PATH)
            return this.BASE_URL()+'/storage/'+this.archive.LICENSE_PATH;
            else
            return false;
      },
      PERMIT_PATH_link(){
            if(this.archive.PERMIT_PATH)
            return this.BASE_URL()+'/storage/'+this.archive.PERMIT_PATH;
            else
            return false;
      },
      TAXIHOST_PATH_link(){
            if(this.archive.TAXIHOST_PATH)
            return this.BASE_URL()+'/storage/'+this.archive.TAXIHOST_PATH;
            else
            return false;
      },
      ABSTRACT_PATH_link(){
            if(this.archive.ABSTRACT_PATH)
            return this.BASE_URL()+'/storage/'+this.archive.ABSTRACT_PATH;
            else
            return false;
      },
      SP_FILE_PATH_link(){
            if(this.archive.SP_FILE_PATH)
            return this.BASE_URL()+'/storage/'+this.archive.SP_FILE_PATH;
            else
            return false;
      },
        deleteArchive(id,name){
            this.$swal.fire({
                title: 'Are you sure?',
                text: "Archive "+name+" will be deleted !",
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
axios.delete(this.BASE_URL()+'/api/admin/archive/'+id)
                            .then(data => {
                                this.$router.push(this.archivesRoute);
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