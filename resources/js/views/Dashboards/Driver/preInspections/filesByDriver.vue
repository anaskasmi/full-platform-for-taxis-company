<template>
    <div>
        <!-- progress -->
        <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>

        <div v-else class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Driver Name</th>
                    <th scope="col">Date</th>
                    <th scope="col">Vehicle</th>
                    <th scope="col">shiftType</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(slip,i) in slips" :key="i">
                    <td>{{slip.driverName}}</td>
                    <td>{{slip.date}}</td>
                    <td>{{slip.vehicleName}}</td>
                    <td>{{slip.shiftType}}</td>


                    <td class="text-right">
                        <v-icon color="success" class="mx-1" @click="">edit
                        </v-icon>

                        <v-icon
                            color="red lighten-1"
                            class="mx-1"
                            @click=""
                        >delete
                        </v-icon>
                        <v-icon color="info" class="mx-1" @click="">description
                        </v-icon>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-6">
            <v-pagination :total-visible="5" v-model="current_page" :length="last_page"
                          @input="next"></v-pagination>
        </div>
    </div>
</template>
<script>

    export default {
        created(){
          this.fetchSlips();
        },
        components: {},
        data() {
            return {
                slips: {},
                isLoading:false,
                current_page: 1,
                last_page: 1,
            };
        },
        methods: {
            BASE_URL() {
                return this.$store.state.BASE_URL;
            },
            next(page) {
                this.fetchSlips(page);
            },
            makePagination(meta) {
                this.last_page = meta.last_page;
                this.current_page = meta.current_page;
                this.pageSize = meta.pageSize;
            },
            fetchSlips(page){
                this.isLoading=true;
                let url = this.BASE_URL() + "/api/driver/slipsByDriver";
                let vm = this;

                if (page) {
                    url = url + "?page=" + page;
                }
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_driver;
                axios
                    .get(url)
                    .then(res => {
                        res=res.data;
                        this.slips=res.data;
                        let meta = {};
                        meta.current_page = res.current_page
                        meta.last_page = res.last_page;
                        meta.pageSize = res.per_page;
                        vm.makePagination(meta)
                        this.isLoading = false;
                    })
                    .catch(err => {
                        this.$swal.fire("", err.response.data, "warning");
                        this.isLoading = false;

                    });
            }

        },
        computed: {}
    };
</script>


<style scoped>
    a:hover {
        text-decoration: none;
    }
</style>
