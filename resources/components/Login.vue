<template>
    <v-container fluid>
        <v-card class="mx-auto px-6 mt-16" max-width="344">
            <v-sheet class="d-flex justify-center my-2">
                <img width="100" src="/assets/images/logo.png"/>
            </v-sheet>
            <v-form>
                <v-text-field
                    class="mb-2"
                    clearable
                    label="Email"
                    hint="Ketikkan email yang telah terdaftar"
                    variant="outlined"
                    prepend-inner-icon="mdi-email"
                    density="comfortable"
                    v-model="field.user.email"
                ></v-text-field>

                <v-text-field
                    clearable
                    label="Password"
                    variant="outlined"
                    prepend-inner-icon="mdi-lock"
                    density="comfortable"
                    :type="field.user.showPassword?'text':'password'"
                    :append-inner-icon="field.user.showPassword?'mdi-eye-off':'mdi-eye'"
                    @click:appendInner="field.user.showPassword=!field.user.showPassword"
                    v-model="field.user.password"
                ></v-text-field>

                <v-btn
                    block
                    color="success"
                    size="large"
                    variant="elevated"
                    density="comfortable"
                    @click="login"
                    :disabled="loading.submit"
                    :loading="loading.submit"
                >Login
                </v-btn>
                <v-btn class="my-2"
                       block
                       color="info"
                       size="large"
                       variant="text"
                       density="comfortable"
                       href="/register"
                >Register
                </v-btn>
            </v-form>
        </v-card>
        <lg-toast ref="toast"/>
    </v-container>
</template>

<script>

import LgToast from "./helpers/Toast.vue";

export default {
    name: "Login",
    components: {LgToast},
    data() {
        return {
            collection: {},
            field: {
                user: {
                    email: null,
                    password: null,
                    showPassword: false
                }
            },
            loading: {
                submit: false
            },
        }
    },
    methods: {
        login() {
            this.loading.submit = true
            axios.post('/api/v1/login', this.field.user).then(res => {
                if (res.data.code === 200) {
                    this.$refs.toast.show(res.data.message)
                    localStorage.setItem('user', JSON.stringify(res.data.data))
                    location.href = '/user'
                } else {
                    this.$refs.toast.show(res.data.message, 'red')
                }
            }).finally(() => {
                this.loading.submit = false
            })
        }
    },
}
</script>

<style scoped>

</style>
