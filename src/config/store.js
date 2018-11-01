import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)


export default new Vuex.Store({
    state: {
        isMenuVisible: true,
        user: null//{"name": "jairo.Leite", "email": "jairomakay@gmail.com"}
    },
    mutations: {
        toggleMenu(state, isVisible) {
            
            if(!state.user) {
                state.isMenuVisible = false
                return
            }

            if (isVisible === undefined) {
                state.isMenuVisible = !state.isMenuVisible
            } else {
                state.isMenuVisible = isVisible
            }
        },

        setUser(state, user) {
            state.user = user
            if(user) {
                state.isMenuVisible = true
            } else {
                state.isMenuVisible = false
            }
        }

    }
})