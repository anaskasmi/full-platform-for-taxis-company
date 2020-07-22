<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">
            <div>
                <NavbarDispatcher></NavbarDispatcher>
                <Drawer></Drawer>
                <v-btn color="grey" text tile class="float-left" :to="authorizationFilesRoute">
                    <v-icon class="mr-2">keyboard_return</v-icon>Authorization Files
                </v-btn>
                <v-btn color="success" outlined class="float-right mx-4" :to="authorizationFilesRoute_edit">
                    <v-icon class="mr-2">edit</v-icon>edit
                </v-btn>
                <v-btn color="red " outlined class="float-right" @click="deleteAuthorizationFile()">
                    <v-icon class="mr-2">delete</v-icon>delete
                </v-btn>
                <div class="table-responsive">
                    <div class="col-5"></div>
                    <div class="col-5"></div>

                    <table class="table table-bordered table-hover">
                        <tbody>
                        <tr class="table-primary text-uppercase text-center font-weight-bold">
                            <td colspan="2">Document Information</td>
                        </tr>
                        <tr>
                            <td style="width: 30%;">Name</td>
                            <td>{{authorizationFile.first_name}} {{authorizationFile.last_name}}</td>
                        </tr>
                        <tr>
                            <td>Account Type</td>
                            <td>{{authorizationFile.account_type}}</td>
                        </tr>
                        <tr>
                            <td>Account Number</td>
                            <td>{{authorizationFile.account_number}}</td>
                        </tr>

                        <tr>
                            <td>Expiry date</td>
                            <td class="text-uppercase table-danger" v-if="expired">
                                {{authorizationFile.expiry_date}}
                                <span class="font-weight-bold mx-4">--EXPIRED !</span>
                            </td>
                            <td class="text-uppercase" v-if="!expired">{{authorizationFile.expiry_date}}</td>
                        </tr>

                        <tr>
                            <td>PO</td>
                            <td>{{authorizationFile.po_number}}</td>
                        </tr>

                        <tr class="table-primary text-uppercase text-center font-weight-bold">
                            <td colspan="2">Document Status</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>{{authorizationFile.status}}</td>
                        </tr>

                        <tr v-if="authorizationFile.updater">
                            <td>Updated By</td>
                            <td>{{authorizationFile.updater}}</td>
                        </tr>
                        <tr v-if="authorizationFile.comment">
                            <td>Comment</td>
                            <td
                                class="font-weight-bold font-italic"
                                style="color:orange"
                            >{{authorizationFile.comment}}</td>
                        </tr>

                        <tr v-if="!authorizationFile.updater">
                            <td>created by</td>
                            <td>{{authorizationFile.owner}}</td>
                        </tr>

                        <tr class="table-primary text-uppercase text-center font-weight-bold">
                            <td colspan="2">Document File</td>
                        </tr>

                        <tr class="text-center">
                            <td colspan="2">
                                <div class="col-12 ma-auto align-center" id="preview">
                                    <a :href="documentUrl" target="_blank">
                                        <embed v-if="fileExtention=='pdf'" :src="documentUrl" width="800px" height="800px" />
                                        <img v-else :src="documentUrl" alt class="shadow-lg p-3 mb-5 bg-white rounded" />
                                    </a>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->
        <!----------------------------Mobile Version---------------------------->
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->

        <div class="pa-0 ma-0 " v-if="$mq=='mobile'">
            <!-- nav -->
            <NavbarDispatcher></NavbarDispatcher>
            <div class="container px-4">
                <div>
                    <v-btn color="grey" text tile class="text-center"  block :to="authorizationFilesRoute">
                        <v-icon class="mr-2">keyboard_return</v-icon>Authorization Files
                    </v-btn>
                    <hr>
                    <v-btn color="success" outlined class="my-4" block :to="authorizationFilesRoute_edit">
                        <v-icon class="mr-2">edit</v-icon>edit
                    </v-btn>
                    <v-btn color="red " outlined class="" block @click="deleteAuthorizationFile()">
                        <v-icon class="mr-2">delete</v-icon>delete
                    </v-btn>
                    <div class="table-responsive">


                        <table class="table table-bordered table-hover table-striped my-4">
                            <tbody>
                            <tr class="table-primary text-uppercase text-center font-weight-bold">
                                <td colspan="2">Document Information</td>
                            </tr>
                            <tr>
                                <td class="fitCell align-middle">Name</td>
                                <td class="fitCell align-middle">{{authorizationFile.first_name}} {{authorizationFile.last_name}}</td>
                            </tr>
                            <tr>
                                <td class="fitCell align-middle">Account Type</td>
                                <td class="fitCell align-middle">{{authorizationFile.account_type}}</td>
                            </tr>
                            <tr>
                                <td class="fitCell align-middle">Account Number</td>
                                <td class="fitCell align-middle">{{authorizationFile.account_number}}</td>
                            </tr>

                            <tr>
                                <td class="fitCell align-middle">Expiry date</td>
                                <td class="text-uppercase table-danger" v-if="expired">
                                    {{authorizationFile.expiry_date}}
                                    <span class="font-weight-bold mx-4">--EXPIRED !</span>
                                </td>
                                <td class="text-uppercase" v-if="!expired">{{authorizationFile.expiry_date}}</td>
                            </tr>

                            <tr>
                                <td class="fitCell align-middle">PO</td>
                                <td class="fitCell align-middle">{{authorizationFile.po_number}}</td>
                            </tr>

                            <tr class="table-primary text-uppercase text-center font-weight-bold">
                                <td colspan="2" >Document Status</td>
                            </tr>
                            <tr>
                                <td class="fitCell align-middle">Status</td>
                                <td class="fitCell align-middle">{{authorizationFile.status}}</td>
                            </tr>

                            <tr v-if="authorizationFile.updater">
                                <td class="fitCell align-middle">Updated By</td>
                                <td class="fitCell align-middle">{{authorizationFile.updater}}</td>
                            </tr>
                            <tr v-if="authorizationFile.comment">
                                <td>Comment</td>
                                <td
                                    class="font-weight-bold font-italic"
                                    style="color:orange"
                                >{{authorizationFile.comment}}</td>
                            </tr>

                            <tr v-if="!authorizationFile.updater">
                                <td>created by</td>
                                <td>{{authorizationFile.owner}}</td>
                            </tr>

                            <tr class="table-primary text-uppercase text-center font-weight-bold">
                                <td colspan="2">Document File</td>
                            </tr>

                            <tr class="text-center">
                                <td colspan="2">
                                    <div class="col-12 ma-auto align-center" id="preview">
                                        <a :href="documentUrl" target="_blank">
                                            <embed v-if="fileExtention=='pdf'" :src="documentUrl" width="800px" height="800px" />
                                            <img v-else :src="documentUrl" alt class="shadow-lg p-3 mb-5 bg-white rounded" />
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>
<script>
import NavbarDispatcher from "@/js/components/navbars/Dispatcher.vue";
import Drawer from "@/js/components/drawers/Dispatcher.vue";

