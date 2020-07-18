<template>
  <div class="wrapper_box">
    <NavbarDispatcher></NavbarDispatcher>
    <Drawer></Drawer>
    <!-- start add dialog -->
    <v-dialog v-model="addDialogIsOpen"  max-width="500px">
      <v-card v-if="addDialogIsOpen">
        <v-card-text>
          <v-container>
            <div class="row float-right">
              <v-btn text color="black" class="float-right" @click="closeAddDialog()">
                <v-icon>close</v-icon>
              </v-btn>
            </div>
            <v-container>
              <v-row class="mt-12">
                <v-text-field
                  autocomplete="false"
                  class
                  label="Account Name"
                  required
                  v-model="AccountTypeName_addForm"
                  v-on:keyup.enter="addAccountType()"
                ></v-text-field>
              </v-row>
              <v-row>
                <v-btn color="info" class tile block @click="addAccountType()">Add</v-btn>
              </v-row>
            </v-container>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
    <!-- end add dialog -->

    <!-- start edit dialog -->
    <v-dialog v-model="editDialogIsOpen" max-width="500px">
      <v-card v-if="editDialogIsOpen" >
        <v-card-text>
          <v-container>
            <div class="row float-right">
              <v-btn text color="black" class="float-right" @click="closeEditDialog()">
                <v-icon>close</v-icon>
              </v-btn>
            </div>
            <v-container>
              <v-row class="mt-12">
                <v-text-field
                  autocomplete="false"
                  class
                  label="Account Name"
                  required
                  v-model="AccountTypeName_editForm"
                  v-on:keyup.enter="editAccountType()"
                ></v-text-field>
              </v-row>
              <v-row>
                <v-btn color="info" class tile block @click="editAccountType()">UPDATE</v-btn>
              </v-row>
            </v-container>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
    <!-- end edit dialig -->
    <!-- header -->
    <div class="wrapper_header mt-10">
      <div class="title_header text-uppercase">Account Types</div>
      <v-btn
        link
        tile
        class="ma-2 button_header"
        outlined
        color="success "
        @click="openAddDialog()"
      >
        <v-icon left>mdi-pencil</v-icon>
        <div>New Account Type</div>
      </v-btn>
    </div>
    <hr />
    <!-- progress -->
    <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
    <!-- table -->
    <div class="table-responsive" v-if="!isLoading">
      <table class="table">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Name</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(accountType,i) in accountTypes" :key="i">
            <td>{{accountType.name}}</td>
            <td class="text-right">
              <v-icon color="green" class="mx-1" @click="openEditDialog(accountType)">edit</v-icon>
              <v-icon color="red" class="mx-1" @click="deleteAccountType(accountType)">delete</v-icon>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script>
import NavbarDispatcher from "@/js/components/navbars/Dispatcher.vue";
import Drawer from "@/js/components/drawers/Dispatcher.vue";

export default {
  beforeMount() {
    this.fetchItems();
  },
  components: {
    NavbarDispatcher,
    Drawer
  },
  data() {
    return {
      accountTypes: [],
      noAccountTypefound: false,
      addDialogIsOpen: false,
      editDialogIsOpen: false,
      editedId:'',
      AccountTypeName_addForm: "",
      AccountTypeName_editForm: "",
      isLoading: true
    };
  },
  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    fetchItems() {
      let url = this.BASE_URL() + "/api/dispatcher/accountTypes";
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_dispatcher;
      axios
        .get(url)
        .then(res => {
          this.accountTypes = res.data.data;
          this.isLoading = false;
          if (!this.accountTypes) {
            this.noAccountTypefound = true;
          }
        })
        .catch(error => {
          this.$swal("Try again", error.response.data.errors, "warning");
        });
    },
    openAddDialog() {
      this.addDialogIsOpen = true;
    },
    closeAddDialog() {
      this.addDialogIsOpen = false;
      this.AccountTypeName_addForm="";
    },
    
    openEditDialog(accountType) {
      this.editDialogIsOpen = true;
      this.editedId = accountType.id;
    },
    closeEditDialog(accountType) {
      this.editDialogIsOpen = false;
      this.editedId = null;
      this.AccountTypeName_editForm= null;
    },

    addAccountType() {
      if (
        !this.AccountTypeName_addForm ||
        this.AccountTypeName_addForm == " "
      ) {
        this.$swal({
          title: "Invalid enteries !",
          text: "please make sure the input is Filled",
          type: "warning",
          timer: 3000
        });
        return;
      }
      let url = this.BASE_URL() + "/api/dispatcher/accountType";
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_dispatcher;
      axios
        .post(url, { name: this.AccountTypeName_addForm })
        .then(res => {
          this.$swal({
            title: "Success",
            text: "Account Type added successfully!",
            type: "success",
            timer: 2000
          });
          this.fetchItems();
          this.closeAddDialog();
        })
        .catch(error => {
          this.$swal("Try again", error.response.data.errors, "warning");
        });
    },
    editAccountType() {
      let url =
        this.BASE_URL() + "/api/dispatcher/accountType/" + this.editedId;
      let data = { name: this.AccountTypeName_editForm, _method: "put" };
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_dispatcher;
      axios
        .post(url, data)
        .then(res => {
          this.$swal({
            title: "Success",
            text: "Account Type Updated successfully!",
            type: "success",
            timer: 2000
          });
          this.fetchItems();
          this.closeEditDialog();
        })
        .catch(error => {
          this.$swal("Try again", error.response.data.errors, "warning");
        });
    },
    deleteAccountType(accountType) {
      this.$swal
        .fire({
          text: "Are You Sure You Want To Delete This Account?",
          type: "question",
          animation: true,
          focusConfirm: false,
          padding: "2rem",
          showCancelButton: true,
          confirmButtonText: "Yes"
        })
        .then(res => {
          if (res.value) {
            let url =
              this.BASE_URL() + "/api/dispatcher/accountType/" + accountType.id;
            axios.defaults.headers.common["Authorization"] =
              "Bearer " + this.$store.state.token_dispatcher;
            axios
              .delete(url)
              .then(res => {
                this.fetchItems();
                this.$swal({
                  title: "Success",
                  text: "Account Type Deleted successfully!",
                  type: "success",
                  timer: 2000
                });
              })
              .catch(error => {
                this.$swal("Try again", error.response.data.errors, "warning");
              });
          }
        });
    }
  }
};
</script>
<style scoped>
.wrapper_box {
  display: grid;
  grid-gap: 2em;
  grid-auto-rows: minmax(10px, auto);
}
.search_box {
  background-color: rgb(255, 51, 61);
}
.table_box {
  background-color: rgb(65, 35, 150);
}
.pagination_box {
  background-color: rgb(255, 153, 0);
}
a,
a:hover {
  text-decoration: none;
}
.wrapper_header {
  display: grid;
  grid-template-columns: 10fr 4fr;
  grid-auto-rows: minmax(50px, auto);
  align-items: center;
  /* background-color: rgb(255, 153, 0); */
}
.title_header {
  justify-self: start;
  font-family: "roboto";
  font-size: 3em;
  color: rgb(124, 124, 124);
}
.button_header {
  justify-self: end;
}
</style>