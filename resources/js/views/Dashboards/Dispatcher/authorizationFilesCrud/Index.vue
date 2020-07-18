<template>
  <div class="wrapper_box">
    <NavbarDispatcher></NavbarDispatcher>
    <Drawer></Drawer>
    <!-- modal viewer -->
    <v-row justify="center">
      <v-dialog v-model="openViewModel" class="text-center" max-width="600">
        <v-card>
          <embed v-if="fileExtention=='pdf'" :src="viewModelPath" width="600px" height="600px" />
          <img
          v-else
            :src="viewModelPath"
            alt
            class="shadow-lg p-3 mb-5 bg-white rounded"
            style="max-width:600px"
          />
        </v-card>
      </v-dialog>
    </v-row>
    <!-- end modal viewer -->
    <!-- header -->
    <div class="wrapper_header mt-10">
      <div class="title_header text-uppercase">Authorization Files</div>
      <v-btn
        link
        tile
        class="ma-2 button_header"
        outlined
        color="success "
        @click="addAuthorizationFile"
      >
        <v-icon left>mdi-pencil</v-icon>
        <div>New AuthorizationFile</div>
      </v-btn>
    </div>
    <hr />
    <!-- search start -->

    <!-- {{-- collapse button --}} -->
    <v-btn block outlined tile color="info" @click="showSearchOption = !showSearchOption">
      <v-icon color="info darken-1" class="mx-3">arrow_drop_down</v-icon>Search options
    </v-btn>
    <!-- {{-- end collapse button --}} -->

    <transition mode="out-in">
      <div class="card-deck" v-if="showSearchOption">
        <!-- {{-- search by Expiry Date --}} -->
        <div class="card bg-light">
          <div class="card-header">Search by Expiry Date</div>
          <div class="card-body text-center">
            <div class="input-group mb-4">
              <input
                type="date"
                v-model="searchByExpiryDate"
                max="3000-12-31"
                min="1000-01-01"
                class="form-control"
              />
              <!-- {{-- search button --}} -->
              <div class="input-group-prepend">
                <button
                  type="submit"
                  class="btn btn-dark"
                  @click.prevent="search(searchByExpiryDate,'searchByExpiryDate')"
                >
                  <v-icon dark small>search</v-icon>
                </button>
              </div>
            </div>
          </div>
        </div>
        <!-- {{-- end search by Expiry Date --}} -->

        <!-- {{-- search by Account Number --}} -->
        <div class="card bg-light">
          <div class="card-header">Search by Account Number</div>
          <div class="card-body text-center">
            <form>
              <div class="input-group mb-4">
                <!-- {{-- search box --}} -->
                <input
                  type="search"
                  v-model="searchByAccountNumber"
                  name="searchbydriver"
                  placeholder="search by Account Number"
                  aria-describedby="button-addon7"
                  class="form-control"
                />

                <!-- {{-- search button --}} -->
                <div class="input-group-prepend">
                  <button
                    type="submit"
                    class="btn btn-dark"
                    @click.prevent="search(searchByAccountNumber,'searchByAccountNumber')"
                  >
                    <v-icon dark small>search</v-icon>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <!-- {{-- end search by Account Number --}} -->

        <!-- {{-- search by Account Name --}} -->

        <div class="card bg-light">
          <div class="card-header">Search by Account Name</div>
          <div class="card-body text-center">
            <form>
              <div class="input-group mb-4">
                <!-- {{-- search box --}} -->
                <input
                  type="search"
                  v-model="searchByAccountName"
                  name="searchByAccountName"
                  placeholder="search by Account name"
                  aria-describedby="button-addon7"
                  class="form-control"
                />

                <!-- {{-- search button --}} -->
                <div class="input-group-prepend">
                  <button
                    type="submit"
                    class="btn btn-dark"
                    @click.prevent="search(searchByAccountName,'searchByAccountName')"
                  >
                    <v-icon dark small>search</v-icon>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- {{-- end search by Account Name --}} -->
      </div>
    </transition>

    <!-- search end -->
    <!-- progress -->
    <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
    <!-- table -->
    <div class="table-responsive" v-if="!isLoading">
      <table class="table">
        <thead class="thead-dark">
          <tr>
            <th scope="col">File</th>
            <th @click="sort('first_name')" scope="col">Name</th>
            <th @click="sort('account_type')" scope="col">Account Type</th>
            <th @click="sort('account_number')" scope="col">Account Number</th>
            <th @click="sort('po_number')" scope="col">PO</th>
            <th @click="sort('status')" scope="col">Status</th>
            <th @click="sort('expiry_date')" scope="col">Expiry date</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(authorizationFile,i) in sortedRows" :key="i">
            <td @click="viewDocument(authorizationFile.file_path)">
              <v-icon style="cursor:pointer">visibility</v-icon>
            </td>
            <td>{{authorizationFile.first_name}} {{authorizationFile.last_name}}</td>
            <td>{{authorizationFile.account_type}}</td>
            <td>{{authorizationFile.account_number}}</td>
            <td>{{authorizationFile.po_number}}</td>
            <td>{{authorizationFile.status}}</td>
            <td v-if="expired(authorizationFile)" class="table-danger">
              {{authorizationFile.expiry_date}}
              <span class="font-weight-bold mx-4">--EXPIRED !</span>
            </td>
            <td v-if="!expired(authorizationFile)">{{authorizationFile.expiry_date}}</td>
            <td class="text-right">
              <v-icon
                color="red"
                class="mx-1"
                @click="deleteAuthorizationFile(authorizationFile)"
              >delete</v-icon>
              <v-icon
                color="green"
                class="mx-1"
                @click="editAuthorizationFile(authorizationFile)"
              >edit</v-icon>
              <v-icon
                color="info"
                class="mx-1"
                @click="showAuthorizationFile(authorizationFile)"
              >description</v-icon>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <v-pagination :total-visible="5" v-model="current_page" :length="last_page" @input="next"></v-pagination>
  </div>
