<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">

            <div class="wrapper_box">
                <NavbarDriver></NavbarDriver>
                <Drawer></Drawer>

                <!-- header -->
                <div class="wrapper_header mt-10">
                    <div
                        class="title_header text-uppercase"
                    >
                        Log Sheet
                    </div>

                </div>
                <hr/>
                <!-- <div class="sentence">
                                Pick a date !
                </div>-->

                <!-- tabs -->
                <v-tabs fixed-tabs background-color="light-blue darken-4" dark v-model="active_tab">
                    <v-tab @click="day" key="0">Daily Report</v-tab>

                    <v-tab @click="currentMonth" key="1">Current Month</v-tab>

                    <v-tab @click="pastMonth" key="2">Past Month</v-tab>

                    <v-tab @click="currentYear" key="3">Current year</v-tab>

                    <v-tab @click="pastYear" key="4">Past year</v-tab>
                </v-tabs>
                <!-- end tabs -->
                <day v-if="day_show" :idEncrypted="idEncrypted"></day>
                <currentMonth v-if="currentMonth_show" :idEncrypted="idEncrypted"></currentMonth>
                <pastMonth v-if="pastMonth_show" :idEncrypted="idEncrypted"></pastMonth>
                <currentYear v-if="currentYear_show" :idEncrypted="idEncrypted"></currentYear>
                <pastYear v-if="pastYear_show" :idEncrypted="idEncrypted"></pastYear>
            </div>
        </div>
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->
        <!----------------------------Mobile Version---------------------------->
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->

        <div class="pa-0 ma-0 " v-if="$mq=='mobile'">
            <!-- nav -->
            <NavbarDriver></NavbarDriver>
            <div class="container px-4">

                <div
                    class=" text-uppercase text-center"
                    style="font-size: 2em;color: rgb(124, 124, 124);"
                >
                    Log Sheet
                </div>


                <hr/>
                <div class="row col-12">
                    <v-btn class="ma-2 float-right block" tile outlined block color="light-blue darken-4">
                        <v-icon left>person</v-icon>
                        {{name}}
                    </v-btn>
                </div>

                <hr/>
                <v-alert

                    tile
                    dense
                    color="#2A3B4D"
                    elevation="2"
                    class="mt-12 font-weight-light"
                    align="center"
                >
                    <div class=" text-center text-white">PS :Swipe Left and Right to see all the available reports!
                    </div>


                </v-alert>
                <!-- tabs -->
                <v-tabs fixed-tabs background-color="light-blue darken-4" dark v-model="active_tab">
                    <v-tab @click="day" key="0">Daily Report</v-tab>

                    <v-tab @click="currentMonth" key="1">Current Month</v-tab>

                    <v-tab @click="pastMonth" key="2">Past Month</v-tab>

                    <v-tab @click="currentYear" key="3">Current year</v-tab>

                    <v-tab @click="pastYear" key="4">Past year</v-tab>
                </v-tabs>
                <!-- end tabs -->
                <day v-if="day_show" :idEncrypted="idEncrypted"></day>
                <currentMonth v-if="currentMonth_show" :idEncrypted="idEncrypted"></currentMonth>
                <pastMonth v-if="pastMonth_show" :idEncrypted="idEncrypted"></pastMonth>
                <currentYear v-if="currentYear_show" :idEncrypted="idEncrypted"></currentYear>
                <pastYear v-if="pastYear_show" :idEncrypted="idEncrypted"></pastYear>
            </div>
        </div>

    </div>


</template>
<script>
    import Drawer from "@/js/components/drawers/Driver.vue";
    import NavbarDriver from "@/js/components/navbars/Driver.vue";
    import day from "@/js/views/Dashboards/Driver/reports/dailyLogSheet/day.vue";
    import currentMonth from "@/js/views/Dashboards/Driver/reports/dailyLogSheet/currentMonth.vue";
    import pastMonth from "@/js/views/Dashboards/Driver/reports/dailyLogSheet/pastMonth.vue";
    import currentYear from "@/js/views/Dashboards/Driver/reports/dailyLogSheet/currentYear.vue";
    import pastYear from "@/js/views/Dashboards/Driver/reports/dailyLogSheet/pastYear.vue";

    export default {
        beforeMount() {
            this.fetchItems();
            // this.day();
            if (
                !this.day_show &&
                !this.currentMonth_show &&
                !this.pastMonth_show &&
                !this.currentYear_show &&
                !this.pastYear_show
            ) {
                this.day();
            }
        },
        components: {
            NavbarDriver,
            day,
            currentMonth,
            pastMonth,
            currentYear,
            pastYear,
            Drawer
        },
        data() {
            return {
                id: "",
                name: "",
                active_tabValue: ""
            };
        },
        computed: {
            active_tab: {
                get() {
                    if (this.day_show) {
                        return 0;
                    } else if (this.currentMonth_show) {
                        return 1;
                    } else if (this.pastMonth_show) {
                        return 2;
                    } else if (this.currentYear_show) {
                        return 3;
                    } else if (this.pastYear_show) {
                        return 4;
                    }
                },
                set(newValue) {
                    this.active_tabValue = newValue;
                }
            },
            backRoute() {
                return {name: "DriverDashboard_reports"};
            },
            idEncrypted() {
                return this.id * 2 + 2019;
            },
            day_show() {
                return this.$store.state.showDay;
            },
            currentMonth_show() {
                return this.$store.state.showCurrentMonth;
            },
            pastMonth_show() {
                return this.$store.state.showPastMonth;
            },
            currentYear_show() {
                return this.$store.state.showCurrentYear;
            },
            pastYear_show() {
                return this.$store.state.showPastYear;
            }
        },
        methods: {
            BASE_URL() {
                return this.$store.state.BASE_URL;
            },
            fetchItems() {
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_driver;
                axios
                    .get(this.BASE_URL() + "/api/driver/auth/user")
                    .then(res => {
                        console.log(res);
                        this.name = res.data.FirstName + " " + res.data.LastName;
                        this.id = res.data.PermitNumber;
                    })
                    .catch(err => {
                    });
            },
            hideAll() {
                this.day_show = false;
                this.currentMonth_show = false;
                this.pastMonth_show = false;
                this.currentYear_show = false;
                this.pastYear_show = false;
            },
            day() {
                this.$store.commit("showDay");
            },
            currentMonth() {
                this.$store.commit("showCurrentMonth");
            },
            pastMonth() {
                this.$store.commit("showPastMonth");
            },
            currentYear() {
                this.$store.commit("showCurrentYear");
            },
            pastYear() {
                this.$store.commit("showPastYear");
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
