<template>
    <v-container fluid>

        <v-layout>
            <v-app-bar v-if="user!==null"
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
                                 :prepend-avatar="user.avatar">
                    </v-list-item>
                    <v-menu activator="#menu" location="bottom">
                        <v-list nav density="compact" :lines="false">
                            <v-list-item to="/account" prepend-icon="mdi-account">Account</v-list-item>
                            <v-list-item @click="logout" class="text-red" prepend-icon="mdi-power">Logout</v-list-item>
                        </v-list>
                    </v-menu>
                </template>
            </v-app-bar>
            <v-navigation-drawer v-if="user!==null"
                v-model="drawer" permanent
            >
                <v-list-item v-if="user!==null"
                             :prepend-avatar="user.avatar"
                             :title="user.name"
                             :subtitle="user.email"
                ></v-list-item>

                <v-divider></v-divider>

                <v-list density="compact" nav :lines="false">
                    <v-list-item to="/user" prepend-icon="mdi-account-multiple" title="User Manager" value="home"></v-list-item>
                    <v-list-item to="/about" prepend-icon="mdi-forum" title="About" value="about"></v-list-item>
                </v-list>
            </v-navigation-drawer>
            <v-main>
                <router-view/>
            </v-main>
        </v-layout>
        <toast ref="message"/>
    </v-container>
</template>

<script>
import Toast from "./Toast.vue";
import axios from "axios";
import {mapGetters} from "vuex";

export default {
    name: "Container",
    components: {Toast},
    data() {
        return {
            drawer: true,
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
        },
        auth() {
            axios.post('/api/v1/user/auth').then(res => {
                if (res.data.code === 200) {
                    this.$store.commit('setUser', res.data.data)
                }else{
                    this.$refs.message.show(res.data.message, 'warning')
                }
            })
        },
    },
    mounted() {
        this.auth()
    },
    computed:{
        ...mapGetters(['user']),
    }
}
</script>

<style scoped>

</style>
