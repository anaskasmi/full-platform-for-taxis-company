<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">
            <div>
                <!-- date picker -->
                <!-- button to toggle the the date picker -->
                <div class="row col-12">
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <a
                                :href="BASE_URL() +
          '/api/driver/reports/pastYear/' +
          idEncrypted +
          '/printPDF/'"
                                target="_blank"
                            >
                                <v-btn
                                    v-on="on"
                                    depressed
                                    fab
                                    small
                                    color="success"
                                    class="mr-2 my-auto"
                                    @click="load()"
                                >
                                    <v-icon small color="white lighten-4">save_alt</v-icon>
                                </v-btn>
                            </a>
                        </template>
                        <span>download this report as PDF</span>
                    </v-tooltip>
                </div>
                <!-- table -->

                <div class="table-responsive">
                    <br/>
                    <table class="table table-bordered table-hover">
                        <tbody>
                        <tr class="table-primary text-uppercase text-center font-weight-bold">
                            <td>Months</td>
                            <td>Fare</td>
                            <td>Tip</td>
                            <td>Total</td>
                        </tr>
                        <tr v-for="(month, index) in months" :key="index">
                            <td class="text-uppercase font-weight-bold">{{getMonthName(index)}}</td>
                            <td>${{getMonthFare(index)}}</td>
                            <td>${{getMonthTip(index)}}</td>
                            <td>${{getMonthTotal(index)}}</td>
                            <td @click="showMonthShifts(index)">
                                <v-icon class="pa-0 ma-0" style="cursor: pointer;" color="primary">description</v-icon>
                            </td>
                        </tr>
                        <tr>
                            <td class="table-success text-uppercase text-center font-weight-bold">Totals</td>
                            <td>${{total_fare}}</td>
                            <td>${{total_tip}}</td>
                            <td>${{total_total}}</td>
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
            <div>
                <!-- date picker -->
                <!-- button to toggle the the date picker -->
                <div class="row col-12">
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <a
                                :href="BASE_URL() +
          '/api/driver/reports/pastYear/' +
          idEncrypted +
          '/printPDF/'"
                                target="_blank"
                            >
                                <v-btn
                                    v-on="on"
                                    depressed
                                    fab
                                    small
                                    color="success"
                                    class="mr-2 my-auto"
                                    @click="load()"
                                >
                                    <v-icon small color="white lighten-4">save_alt</v-icon>
                                </v-btn>
                            </a>
                        </template>
                        <span>download this report as PDF</span>
                    </v-tooltip>
                </div>
                <!-- table -->

                <div class="table-responsive">
                    <br/>
                    <table class="table table-bordered table-hover">
                        <tbody>
                        <tr class="table-primary text-uppercase text-center font-weight-bold">
                            <td>Months</td>
                            <td>Fare</td>
                            <td>Tip</td>
                            <td>Total</td>
                        </tr>
                        <tr v-for="(month, index) in months" :key="index">
                            <td class="text-uppercase font-weight-bold">{{getMonthName(index)}}</td>
                            <td class="fitCell" >${{getMonthFare(index)}}</td>
                            <td class="fitCell">${{getMonthTip(index)}}</td>
                            <td class="fitCell">${{getMonthTotal(index)}}</td>
                            <td  @click="showMonthShifts(index)">
                                <v-icon class="pa-0 ma-0" style="cursor: pointer;" color="primary">description</v-icon>
                            </td>
                        </tr>
                        <tr>
                            <td class="table-success text-uppercase text-center font-weight-bold">Totals</td>
                            <td class="fitCell">${{total_fare}}</td>
                            <td class="fitCell">${{total_tip}}</td>
                            <td class="fitCell">${{total_total}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</template>
<script>
    export default {
        beforeMount() {
            this.getReport();
            this.$store.commit("showPastYear");
        },
        data() {
            return {
                date: new Date().toISOString().substr(0, 10),
                months: {},
                driver: {},
                name: "",
                total_fare: 0,
                total_tip: 0,
                total_total: 0
            };
        },
        props: {
            idEncrypted: ""
        },
        methods: {
            BASE_URL() {
                return this.$store.state.BASE_URL;
            },
            load() {
                this.$swal.fire("", "Your PDF is Ready!", "success");
            },
            pdf() {
                this.$swal.fire("", "loading..", "success");
                axios
                    .get(
                        this.BASE_URL() +
                        "/api/driver/reports/pastYear/" +
                        this.id +
                        "/printPDF/"
                    )
                    .then(res => {
                        this.$swal.fire("", "Your PDF is ready !", "success");
                    })
                    .catch(error => {
                        console.log("error pdf :");
                        console.log(error);
                        this.$swal.fire("", "Somthing wrong happend !", "warning");
                    });
            },
            showMonthShifts(index) {
                let currentYear = new Date().getFullYear() - 1;
                this.$router.push({
                    name: "DriverDashboard_ShiftsOfMonth_show",
                    params: {month: index, year: currentYear}
                });
            },
            getMonthName(index) {
                let Month = "";
                switch (index) {
                    case "1":
                        Month = "January";
                        break;
                    case "2":
                        Month = "February";
                        break;
                    case "3":
                        Month = "March";
                        break;
                    case "4":
                        Month = "April";
                        break;
                    case "5":
                        Month = "May";
                        break;
                    case "6":
                        Month = "June";
                        break;
                    case "7":
                        Month = "July";
                        break;
                    case "8":
                        Month = "August";
                        break;
                    case "9":
                        Month = "September";
                        break;
                    case "10":
                        Month = "October";
                        break;
                    case "11":
                        Month = "November";
                        break;
                    case "12":
                        Month = "December";
                        break;
                }
                return Month;
            },
            getMonthFare(index) {
                return this.months[index].fare;
            },
            getMonthTip(index) {
                return this.months[index].tip;
            },
            getMonthTotal(index) {
                return this.months[index].total;
            },
            getReport() {
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_driver;
                axios
                    .get(this.BASE_URL() + "/api/driver/reports/DailyLogSheet/pastYear/")
                    .then(res => {
                        this.table_show = true;
                        this.months = res.data.months;
                        this.name = res.data.name;
                        this.total_fare = res.data.total_fare;
                        this.total_tip = res.data.total_tip;
                        this.total_total = res.data.total_total;
                    })
                    .catch(error => {
                        console.log("error :");
                        console.log(error);
                    });
            }
        }
    };
</script>
<style scoped>
    .wrapper_box {
        display: grid;
        grid-gap: 1em;
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
        margin-top: 4em;
        font-family: "Quicksand", sans-serif;
        justify-self: center;
        font-size: 3em;
        justify-self: center;
    }
    .fitCell{
        max-width:100%;white-space:nowrap;
    }
</style>
