<template>
    <v-container fluid>
        <v-card>
            <v-toolbar color="success" density="compact">
                <v-toolbar-title>About App</v-toolbar-title>

            </v-toolbar>
            <v-card-text class="py-2">
                <div v-if="collection.user!==null" class="d-flex justify-center my-3">
                    <img width="100" src="/assets/images/logo.png"/>
                </div>
             <p>This app is built using Laragates</p>
            </v-card-text>


        </v-card>    </v-container>
</template>

<script>
export default {
    name: "About",
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
    methods: {},
    mounted() {
    },
    watch: {}
}
</script>

<style scoped>

</style>
