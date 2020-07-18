<template>
  <v-navigation-drawer app clipped permanent v-if="show">
    <v-list-item>
      <v-list-item-content>
        <v-avatar size="130" @click="visit">
          <img class="shadow-sm" :src="image" />
        </v-avatar>
      </v-list-item-content>
    </v-list-item>

    <v-list-item>
      <v-list-item-content>
        <v-list-item-title class="title text-uppercase text-center">{{name}}</v-list-item-title>

        <v-list-item-subtitle class="pt-2 text-center">
          <v-btn class="auto" depressed rounded small color="info">{{role}}</v-btn>
        </v-list-item-subtitle>
      </v-list-item-content>
    </v-list-item>

    <v-divider></v-divider>
    <v-list-item>
      <v-list-item-content>
        <v-list-item-subtitle class="pt-2 text-center">
          <v-btn class="auto" depressed tile outlined small color="info" id="notButton1">{{email}}</v-btn>
        </v-list-item-subtitle>
      </v-list-item-content>
    </v-list-item>

    <v-list dense nav>
      <v-list-item v-for="item in items" :key="item.title" link :to='item.link'>
        <v-list-item-icon>
          <v-icon>{{ item.icon }}</v-icon>
        </v-list-item-icon>

        <v-list-item-content>
          <v-list-item-title>{{ item.title }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list>
    <template v-slot:append>
      <div class="pa-2">
        <v-btn tile color="light-blue darken-4" dark block @click="logout()">Log out</v-btn>
      </div>
    </template>
  </v-navigation-drawer>
</template>
<script>
export default {
  created() {
    this.fetchItems();
  },
  data() {
    return {
      items: [
        { title: "Profile", icon: "person",link:{ name: 'DirectorDashboard_profile'} },
        { title: "Change Passowrd", icon: "vpn_key",link:{ name: 'DirectorDashboard_changePassword'} }
      ],
      right: null,
      role: "Director",
      name: "",
      image: "",
      email: ""
    };
  },
  computed: {
    show() {
      return this.$store.state.showDrawerDirector;
    }
  },
  methods: {
    BASE_URL()
      {
            return this.$store.state.BASE_URL;
      },
    changeCursor() {
      document.getElementById("notButton1").style.cursor = "default";
    },

    logout() {
      this.$store
        .dispatch("destroyToken_director")
        .then(response => {
          this.$router.push({ name: "LoginPage" });
        })
        .catch(error => {
          console.log(error);
        });
    },
    visit() {
      console.log(this.image);
    },
    fetchItems() {
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_director;

      axios
        .get(this.BASE_URL()+"/api/director/auth/user")
        .then(res => {
          
          this.name = res.data.name;
 if(res.data.image !== '' && res.data.image !== null )
          {
            this.image = this.BASE_URL()+"/storage/" + res.data.image;
          }
          else{
            this.image = this.BASE_URL()+"/storage/uploads/IMAGES/man.png";
          }          this.email = res.data.email;
        })
        .catch(err => {});
    }
  }
};
</script>
<style  scoped>
.auto {
  cursor: auto;
}
a:hover{
  text-decoration: none;
}
</style>