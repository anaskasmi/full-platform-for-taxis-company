<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">

            <div class="wrapper_box">
                <NavbarAdmin></NavbarAdmin>
                <Drawer></Drawer>

                <!-- header -->
                <div class="mt-8 col-12">
                    <div class="title_header text-uppercase col-6 mb-10">Pre Inspections</div>
                    <v-row class="mb-4 mt-10 justify-content-around" no-gutters>


                        <div class="text-center">
                            <v-btn v-if="showFilesByVehicle==false" outlined x-large tile color="success" dark
                                   @click="showPageByName('filesByVehicle')">
                                <v-icon class="mr-5">insert_drive_file</v-icon>
                                Files by Vehicle
                            </v-btn>
                            <v-btn v-if="showFilesByVehicle==true" x-large tile color="success" dark>
                                <v-icon class="mr-5">insert_drive_file</v-icon>
                                Files by Vehicle
                            </v-btn>
                        </div>
                        <v-spacer></v-spacer>

                        <div class="text-center">
                            <v-btn v-if="showFilesByDriver==false" outlined x-large tile color="success" dark
                                   @click="showPageByName('filesByDriver')">
                                <v-icon class="mr-5">folder_shared</v-icon>
                                Files By Driver
                            </v-btn>
                            <v-btn v-if="showFilesByDriver==true" x-large tile color="success" dark>
                                <v-icon class="mr-5">folder_shared</v-icon>
                                Files By Driver
                            </v-btn>
                        </div>
                        <v-spacer></v-spacer>
                        <div class="text-center mr-6">
                            <v-btn v-if="showAllFiles==false" outlined x-large tile color="success" dark
                                   @click="showPageByName('allFiles')">
                                <v-icon class="mr-5">folder_open</v-icon>
                                All Files
                            </v-btn>
                            <v-btn v-if="showAllFiles==true" x-large tile color="success" dark>
                                <v-icon class="mr-5">folder_open</v-icon>
                                All Files
                            </v-btn>
                        </div>
                    </v-row>
                </div>
                <hr/>


                <!-- content -->
                <div  class="col-12">
                    <filesByVehicle v-if="showFilesByVehicle"></filesByVehicle>
                    <filesByDriver v-if="showFilesByDriver"></filesByDriver>
                    <allFiles v-if="showAllFiles"></allFiles>
                </div>
            </div>
        </div>
    </div>


</template>
<script>
    import NavbarAdmin from "@/js/components/navbars/Dispatcher.vue";
    import Drawer from "@/js/components/drawers/Dispatcher.vue";
    import filesByVehicle from "@/js/views/Dashboards/Dispatcher/preInspectionsCrud/filesByVehicle.vue";
    import filesByDriver from "@/js/views/Dashboards/Dispatcher/preInspectionsCrud/filesByDriver.vue";
    import allFiles from "@/js/views/Dashboards/Dispatcher/preInspectionsCrud/allFiles.vue";

    export default {
        components: {
            NavbarAdmin,
            Drawer,
            filesByVehicle,
            filesByDriver,
            allFiles,
        },
        created() {
            // this.fetchItems();
            this.showPageByName("allFiles")

        },
        data() {
            return {
                showFilesByVehicle: false,
                showFilesByDriver: false,
                showAllFiles: false,
            };
        },
        methods: {
            BASE_URL() {
                return this.$store.state.BASE_URL;
            },
            hideAllTabs() {
                this.showFilesByVehicle = false;
                this.showFilesByDriver = false;
                this.showAllFiles = false;
            },

            showPageByName(name) {
                this.hideAllTabs();
                switch (name) {

                    case "filesByVehicle":
                        this.hideAllTabs();
                        this.showFilesByVehicle = true;
                        break;
                    case "filesByDriver":
                        this.hideAllTabs();
                        this.showFilesByDriver = true;
                        break;
                    case "allFiles":
                        this.hideAllTabs();
                        this.showAllFiles = true;
                        break;
                    default:
                        break;
                }
            },
            fetchItems() {

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
