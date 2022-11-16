<template>
    <v-container fluid>
        <v-card>
            <v-toolbar color="success" density="compact">
                <v-toolbar-title>About App</v-toolbar-title>

            </v-toolbar>
            <v-card-text class="py-2">
                <v-list>
                    <v-list-item
                        prepend-avatar="/assets/images/logo.png"
                        title="Laragates"
                        subtitle="nusagates@gmail.com"
                    ></v-list-item>
                </v-list>

                <v-divider/>
                <v-card-title class="text-center">Changelogs</v-card-title>
                <v-timeline>
                    <template v-for="(timeline, index) of collection.timeline">
                        <v-timeline-item size="small" :dot-color="timeline.color">
                            <template v-slot:opposite>
                                <div
                                    :class="`pt-1 headline font-weight-bold text-${timeline.color}`" v-text="timeline.date"/>
                            </template>
                            <div>
                                <h2 :class="`mt-n1 headline font-weight-light mb-4 text-${timeline.color}`" v-text="timeline.title"/>
                                <div v-html="timeline.description"/>
                            </div>
                        </v-timeline-item>
                    </template>
                </v-timeline>
            </v-card-text>


        </v-card>
    </v-container>
</template>

<script>
export default {
    name: "About",
    data() {
        return {
            collection: {
                timeline:[
                    {
                        date: "16 November 2022",
                        title: "Add SPA Architecture",
                        description: "Add Single Page Architecture (SPA). All vue components inside components directory is autoregistered to vue-routers",
                        color: "pink"
                    },
                    {
                        date: "15 November 2022",
                        title: "Add User Manager",
                        description: "Add user manager with dynamic roles and permissions using <code class='v-code'>spatie/laravel-permission</code>. We can use <code class='v-code'>@can()</code> or <code class='v-code'>@is()</code> inside vue component to give permissions<br>" +
                            "Add account update feature. Users can change their name, email, password, and avatar (using <code class='v-code'>spatie/laravel-ignition</code>)",
                        color: "cyan"
                    },
                    {
                        date: "14 November 2022",
                        title: "Add Authentication",
                        description: "Add registration and login page. This authentication is using Sanctum. <br>" +
                            "Add sidebar menu and app bar menu. So we can add app navigations there",
                        color: "orange"
                    },
                    {
                        date: "7 November 2022",
                        title: "Laragates Project Started",
                        description: "Laragates project is started using Laravel 9, VueJs 3, Vuetify 3, Vite 3",
                        color: "success"
                    },
                ]
            },
        }
    },

}
</script>

<style scoped>

</style>
