<template>
  <div class="wrapper_box">
    <NavbarAdmin></NavbarAdmin>
    <Drawer></Drawer>
    <!-- header -->
    <div class="wrapper_header mt-10">
      <div class="title_header text-uppercase">Archives</div>
    </div>
    <hr />
    <!-- search start -->
    <div class="row">
      <div class="col-md-12 md-form active-cyan-2 mb-3" style="margin-bottom: -15px !important;">
        <form @submit.prevent="search(searchValue)">
          <div class="input-group mb-4">
            <div class="input-group-prepend">
              <button type="submit" class="btn btn-dark">
                <!-- <i class="fa fa-search"></i> -->
                <v-icon dark small>search</v-icon>
              </button>
            </div>
            <input
              type="search"
              name="searchValue"
              v-model="searchValue"
              placeholder="Search by name"
              aria-describedby="button-addon7"
              class="form-control"
            />
          </div>
        </form>
      </div>
    </div>
    <!-- search end -->

    <div class="table-responsive">
      <table class="table table-hover">
        <thead class="thead-dark">
          <tr class>
            <th scope="col">Archive id</th>
            <th scope="col">Driver</th>
            <th scope="col">Archive date</th>
            <th scope="col">LICENSE File</th>
            <th scope="col">PERMIT File</th>
            <th scope="col">TAXIHOST File</th>
            <th scope="col">ABSTRACT File</th>
            <th scope="col">SP FILE</th>
            <!-- <th class="text-center" scope="col">ACTIONS</th> -->
            <th class="text-center" scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="archive in archives" v-bind:key="archive.archive_id">
            <td>{{archive.archive_id}}</td>
            <td>{{archive.FirstName}} {{archive.LastName}}</td>
            <td>{{archive.created_at}}</td>

            <td>
              <div v-if="archive.LICENSE_PATH">
                <a :href="LICENSE_PATH_link(archive)">View</a>
              </div>
              <div v-if="!archive.LICENSE_PATH" class="text-muted">-</div>
            </td>

            <td>
              <div v-if="archive.PERMIT_PATH">
                <a :href="PERMIT_PATH_link(archive)">View</a>
              </div>
              <div v-if="!archive.PERMIT_PATH" class="text-muted">-</div>
            </td>

            <td>
              <div v-if="archive.TAXIHOST_PATH">
                <a :href="TAXIHOST_PATH_link(archive)">View</a>
              </div>
              <div v-if="!archive.TAXIHOST_PATH" class="text-muted">-</div>
            </td>

            <td>
              <div v-if="archive.ABSTRACT_PATH">
                <a :href="ABSTRACT_PATH_link(archive)">View</a>
              </div>
              <div v-if="!archive.ABSTRACT_PATH" class="text-muted">-</div>
            </td>

            <td>
              <div v-if="archive.SP_FILE_PATH">
                <a :href="SP_FILE_PATH_link(archive)">View</a>
              </div>
              <div v-if="!archive.SP_FILE_PATH" class="text-muted">-</div>
            </td>

            <td class="text-right">
              <v-icon
                color="red lighten-1"
                class="mx-1"
                @click="deleteArchive(archive.archive_id,archive.Name)"
              >delete</v-icon>
              <v-icon color="info" class="mx-1" @click="showArchive(archive)">person</v-icon>
            </td>
          </tr>
        </tbody>
      </table>
      <v-alert
        v-if="noArchivefound"
        border="left"
        tile
        type="warning"
        dense
        dark
        elevation="2"
        class="mb-12"
        align="center"
      >Sorry, No Archive Found !</v-alert>
    </div>

    <v-pagination :total-visible="5" v-model="current_page" :length="last_page" @input="next"></v-pagination>
  </div>
</template>
<script>
import NavbarAdmin from "@/js/components/navbars/Admin.vue";
import Drawer from "@/js/components/drawers/Admin.vue";

export default {
  beforeMount() {
    this.$store.state.adminDashboard.title.text = "Archives";
    this.$store.state.adminDashboard.button.text = "New Archive";
    this.fetchItems();
  },
  components: {
    NavbarAdmin,
    Drawer
  },
  data() {
    return {
      archives: [],
      current_page: 1,
      last_page: 1,
      noArchivefound: false,
      searchValue: ""
    };
  },

  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    LICENSE_PATH_link(archive) {
      if (archive.LICENSE_PATH)
        return this.BASE_URL() + "/storage/" + archive.LICENSE_PATH;
      else return false;
    },
    PERMIT_PATH_link(archive) {
      if (archive.PERMIT_PATH)
        return this.BASE_URL() + "/storage/" + archive.PERMIT_PATH;
      else return false;
    },
    TAXIHOST_PATH_link(archive) {
      if (archive.TAXIHOST_PATH)
        return this.BASE_URL() + "/storage/" + archive.TAXIHOST_PATH;
      else return false;
    },
    ABSTRACT_PATH_link(archive) {
      if (archive.ABSTRACT_PATH)
        return this.BASE_URL() + "/storage/" + archive.ABSTRACT_PATH;
      else return false;
    },
    SP_FILE_PATH_link(archive) {
      if (archive.SP_FILE_PATH)
        return this.BASE_URL() + "/storage/" + archive.SP_FILE_PATH;
      else return false;
    },
    search(searchValue) {
      if (searchValue == "") {
        this.fetchItems();
        this.noArchivefound = false;
        return;
      }
      let vm = this;
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(
          this.BASE_URL() +
            "/api/admin/archives/search?searchbydriver=" +
            searchValue
        )
        .then(res => {
          console.log(res);
          if (res.data.data.length == 0) {
            console.log("no drii");
            this.noArchivefound = true;
            this.archives = {};
          } else {
            this.noArchivefound = false;
            this.archives = res.data.data;
          }
          vm.makePagination(res.data.meta, res.data.links);
        })
        .catch(error => {
          console.log("search errors : ");
          console.log(error);
          this.inputErrors = error.response.data.errors;
          $("html, body").animate({ scrollTop: 0 }, "200");
        });
    },
    fetchItems(page_url) {
      let vm = this;
      page_url = page_url || this.BASE_URL()+"/api/admin/archives";
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(page_url)
        .then(res => {
          this.archives = res.data.data;
          vm.makePagination(res.data.meta, res.data.links);
        })
        .catch(error => {
          console.log(error);
        });
    },

    makePagination(meta, links) {
      this.last_page = meta.last_page;
      this.current_page = meta.current_page;
    },

    next(page) {
      this.fetchItems(this.BASE_URL()+"/api/admin/archives?page=" + page);
    },

    deleteArchive(id, name) {
      this.$swal
        .fire({
          title: "Are you sure?",
          text: "Archive " + name + " will be deleted !",
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
              "Bearer " + this.$store.state.token_admin;
            axios
              .delete(this.BASE_URL()+"/api/admin/archive/" + id)
              .then(data => {
                console.log(data);
                this.fetchItems();
              })
              .catch(err => console.log(err));
            this.$swal.fire("Deleted!", "Archive has been deleted.", "success");
          }
        });
    },

    showArchive: function(archive) {
      this.$router.push({
        name: "AdminDashboard_archives_show",
        params: { id: archive.archive_id }
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
  grid-template-columns: 10fr 2fr;
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