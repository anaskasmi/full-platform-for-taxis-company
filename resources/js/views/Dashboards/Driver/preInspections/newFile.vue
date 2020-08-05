<template>
        <div>
            <v-alert border="left" color="primary" dark class="font-weight-bold">
                soon
            </v-alert>
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
  data() {
    return {

    };
  },
  methods: {
    BASE_URL()
      {
            return this.$store.state.BASE_URL;
      },

  },
  computed: {

  }
};
</script>


<style scoped>
a:hover {
  text-decoration: none;
}
</style>
