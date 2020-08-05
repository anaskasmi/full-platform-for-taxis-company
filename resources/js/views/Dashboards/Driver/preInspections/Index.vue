<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">

            <div class="wrapper_box">
                <NavbarDriver></NavbarDriver>
                <Drawer></Drawer>

                <!-- header -->
                <div class="mt-8 col-12">
                    <div class="title_header text-uppercase col-6 mb-10">Pre Inspections</div>
                    <v-row class="mb-4 mt-10 justify-content-around" no-gutters>
                        <div class="text-center  ml-4">
                            <v-btn v-if="showVehicleIdentification==false" outlined x-large tile color="success" dark
                                   @click="showPageByName('vehicleIdentification')">
                                <v-icon class="mr-5">directions_car</v-icon>
                                Identify Vehicle
                            </v-btn>
                            <v-btn v-if="showVehicleIdentification==true" x-large tile color="success" dark>
                                <v-icon class="mr-5">directions_car</v-icon>
                                Identify Vehicle
                            </v-btn>
                        </div>
                        <v-spacer></v-spacer>

                        <div class="text-center">
                            <v-btn v-if="showFilesByVehicle==false" outlined x-large tile color="success" dark
                                   @click="showPageByName('filesByVehicle')">
                                <v-icon class="mr-5"> folder_open</v-icon>
                                Files by Vehicle
                            </v-btn>
                            <v-btn v-if="showFilesByVehicle==true" x-large tile color="success" dark>
                                <v-icon class="mr-5">folder_open</v-icon>
                                Files by Vehicle
                            </v-btn>
                        </div>
                        <v-spacer></v-spacer>
                        <div class="text-center">
                            <v-btn v-if="showFilesByDriver==false" outlined x-large tile color="success" dark
                                   @click="showPageByName('filesByDriver')">
                                <v-icon class="mr-5"> folder_open</v-icon>
                                Files By Driver
                            </v-btn>
                            <v-btn v-if="showFilesByDriver==true" x-large tile color="success" dark>
                                <v-icon class="mr-5">folder_open</v-icon>
                                Files By Driver
                            </v-btn>
                        </div>

                        <v-spacer></v-spacer>
                        <div class="text-center mr-6">
                            <v-btn v-if="showNewFile==false" outlined x-large tile color="success" dark
                                   @click="showPageByName('newFile')">
                                <v-icon class="mr-5">insert_drive_file</v-icon>
                                New Pre Inspection Slip
                            </v-btn>
                            <v-btn v-if="showNewFile==true" x-large tile color="success" dark>
                                <v-icon class="mr-5">insert_drive_file</v-icon>
                                New Pre Inspection Slip
                            </v-btn>
                        </div>
                    </v-row>
                </div>
                <hr/>


                <!-- content -->
                <div class="col-12">
                    <vehicleIdentification v-if="showVehicleIdentification"></vehicleIdentification>
                    <filesByVehicle v-if="showFilesByVehicle"></filesByVehicle>
                    <filesByDriver v-if="showFilesByDriver"></filesByDriver>
                    <newFile v-if="showNewFile"></newFile>
                </div>
            </div>
        </div>
    </div>


</template>
<script>
    import NavbarDriver from "@/js/components/navbars/Driver.vue";
    import Drawer from "@/js/components/drawers/Driver.vue";
    import vehicleIdentification from "@/js/views/Dashboards/Driver/preInspections/vehicleIdentification.vue";
    import filesByVehicle from "@/js/views/Dashboards/Driver/preInspections/filesByVehicle.vue";
    import filesByDriver from "@/js/views/Dashboards/Driver/preInspections/filesByDriver.vue";
    import newFile from "@/js/views/Dashboards/Driver/preInspections/newFile.vue";

    export default {
        components: {
            NavbarDriver,
            Drawer,
            vehicleIdentification,
            filesByVehicle,
            filesByDriver,
            newFile,
        },
        created() {
            // this.fetchItems();
            this.showPageByName("vehicleIdentification")

        },
        data() {
            return {
                showVehicleIdentification: false,
                showFilesByVehicle: false,
                showFilesByDriver: false,
                showNewFile: false,
            };
        },
        methods: {
            BASE_URL() {
                return this.$store.state.BASE_URL;
            },
            hideAllTabs() {
                this.showVehicleIdentification = false;
                this.showFilesByVehicle = false;
                this.showFilesByDriver = false;
                this.showNewFile = false;
            },

            showPageByName(name) {
                this.hideAllTabs();
                switch (name) {
                    case "vehicleIdentification":
                        this.hideAllTabs();
                        this.showVehicleIdentification = true;
                        break;
                    case "filesByVehicle":
                        this.hideAllTabs();
                        this.showFilesByVehicle = true;
                        break;
                    case "filesByDriver":
                        this.hideAllTabs();
                        this.showFilesByDriver = true;
                        break;
                    case "newFile":
                        this.hideAllTabs();
                        this.showNewFile = true;
                        break;
                    default:
                        break;
                }
            },
            fetchItems() {
                // this.isLoading = true;
                // let page_url = this.BASE_URL() + "/api/dispatcher/rotationsCategories";
                // axios.defaults.headers.common["Authorization"] =
                //     "Bearer " + this.$store.state.token_dispatcher;
                // axios
                //     .get(page_url)
                //     .then(res => {
                //         this.rotationsCategories = res.data.data;
                //         this.isLoading = false;
                //     })
                //     .catch(error => {
                //         console.log(error);
                //         this.isLoading = false;
                //         if (
                //
                //
                //             error.response &&
                //             error.response.data &&
                //             error.response.data.errors
                //         ) {
                //             {
                //                 this.$swal("Try again", error.response.data.errors, "warning");
                //             }
                //         } else {
                //             this.$swal("Try again", "Unknown error has occurred ! ", "warning");
                //         }
                //     });
            },

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
