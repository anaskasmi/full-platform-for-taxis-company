<template>
  <div class="text-center align-center mt-12 tile">
    <v-btn color="info">
    will be deployed soon!
    </v-btn>

  </div>
</template>
<script>

export default {

  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    }
  }
};
</script>
<style scoped>
.wrapper_box {
  display: grid;
  grid-gap: 0.2em;
  grid-auto-rows: minmax(10px, auto);
}

a,
a:hover {
  text-decoration: none;
}
.wrapper_header {
  display: grid;
  grid-template-columns: 10fr 0fr;
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
.sentence {
  margin-top: 2em;
  font-family: "Quicksand", sans-serif;
  justify-self: center;
  font-size: 2.5em;
}
</style>