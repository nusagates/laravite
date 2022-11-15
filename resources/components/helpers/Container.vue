<template>
    <v-container fluid>

        <v-layout>
            <v-app-bar
                color="success"
                density="compact"
            >
                <template v-slot:prepend>
                    <v-btn @click="drawer=!drawer" icon>
                        <v-app-bar-nav-icon></v-app-bar-nav-icon>
                    </v-btn>
                </template>

                <v-app-bar-title>
                    <slot name="title"/>
                </v-app-bar-title>

                <template v-slot:append>
                    <v-list-item id="menu" v-if="user!==null" density="compact"
                                 prepend-avatar="https://randomuser.me/api/portraits/men/78.jpg">
                    </v-list-item>
                    <v-menu activator="#menu" location="bottom">
                        <v-list nav density="compact" :lines="false">
                            <v-list-item prepend-icon="mdi-account">Account</v-list-item>
                            <v-list-item @click="logout" class="text-red" prepend-icon="mdi-power">Logout</v-list-item>
                        </v-list>
                    </v-menu>
                </template>
            </v-app-bar>
            <v-navigation-drawer
                v-model="drawer" permanent
            >
                <v-list-item v-if="user!==null"
                             prepend-avatar="https://randomuser.me/api/portraits/men/78.jpg"
                             :title="user.name"
                             :subtitle="user.email"
                ></v-list-item>

                <v-divider></v-divider>

                <v-list density="compact" nav :lines="false">
                    <v-list-item prepend-icon="mdi-account-multiple" title="User Manager" value="home"></v-list-item>
                    <v-list-item prepend-icon="mdi-forum" title="About" value="about"></v-list-item>
                </v-list>
            </v-navigation-drawer>
            <v-main>
                <slot name="content"/>
            </v-main>
        </v-layout>
        <toast ref="message"/>
    </v-container>
</template>

<script>
import axios from "axios";
import Toast from "./Toast.vue";

export default {
    name: "Container",
    components: {Toast},
    data() {
        return {
            drawer: true,
            user: JSON.parse(localStorage.getItem('user'))
        }
    },
    methods: {
        logout() {
            axios.post('/api/v1/logout').then(res => {
                if (res.data.code === 200) {
                    this.$refs.message.show(res.data.message)
                   setTimeout(()=>{
                       localStorage.removeItem('user')
                       location.href = '/login'
                   }, 1000)
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