export default {
  components: {
    NavbarDispatcher,
    Drawer
  },
  created() {
    this.fetchItems();
  },
  data() {
    return {
      authorizationFile: {},
      id: "",
      authorizationFilesRoute: {
        name: "DispatcherDashboard_authorizationFiles"
      },
      authorizationFilesRoute_edit: {
        name: "DispatcherDashboard_authorizationFiles_edit",
        params: { id: this.$route.params.id }
      },
      filename: ""
    };
  },
  computed: {
    documentUrl() {
      return this.BASE_URL() + "/storage/" + this.authorizationFile.file_path;
    },
    expired() {
      var date = this.$moment(this.authorizationFile.expiry_date);
      var now = this.$moment();

      if (now > date) {
        return true;
      } else {
        return false;
      }
    },
    fileExtention()
    {
      if(this.authorizationFile && this.authorizationFile.file_path )
      {
        return this.authorizationFile.file_path.split(".").pop();

      }

    }
  },
  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    fetchItems() {
      this.id = this.$route.params.id;
      let page_url =
        this.BASE_URL() + "/api/dispatcher/authorizationFile/" + this.id;
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_dispatcher;
      axios
        .get(page_url)
        .then(res => {
          this.authorizationFile = res.data.data;
          this.isLoading = false;
        })
        .catch(error => {
          this.$swal("Try again", error.response.data.errors, "warning");
        });
    },
    deleteAuthorizationFile() {
      this.$swal
        .fire({
          title: "Are you sure?",
          text: "This Authorization File will be deleted !",
          type: "question",
          animation: false,
          focusConfirm: false,
          padding: "2rem",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#424242",
          confirmButtonText: "Yes, delete it!"
        })
        .then(result => {
          if (result.value) {
            axios.defaults.headers.common["Authorization"] =
              "Bearer " + this.$store.state.token_dispatcher;
            axios
              .delete(
                this.BASE_URL() + "/api/dispatcher/authorizationFile/" + this.id
              )
              .then(data => {
                this.$router.push(this.authorizationFilesRoute);
              })
              .catch(error => {
                console.log(error);
                this.isLoading = false;
                if (
                  error.response &&
                  error.response.data &&
                  error.response.data.errors
                ) {
                  {
                    this.$swal(
                      "Try again",
                      error.response.data.errors,
                      "warning"
                    );
                  }
                } else {
                  this.$swal(
                    "Try again",
                    "Unknown error has occurred ! ",
                    "warning"
                  );
                }
              });
          }
        });
    }
  }
};
</script>


<style scoped>
a:hover {
  text-decoration: none;
}
#preview {
  display: flex;
  justify-content: center;
  align-items: center;
}

#preview img {
  max-width: 900px;
  max-height: 100%;
}
.fitCell{
    max-width:100%;white-space:nowrap;
}
</style>
