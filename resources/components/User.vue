<template>
    <v-container fluid>
        <v-card>
            <v-toolbar color="success" density="compact">
                <v-toolbar-title>Users</v-toolbar-title>
                <v-spacer/>
                <v-toolbar-items>
                    <v-btn @click="createUser" v-if="can('Add User')" variant="text">
                        <v-icon>mdi-plus</v-icon>
                        Add
                    </v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-card-text class="py-2">
                <v-table>
                    <thead>
                    <tr>
                        <td>Name</td>
                        <td>Email</td>
                        <td>Action</td>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="collection.users!==null" v-for="(item, index) of collection.users.data">
                        <tr>
                            <td v-text="item.name"/>
                            <td v-text="item.email"/>
                            <td>
                                <v-btn
                                    @click="editUser(item)"
                                    v-if="can('Update User')&& !item.role_names.includes('Super Admin')" icon
                                    size="small">
                                    <v-icon>mdi-square-edit-outline</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </v-table>
            </v-card-text>
        </v-card>
        <!-- Dialog Create/Edit User -->
        <v-dialog width="500" v-model="dialog.user.create">
            <v-card>
                <v-toolbar density="compact" color="success">
                    <v-toolbar-title v-text="field.user.id===null?'Add User':'Edit User'"/>
                    <v-spacer/>
                    <v-toolbar-items>
                        <v-btn @click="dialog.user.create=false" variant="text">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-card-text class="py-2">
                    <v-text-field variant="outlined" density="compact" label="Name" v-model="field.user.name"/>
                    <v-text-field variant="outlined" density="compact" label="Email" v-model="field.user.email"/>
                    <v-alert variant="text" color="red" v-if="field.user.id!==null" class="text-center">leave blank if
                        you don't want to change password
                    </v-alert>
                    <v-text-field variant="outlined" density="compact" label="Password" v-model="field.user.password"
                                  type="password"/>
                    <v-text-field variant="outlined" density="compact" label="Password Confirmation"
                                  v-model="field.user.password_confirmation" type="password"/>
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="dialog.user.delete=true" color="warning" v-if="field.user.id!==null">Delete</v-btn>
                    <v-spacer/>
                    <v-btn :loading="loading.user.update" :disabled="loading.user.update" @click="updateUser"
                           variant="flat" color="success" v-if="field.user.id!==null">Update
                    </v-btn>
                    <v-btn :loading="loading.user.create" :disabled="loading.user.create" @click="storeUser"
                           variant="flat" color="success" v-if="field.user.id===null">Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- Dialog delete user confirmation -->
        <v-dialog width="350" v-model="dialog.user.delete">
            <v-card>
                <v-toolbar density="compact" color="warning">
                    <v-toolbar-title>Confirmation</v-toolbar-title>
                    <v-spacer/>
                    <v-toolbar-items>
                        <v-btn @click="dialog.user.delete=false" variant="text">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-card-text class="py-2">
                    <p>Do you want to delete this selected user?</p>
                </v-card-text>
                <v-card-actions>
                    <v-spacer/>

                    <v-btn :loading="loading.user.delete" :disabled="loading.user.delete" @click="deleteUser"
                           variant="flat" color="warning" v-if="field.user.id!==null">Delete
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
    <lg-toast ref="message"/>
</template>

<script>
import axios from "axios";
import LgToast from "./helpers/Toast.vue";

export default {
    name: "User",
    components: {LgToast},
    data() {
        return {
            collection: {
                users: null
            },
            field: {
                user: {
                    id: null,
                    name: null,
                    email: null,
                    password: null,
                    password_confirmation: null,
                }
            },
            dialog: {
                user: {
                    create: false,
                    delete: false
                }
            },
            loading: {
                user: {
                    create: false,
                    delete: false,
                    update: false,
                }
            },
            selected: {},
            option: {},
            table: {},
        }
    },
    methods: {
        getUsers() {
            axios.get('/api/v1/user').then(res => {
                this.collection.users = res.data.data
            }).finally(() => {

            })
        },
        createUser() {
            this.field.user = {
                id: null,
                name: null,
                email: null,
                password: null,
                password_confirmation: null,
            }
            this.dialog.user.create = true
        },
        storeUser() {
            this.loading.user.create = true
            axios.post('/api/v1/user', this.field.user).then(res => {
                if (res.data.code === 200) {
                    this.$refs.message.show(res.data.message)
                    this.field.user = {
                        id: null,
                        name: null,
                        email: null,
                        password: null,
                        password_confirmation: null,
                    }
                    this.dialog.user.create = false
                    this.getUsers()
                } else {
                    this.$refs.message.show(res.data.message, 'warning')
                }
            }).finally(() => {
                this.loading.user.create = false
            })
        },
        updateUser() {
            this.loading.user.update = true
            axios.patch('/api/v1/user/' + this.field.user.id, this.field.user).then(res => {
                if (res.data.code === 200) {
                    this.$refs.message.show(res.data.message)
                    this.field.user = {
                        id: null,
                        name: null,
                        email: null,
                        password: null,
                        password_confirmation: null,
                    }
                    this.dialog.user.create = false
                    this.getUsers()
                } else {
                    this.$refs.message.show(res.data.message, 'warning')
                }
            }).finally(() => {
                this.loading.user.update = false
            })
        },
        editUser(user) {
            this.field.user.id = user.id
            this.field.user.name = user.name
            this.field.user.email = user.email
            this.dialog.user.create = true
        },
        deleteUser() {
            this.loading.user.delete = true
            axios.delete('/api/v1/user/' + this.field.user.id).then(res => {
                if (res.data.code === 200) {
                    this.$refs.message.show(res.data.message)
                    this.dialog.user.delete = false
                    this.dialog.user.create = false
                    this.getUsers()
                } else {
                    this.$refs.message.show(res.data.message, 'warning')
                }
            }).finally(() => {
                this.loading.user.delete = false
            })
        }
    },
    mounted() {
        this.getUsers()
    },
    watch: {}
}
</script>

<style scoped>

</style>
