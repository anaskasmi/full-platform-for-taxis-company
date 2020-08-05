<template>
    <div class="container ">
        <div color="info" class="text-info text-center my-5 font-weight-bold" border="top"  style="font-size: 3em" v-if="currentAuthenticateVehicle">
            {{currentAuthenticateVehicle.name}} is authenticated
        </div>
        <br>
        <div class="text-center">
            <v-btn v-if="currentAuthenticateVehicle" @click="currentAuthenticateVehicle=null" outlined color="info"  >
                <v-icon color="teal darken-3" class="mx-1">autorenew</v-icon>
                Authenticate to another Vehicle
            </v-btn>
        </div>

        <v-card class="mx-auto" style="width: 50%;" shaped v-if="!currentAuthenticateVehicle">
            <div class="card-header text-center text-primary text-uppercase" style="font-size: 2em;">Authenticate
                vehicle
            </div>
            <div class="card-body">
                <form>
                    <div class="my-4 ">
                        <v-select-search
                            class="w-100"
                            label="name"
                            placeholder="Vehicle"
                            v-model="VehicleId"
                            :options="vehicles"
                            :reduce="vehicle => vehicle.id"
                        ></v-select-search>
                    </div>
                    <div class="form-group">
                        <input type="password" placeholder="Password" class="form-control" v-model="password">
                    </div>
                    <v-btn
                        block
                        outlined
                        color="info"
                        @click="authenticateVehicle()"
                    >
                        Authenticate
                    </v-btn>
                </form>
            </div>
        </v-card>
    </div>
</template>
<script>

    export default {
        components: {},
        created() {
            this.fetchItems();

        },

        data() {
            return {
                vehicles: [],
                isLoading: false,
                reseting: false,
                VehicleId: '',
                password:'',
                currentAuthenticateVehicle: null,
            }
        },
        computed: {},
        methods: {
            BASE_URL() {
                return this.$store.state.BASE_URL;
            },
            compare(a, b) {
                if (parseInt(a.number, 10) < parseInt(b.number, 10)) {
                    return -1;
                }
                if (parseInt(a.number, 10) > parseInt(b.number, 10)) {
                    return 1;
                }
                return 0;
            },
            fetchItems() {
                this.checkIfAuthentocated();
                this.isLoading = true;

                let url = this.BASE_URL() + "/api/driver/vehiclesWithNames";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_driver;
                axios
                    .get(url)
                    .then(res => {
                        this.vehicles = res.data;
                        this.vehicles.sort(this.compare);
                        this.isLoading = false;
                        if (!this.vehicles) {
                            this.$swal("No vehicles found!", "", "warning");
                        }
                    })
                    .catch(error => {
                        console.log(error)
                        this.$swal("Try again", error.response.data.errors, "warning");
                    });
            },
            authenticateVehicle() {
                this.isLoading = true;

                let url = this.BASE_URL() + "/api/driver/authenticateVehicle";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_driver;
                axios
                    .post(url, {'id': this.VehicleId, 'password': this.password})
                    .then(res => {
                        this.isLoading = false;
                        this.$swal("authenticated!", "", "success");
                        this.$store.commit('setVehicleAuthId', this.VehicleId);
                        this.$store.commit('setVehicleAuthPassword', this.password);
                        this.currentAuthenticateVehicle = res.data.data;
                    })
                    .catch(error => {
                        if (error.response) {
                            this.$swal(error.response.data.message, "try again", "error");
                        } else {
                            this.$swal("Something went wrong", "try again", "warning");
                        }
                        console.log(error)

                    });
            },
            checkIfAuthentocated() {
                this.isLoading = true;

                let idToCheck = this.$store.getters.getVehicleAuthId;
                let passwordToCheck = this.$store.getters.getVehicleAuthPassword;
                console.log("idToCheck : " + idToCheck);
                console.log("passwordToCheck : " + passwordToCheck);
                if (!idToCheck && !passwordToCheck) {
                    return false;
                }


                let url = this.BASE_URL() + "/api/driver/authenticateVehicle";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_driver;
                axios
                    .post(url, {'id': idToCheck, 'password': passwordToCheck})
                    .then(res => {
                        this.isLoading = false;
                        this.currentAuthenticateVehicle = res.data.data;
                    })
                    .catch(error => {
                        if (error.response) {
                            this.$store.commit('setVehicleAuthId', null);
                            this.$store.commit('setVehicleAuthPassword', null);
                            this.$swal("this vehicle is no longer authenticated",error.response.data.message, "error");


                        } else {
                            this.$swal("Couldnt authenticate your vehicle", "try again", "warning");
                        }
                        console.log(error)

                    });
            }

        },


    }
</script>


<style scoped>
    a:hover {
        text-decoration: none;
    }

    .fitCell {
        max-width: 100%;
        white-space: nowrap;
    }

    .bigFont {
        font-size: 1.4em;
    }

</style>
