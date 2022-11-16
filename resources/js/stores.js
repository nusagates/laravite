import {createStore} from 'vuex'

const store = createStore({
    state() {
        return {
            user: null
        }
    },
    getters: {
        user: state => state.user
    },
    mutations: {
        setUser(state, userData) {
            state.user = userData
        }
    }
})

export default store
