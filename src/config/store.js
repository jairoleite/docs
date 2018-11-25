import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    user: { name: "jairo.Leite", email: "jairomakay@gmail.com", isAdmin: true },
    isMenuVisible: true
  },
  mutations: {
    SET_MENU_VISIBLE(state, visible) {
      state.isMenuVisible = visible;
    },
    SET_USER(state, user) {
      state.user = user;
    }
  }
});
