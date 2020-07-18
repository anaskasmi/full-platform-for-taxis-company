<template>
  <div class>
    <NavbarDirector></NavbarDirector>
    <Drawer></Drawer>
<div>
    <v-btn color="grey" text tile class="float-left" :to="commentsRoute">
      <v-icon class="mr-2">keyboard_return</v-icon>special Shifts
    </v-btn>
    <v-btn color="success" outlined class="float-right mx-4" :to="commentsRoute_edit">
      <v-icon class="mr-2">edit</v-icon>edit
    </v-btn>
    <v-btn color="red " outlined class="float-right" @click="deleteComment(comment.id)">
      <v-icon class="mr-2">delete</v-icon>delete
    </v-btn>
    </div>
    <br>
    <br>
    <hr />

    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <tbody>
          <tr>
            <td style="width: 30%;">ID</td>
            <td>{{comment.id}}</td>
          </tr>

          <tr>
            <td>Comment</td>
            <td>{{comment.comment}}</td>
          </tr>

          <tr>
            <td>Date</td>
            <td>{{comment.date}}</td>
          </tr>
          <tr>
            <td>Created at</td>
            <td>{{comment.created_at}}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script>
import NavbarDirector from "@/js/components/navbars/Director.vue";
import Drawer from "@/js/components/drawers/Director.vue";

export default {
  components: {
    NavbarDirector,
    Drawer
  },
  created() {
    this.id = this.$route.params.id;
    this.fetchItems();
  },
  data() {
    return {
      commentsRoute: { name: "" },
      comment: {
        comment: "",
        date: "",
        id: ""
      },
      modal: false,
      id: "",
      commentsRoute: {
        name: "DirectorDashboard_comments"
      }
    };
  },
  computed: {
    commentsRoute_edit() {
      return {
        name: "DirectorDashboard_comments_edit",
        params: { id: this.comment.id }
      };
    }
  },
  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    fetchItems() {
      this.id = this.$route.params.id;
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_director;
      axios
        .get(this.BASE_URL() + "/api/director/comment/" + this.id)
        .then(response => {
          console.log(response.data.data);
          this.comment = response.data.data;
        })
        .catch(error => {
          //   this.inputErrors = error.response.data.errors;
          console.log(error);
          $("html, body").animate({ scrollTop: 0 }, "200");
        });
    },

    deleteComment(id) {
      this.$swal
        .fire({
          title: "Are you sure?",
          text: "Comment of ID " + id + " will be deleted !",
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
              "Bearer " + this.$store.state.token_director;
            axios
              .delete(this.BASE_URL()+"/api/director/comment/" + id)
              .then(data => {
                this.$router.push({name:"DirectorDashboard_comments"})

              })
              .catch(err => console.log(err));
            this.$swal.fire("Deleted!", "Comment has been deleted.", "success");
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