</template>
<script>
import NavbarDispatcher from "@/js/components/navbars/Dispatcher.vue";
import Drawer from "@/js/components/drawers/Dispatcher.vue";

export default {
  beforeMount() {
    this.fetchItems();
    this.searchQuery = null;
  },
  components: {
    NavbarDispatcher,
    Drawer
  },
  computed: {
    sortedRows: function() {
      return this.authorizationFiles.sort((a, b) => {
        let modifier = 1;
        if (this.currentSortDir === "desc") modifier = -1;
        if (a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
        if (a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
        return 0;
      });
    }
  },
  data() {
    return {
      authorizationFiles: [],
      current_page: 1,
      last_page: 1,
      noAuthorizationFilefound: false,
      searchValue: "",
      isLoading: true,
      openViewModel: false,
      viewModelPath: "",
      showSearchOption: false,
      searchByExpiryDate: "",
      searchByAccountNumber: "",
      searchByAccountName: "",
      noResultfound: false,
      searchQuery: null,
      currentSortDir: "",
      fileExtention:'',
    };
  },
  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    expired(authorizationFile) {
      var date = this.$moment(authorizationFile.expiry_date);
      var now = this.$moment();

      if (now > date) {
        return true;
      } else {
        return false;
      }
    },
    sort: function(s) {
      //if s == current sort, reverse
      if (s === this.currentSort) {
        this.currentSortDir = this.currentSortDir === "asc" ? "desc" : "asc";
      }
      this.currentSort = s;
    },
    search(searchValue, type) {
      if (searchValue == "") {
        this.fetchItems();
        this.noResultfound = false;
        return;
      }
      let vm = this;
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_dispatcher;
      axios
        .get(
          this.BASE_URL() +
            "/api/dispatcher/authorizationFiles/search?" +
            type +
            "=" +
            searchValue
        )
        .then(res => {
          if (res.data.data.length == 0) {
            this.noResultfound = true;
            this.$swal(
              "No Result found",
              "sorry we couldnt find what you are looking for ! ",
              "warning"
            );
            this.authorizationFiles = {};
            this.searchQuery = null;
          } else {
            console.log(res.data.meta);
            this.noResultfound = false;
            this.authorizationFiles = res.data.data;
          }
          this.searchQuery = type + "=" + searchValue;
          vm.makePagination(res.data.meta, res.data.links);
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
              this.$swal("Try again", error.response.data.errors, "warning");
            }
          } else {
            this.$swal("Try again", "Unknown error has occurred ! ", "warning");
          }
        });
    },

    fetchItems(page_url) {
      let vm = this;
      page_url =
        page_url || this.BASE_URL() + "/api/dispatcher/authorizationFiles";
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_dispatcher;
      axios
        .get(page_url)
        .then(res => {
          this.authorizationFiles = res.data.data;
          vm.makePagination(res.data.meta, res.data.links);
          this.isLoading = false;
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
              this.$swal("Try again", error.response.data.errors, "warning");
            }
          } else {
            this.$swal("Try again", "Unknown error has occurred ! ", "warning");
          }
        });
    },

    makePagination(meta, links) {
      this.last_page = meta.last_page;
      this.current_page = meta.current_page;
    },

    next(page) {
      if (this.searchQuery != null) {
        this.fetchItems(
          this.BASE_URL() +
            "/api/admin/authorizationFiles/search?" +
            this.searchQuery +
            "&page=" +
            page
        );
        // example of searchQuery : searchbydriver=979
      } else {
        this.fetchItems(
          this.BASE_URL() + "/api/dispatcher/authorizationFiles?page=" + page
        );
      }
    },

    addAuthorizationFile() {
      this.$router.push({ name: "DispatcherDashboard_authorizationFiles_add" });
    },

    showAuthorizationFile: function(authorizationFile) {
      this.$router.push({
        name: "DispatcherDashboard_authorizationFiles_show",
        params: { id: authorizationFile.id }
      });
    },
    editAuthorizationFile: function(authorizationFile) {
      this.$router.push({
        name: "DispatcherDashboard_authorizationFiles_edit",
        params: { id: authorizationFile.id }
      });
    },
    deleteAuthorizationFile: function(authorizationFile) {
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
              this.BASE_URL() +
              "/api/dispatcher/authorizationFile/" +
              authorizationFile.id;
            axios.defaults.headers.common["Authorization"] =
              "Bearer " + this.$store.state.token_dispatcher;
            axios
              .delete(url)
              .then(res => {
                this.fetchItems();
                this.$swal({
                  title: "Success",
                  text: "Authorization File Type Deleted successfully!",
                  type: "success",
                  timer: 2000
                });
              })
              .catch(error => {
                this.$swal("Try again", error.response.data.errors, "warning");
              });
          }
        });
    },
    viewDocument(path) {
      this.viewModelPath = this.BASE_URL() + "/storage/" + path;
      this.openViewModel = true;

        this.fileExtention = path.split(".").pop()

      
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