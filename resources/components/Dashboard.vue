<template>
    <container>
        <v-main>
            <template #content>
                <router-view/>
            </template>
        </v-main>
    </container>

</template>

<script>
import Container from "./helpers/Container.vue";
import axios from "axios";
export default {
    name: "Dashboard",
    components: {Container},
    data() {
        return {
            collection: {},
            field: {},
            dialog: {},
            loading: {},
            selected: {},
            option: {},
            table: {},
        }
    },
    methods: {
    },
    mounted() {

    },
    watch: {}
}
</script>

<style scoped>

</style>
