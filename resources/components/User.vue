<template>
    <v-container fluid>
        <!-- Users Data -->
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
                        <td>Roles</td>
                        <td>Action</td>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="collection.users!==null" v-for="(item, index) of collection.users.data">
                        <tr v-if="!item.role_names.includes('Super Admin')">
                            <td v-text="item.name"/>
                            <td v-text="item.email"/>
                            <td>
                                <template v-for="(role, index) of item.roles">
                                    <v-chip class="mr-1 mb-1" density="compact" variant="flat"
                                            v-text="role.name"/>
                                </template>
                            </td>
                            <td>
                                <v-btn
                                    @click="editUser(item)"
                                    v-if="can('Update User')&& item.id!==user.id"
                                    variant="text">
                                    <v-icon>mdi-square-edit-outline</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </v-table>
            </v-card-text>
        </v-card>
        <!-- Roles Data -->
        <v-card class="mt-5">
            <v-toolbar color="success" density="compact">
                <v-toolbar-title>Roles</v-toolbar-title>
                <v-spacer/>
                <v-toolbar-items>
                    <v-btn @click="createRole" v-if="can('Add Role')" variant="text">
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
                        <td>Permissions</td>
                        <td>Action</td>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="collection.roles!==null" v-for="(item, index) of collection.roles.data">
                        <tr>
                            <td v-text="item.name"/>
                            <td>
                                <template v-for="(p, index) of item.permissions">
                                    <v-chip class="mr-1 mb-1" density="compact" variant="flat" v-text="p.name"/>
                                </template>
                            </td>
                            <td>
                                <v-btn
                                    @click="editRole(item)"
                                    v-if="can('Update Role')" variant="text">
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
                    <v-select v-if="collection.roles!==null" multiple variant="outlined" density="compact" label="Roles"
                              :items="collection.roles.data" item-title="name" item-value="id"
                              v-model="field.user.roles"/>
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
        <!-- Dialog Create/Edit Role -->
        <v-dialog width="300" v-model="dialog.role.create">
            <v-card>
                <v-toolbar density="compact" color="success">
                    <v-toolbar-title v-text="field.role.id===null?'Add Role':'Edit Role'"/>
                    <v-spacer/>
                    <v-toolbar-items>
                        <v-btn @click="dialog.role.create=false" variant="text">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-card-text class="py-2">
                    <v-text-field variant="outlined" density="compact" label="Name" v-model="field.role.name"/>
                    <v-card-title>Permission</v-card-title>
                    <template v-if="collection.permissions!==null" v-for="(item, index) of collection.permissions">
                        <v-checkbox density="compact" hide-details :value="item.id" :label="item.name"
                                    v-model="field.role.permissions" multiple/>
                    </template>
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="dialog.role.delete=true" color="warning" v-if="field.role.id!==null">Delete Role
                    </v-btn>
                    <v-spacer/>
                    <v-btn :loading="loading.role.update" :disabled="loading.role.update" @click="updateRole"
                           variant="flat" color="success" v-if="field.role.id!==null">Update
                    </v-btn>
                    <v-btn :loading="loading.role.create" :disabled="loading.role.create" @click="storeRole"
                           variant="flat" color="success" v-if="field.role.id===null">Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- Dialog delete role confirmation -->
        <v-dialog width="350" v-model="dialog.role.delete">
            <v-card>
                <v-toolbar density="compact" color="warning">
                    <v-toolbar-title>Confirmation</v-toolbar-title>
                    <v-spacer/>
                    <v-toolbar-items>
                        <v-btn @click="dialog.role.delete=false" variant="text">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-card-text class="py-2">
                    <p>Do you want to delete this selected role?</p>
                </v-card-text>
                <v-card-actions>
                    <v-spacer/>

                    <v-btn :loading="loading.role.delete" :disabled="loading.role.delete" @click="deleteRole"
                           variant="flat" color="warning" v-if="field.role.id!==null">Delete
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
import Container from "./helpers/Container.vue";
import { mapGetters} from 'vuex';
export default {
    name: "User",
    components: {Container, LgToast},
    data() {
        return {
            collection: {
                users: null,
                roles: null,
                permissions: null
            },
            field: {
                user: {
                    id: null,
                    name: null,
                    email: null,
                    password: null,
                    password_confirmation: null,
                    roles: []
                },
                role: {
                    id: null,
                    name: null,
                    permissions: []
                }
            },
            dialog: {
                user: {
                    create: false,
                    delete: false
                },
                role: {
                    create: false,
                    delete: false
                }
            },
            loading: {
                user: {
                    create: false,
                    delete: false,
                    update: false,
                },
                role: {
                    create: false,
                    delete: false,
                    update: false,
                }
            },
        }
    },
    methods: {
        auth() {
            axios.post('/api/v1/user/auth').then(res => {
                console.log(res.data)
                if (res.data.code === 200) {
                    localStorage.setItem('user', JSON.stringify(res.data.data))
                }else{
                    this.$refs.message.show(res.data.message, 'warning')
                }
            })
        },
        getUsers() {
            axios.get('/api/v1/user').then(res => {
                this.collection.users = res.data.data
            }).finally(() => {

            })
        },
        getPermissions() {
            axios.get('/api/v1/permission').then(res => {
                this.collection.permissions = res.data.data
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
            let roles = user.roles.map(r => r.id);
            this.field.user.roles = roles
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
        },
        getRoles() {
            axios.get('/api/v1/role').then(res => {
                this.collection.roles = res.data.data
            }).finally(() => {

            })
        },
        createRole(showDialog = true) {
            this.field.role = {
                id: null,
                name: null,
                permissions: []
            }
            this.dialog.role.create = showDialog
        },
        storeRole() {
            this.loading.role.create = true
            axios.post('/api/v1/role', this.field.role).then(res => {
                if (res.data.code === 200) {
                    this.$refs.message.show(res.data.message)
                    this.field.role = {
                        id: null,
                        name: null,
                        permissions: []
                    }
                    this.dialog.role.create = false
                    this.getRoles()
                } else {
                    this.$refs.message.show(res.data.message, 'warning')
                }
            }).finally(() => {
                this.loading.role.create = false
            })
        },
        editRole(role) {
            let permissions = role.permissions.map(p => p.id);
            this.field.role = {
                id: role.id,
                name: role.name,
                permissions: permissions
            }
            this.dialog.role.create = true
        },
        updateRole() {
            this.loading.role.update = true
            axios.patch('/api/v1/role/' + this.field.role.id, this.field.role).then(res => {
                if (res.data.code === 200) {
                    this.$refs.message.show(res.data.message)
                    this.auth()
                    this.createRole(false)
                    this.getRoles()
                } else {
                    this.$refs.message.show(res.data.message, 'warning')
                }
            }).finally(() => {
                this.loading.role.update = false
            })
        },
        deleteRole() {
            this.loading.role.delete = true
            axios.delete('/api/v1/role/' + this.field.role.id).then(res => {
                if (res.data.code === 200) {
                    this.$refs.message.show(res.data.message)
                    this.dialog.role.delete = false
                    this.dialog.role.create = false
                    this.getRoles()
                } else {
                    this.$refs.message.show(res.data.message, 'warning')
                }
            }).finally(() => {
                this.loading.role.delete = false
            })
        },
    },
    mounted() {
        this.getUsers()
        this.getRoles()
        this.getPermissions()
    },
    computed:{
        ...mapGetters(['user']),
    }
}
</script>

<style scoped>

</style>
