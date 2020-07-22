<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">

            <div class="wrapper_box">
                <NavbarAdmin></NavbarAdmin>
                <Drawer></Drawer>

                <!-- header -->
                <div class="mt-8 col-12">
                    <div class="title_header text-uppercase col-6 mb-10">Rotations Manager</div>
                    <v-row class="mb-4 mt-10 justify-content-around" no-gutters>
                        <div class="text-center  ml-4">
                            <v-btn outlined x-large tile color="success" dark @click="openVehicles">
                                <v-icon class="mr-5">directions_car</v-icon>
                                Vehicles
                            </v-btn>
                        </div>
                        <v-spacer></v-spacer>

                        <div class="text-center">
                            <v-btn outlined x-large tile color="success" dark @click="openCities">
                                <v-icon class="mr-5">mdi-city</v-icon>
                                Cities
                            </v-btn>
                        </div>
                        <v-spacer></v-spacer>

                        <div class="text-center">
                            <v-btn outlined x-large tile color="success" dark @click="openRotationsCategories">
                                <v-icon class="mr-5">directions</v-icon>
                                Rotations types
                            </v-btn>
                        </div>
                        <v-spacer></v-spacer>
                        <div class="text-center mr-6">
                            <v-btn outlined x-large tile color="success" dark @click="openRotations">
                                <v-icon class="mr-5">mdi-format-list-checks</v-icon>
                                Rotations
                            </v-btn>
                        </div>
                    </v-row>
                </div>
                <hr/>

                <div class="sentence">Pick a Rotation</div>
                <!-- progress -->
                <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
                <!-- table -->
                <div class="table-responsive" v-if="!isLoading">
                    <table class="table">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col" class="text-center text-uppercase">Rotations categories</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(rotationsCategory,i) in rotationsCategories" :key="i">
                            <td @click="openRotationsCategory(rotationsCategory)">
                                <v-btn color="info" block outlined tile>{{rotationsCategory.name}}</v-btn>
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
            <NavbarAdmin></NavbarAdmin>
            <div class="container px-4">

                <div class="">

                    <!-- header -->
                    <div class="mt-8 col-12">

                        <div
                            class=" text-uppercase text-center"
                            style="font-size: 2em;color: rgb(124, 124, 124);"
                        >
                            Rotations Manager
                        </div>
                        <hr>
                        <v-btn outlined x-large  block tile color="success" class="my-2" dark @click="openVehicles">
                            <v-icon class="mr-5">directions_car</v-icon>
                            Vehicles
                        </v-btn>
                        <v-btn outlined x-large block tile color="success"  class="my-2"  dark @click="openCities">
                            <v-icon class="mr-5">mdi-city</v-icon>
                            Cities
                        </v-btn>
                        <v-btn outlined x-large  block tile color="success"  class="my-2"  dark @click="openRotationsCategories">
                            <v-icon class="mr-5">directions</v-icon>
                            Rotations types
                        </v-btn>
                        <v-btn outlined x-large block tile color="success"  class="my-2"  dark @click="openRotations">
                            <v-icon class="mr-5">mdi-format-list-checks</v-icon>
                            Rotations
                        </v-btn>

                    </div>
                    <hr/>

                    <div class="sentence text-center my-4">Pick a Rotation</div>
                    <!-- progress -->
                    <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
                    <!-- table -->
                    <div class="table-responsive" v-if="!isLoading">
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col" class="text-center text-uppercase">Rotations categories</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(rotationsCategory,i) in rotationsCategories" :key="i">
                                <td @click="openRotationsCategory(rotationsCategory)">
                                    <v-btn color="info" block outlined tile>{{rotationsCategory.name}}</v-btn>
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
    import NavbarAdmin from "@/js/components/navbars/Dispatcher.vue";
    import Drawer from "@/js/components/drawers/Dispatcher.vue";

    export default {
        components: {
            NavbarAdmin,
            Drawer
        },
        created() {
            this.fetchItems();
        },
        data() {
            return {
                rotationsCategories: {},
                isLoading: true
            };
        },
        methods: {
            BASE_URL() {
                return this.$store.state.BASE_URL;
            },
            fetchItems() {
                this.isLoading = true;
                let page_url = this.BASE_URL() + "/api/dispatcher/rotationsCategories";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;
                axios
                    .get(page_url)
                    .then(res => {
                        this.rotationsCategories = res.data.data;
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
            openVehicles() {
                this.$router.push({name: "DispatcherDashboard_vehicles"});
            },
            openCities() {
                this.$router.push({name: "DispatcherDashboard_cities"});
            },
            openRotationsCategories() {
                this.$router.push({name: "DispatcherDashboard_rotationsCategories"});
            },
            openRotations() {
                this.$router.push({name: "DispatcherDashboard_rotations"});
            },
            openRotationsCategory(category) {
                this.$router.push({name: "DispatcherDashboard_rotations_categories", params: {id: category.id}});
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

    .sentence {
        margin-top: 2em;
        font-family: "Quicksand", sans-serif;
        justify-self: center;
        font-size: 2.5em;
    }
</style>
