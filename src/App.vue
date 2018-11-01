<template>
	<div id="app" :class="{'hide-menu': !isMenuVisible || !user}">
		<Header title="Artigos de Tecnologia" 
			:hideToggle="!user"
			:hideUserDropdown="!user" />
		<Menu v-if="user" />
		<Loading v-if="validating" />
		<Content v-else />
		<Footer />
	</div>
</template>

<script>
import axios from "axios";
import { baseApiUrl } from "@/global";
import { mapState } from "vuex";

import Header from "@/components/template/Header";
import Menu from "@/components/template/Menu";
import Content from "@/components/template/Content";
import Footer from "@/components/template/Footer";
import Loading from "@/components/template/Loading";

export default {
  name: "App",
  components: { Header, Menu, Content, Footer, Loading },
  computed: mapState(["isMenuVisible", "user"]),
  data: function() {
    return {
      validating: false
    };
  },
  methods: {
    async logger() {
     
      this.validating = true;
      const json = localStorage.getItem('userKey')
			const token = JSON.parse(json)
      this.$store.commit('setUser', null)
      //console.log(token)
      
			 if(!token) {
			 	  this.validating = false
			 	  this.$router.push({ name: 'auth' });
			 	  return
			 }


      const url = `${baseApiUrl}/user/logger.1.php`;
      axios.post(url, {token: token}).then(resp => {
        //console.log(resp.data.user[0])
         if (!resp.data.user[0]) {
           //this.validating = false;
           this.$router.push({ name: "auth" });
           return;
         } else {
           //console.log(resp.data.user[0])
            this.$store.commit("setUser", resp.data.user[0]);
           if (this.$mq === "xs" || this.$mq === "sm") {
             this.$store.commit("toggleMenu", false);
           }
            this.$router.push({ name: "/" });
        }
        this.validating = false


      });
    }
  },
  created() {
    this.logger();
  }
};
</script>

<style>
* {
  font-family: "Lato", sans-serif;
}

body {
  margin: 0;
}

#app {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;

  height: 100vh;
  display: grid;
  grid-template-rows: 60px 1fr 40px;
  grid-template-columns: 300px 1fr;
  grid-template-areas:
    "header header"
    "menu content"
    "menu footer";
}

#app.hide-menu {
  grid-template-areas:
    "header header"
    "content content"
    "footer footer";
}
</style>