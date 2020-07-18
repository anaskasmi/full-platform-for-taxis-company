<template>
    <div>
        <v-container v-if="$mq === 'desktop'">
            <!-- welcom section -->
            <div class="welcom">
                <v-img
                    class="logo"
                    src="/img/taxi-logo.png"

                >

                </v-img>
                <div class="devide">
                    <v-divider></v-divider>
                </div>
                <div class="sentence">
                    Welcome back !
                </div>
            </div>

            <!-- cards section -->
            <div class="wrapper">
                <div
                    v-for="card in cards"
                    :key="card.title"
                    :to="card.link">
                    <v-card flat
                            color="grey lighten-4"
                            class="card"
                            @click="showLogin_on(card.title)"
                    >
                        <v-img
                            class="image"
                            :src="card.src"
                        >
                        </v-img>
                        <v-btn
                            dark
                            tile
                            hover
                            class="role "
                            v-text="card.title"
                            color="primary"
                        >
                        </v-btn>

                    </v-card>
                </div>
            </div>
            <modelAdmin></modelAdmin>
            <modelDriver></modelDriver>
            <modelDirector></modelDirector>
            <modelDispatcher></modelDispatcher>
        </v-container>

        <!--------------------------------------    mobile view  --------------------------------------->
        <v-container v-if="$mq === 'mobile'">

            <div class="welcomeMobile">
                <v-img
                    class="logoMobile"
                    src="/img/taxi-logo.png"

                >

                </v-img>
                <div class="devide">
                    <v-divider></v-divider>
                </div>
                <div class="sentenceMobile">
                    Welcome back !
                </div>
            </div>

            <v-carousel hide-delimiters>
                <v-carousel-item
                    v-for="card in cards"
                    :key="card.title"
                    :to="card.link"
                    :ripple="false"
                >

                        <v-row
                            class="fill-height"
                            align="center"
                            justify="center"
                        >
                            <v-card
                                justify="center"
                                align="center"
                                flat
                                color="grey lighten-4 "
                                @click="showLogin_on(card.title)"
                                :ripple="false"
                            >
                                <v-img
                                    style="max-width: 70%; width: 30rem"
                                    class="image img-thumbnail"
                                    :src="card.src"
                                >
                                </v-img>
                                <v-btn
                                    dark
                                    tile
                                    hover
                                    block
                                    class="role m-0 "
                                    v-text="card.title"
                                    color="primary"
                                >
                                </v-btn>

                            </v-card>
                        </v-row>

                </v-carousel-item>
            </v-carousel>


            <modelAdmin></modelAdmin>
            <modelDriver></modelDriver>
            <modelDirector></modelDirector>
            <modelDispatcher></modelDispatcher>
        </v-container>
    </div>
</template>


<script>

    import modelAdmin from '@/js/components/models/Admin.vue';
    import modelDriver from '@/js/components/models/Driver.vue';
    import modelDirector from '@/js/components/models/Director.vue';
    import modelDispatcher from '@/js/components/models/Dispatcher.vue';

    export default {

        created() {
            this.$store.commit('showNavbarIndex_on');
        },
        beforeDestroy() {
            this.$store.commit('showNavbarIndex_off');
        },
        components: {
            modelAdmin,
            modelDriver,
            modelDirector,
            modelDispatcher,
        },
        data: () => ({
            model: null,
            cards: [
                {title: 'Admin', src: "/img/admin-flat.png", flex: 3, link: "/adminDashboard"},
                {title: 'Driver', src: "/img/driver-flat.png", flex: 3, link: "/driverDashboard/profile"},
                {title: 'Director', src: "/img/director-flat.png", flex: 3, link: "/directorDashboard"},
                {title: 'Dispatcher', src: "/img/dispatcher-flat.png", flex: 3, link: "/dispatcherDashboard"},
            ],
        }),
        computed: {
            loggedIn_driver() {
                return this.$store.getters.loggedIn_driver;
            },
            loggedIn_admin() {
                return this.$store.getters.loggedIn_admin;
            },
            loggedIn_director() {
                return this.$store.getters.loggedIn_director;
            },
            loggedIn_dispatcher() {
                return this.$store.getters.loggedIn_dispatcher;
            },
        },
        methods: {
            showLogin_on: function (role) {
                switch (role) {
                    case "Admin":
                        if (this.loggedIn_admin) {
                            this.$router.push({name: 'AdminDashboard_profile'});
                        } else {
                            this.$store.commit('showAdminLogin_on');
                        }
                        break;

                    case "Driver":
                        if (this.loggedIn_driver) {
                            this.$router.push({name: 'DriverDashboard_profile'});
                        } else {
                            this.$store.commit('showDriverLogin_on');
                        }
                        break;

                    case "Director":
                        if (this.loggedIn_director) {
                            this.$router.push({name: 'DirectorDashboard_profile'});
                        } else {
                            this.$store.commit('showDirectorLogin_on');
                        }
                        break;

                    case "Dispatcher":
                        if (this.loggedIn_dispatcher) {
                            this.$router.push({name: 'DispatcherDashboard_profile'});
                        } else {
                            this.$store.commit('showDispatcherLogin_on');
                        }
                        break;

                }
            }
        }
    }
</script>


<style scoped>
    .wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        grid-gap: 3em;
        grid-auto-rows: minmax(300px, auto);
        justify-items: stretch;
        padding: 3em;

    }

    .wrapper > div {
        /* background: #64B5F6; */
        padding: 1em;
    }

    .card {
        display: grid;
        /* background: #E8EAF6; */
        grid-auto-rows: minmax(100px, auto);

    }

    .image {
        justify-self: stretch;
        margin: 2em;
        /* align-self: start; */
    }

    .role {
        justify-self: stretch;
        align-self: end;
        margin: 1em 1em;
    }

    .welcom {
        display: grid;
        grid-gap: 0.2em;
        align-items: auto;
    }
    .welcomeMobile {
        display: grid;
        grid-gap: 0.1em;
        align-items: auto;
    }

    .logo {
        margin-top: 2em;
        margin-bottom: 2em;
        width: 15%;
        justify-self: center;
    }
    .logoMobile {
        margin-top: 1em;
        width: 45%;
        justify-self: center;
    }

    .sentence {
        margin-top: 2em;
        font-family: 'Quicksand', sans-serif;
        justify-self: center;
        font-size: 2.5em;
    }
    .sentenceMobile {
        font-family: 'Quicksand', sans-serif;
        justify-self: center;
        font-size: 2em;
    }

    .devide {
        width: 20%;
        justify-self: center;

    }

    a {
        text-decoration: none;
    }

</style>
