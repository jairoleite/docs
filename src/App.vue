<template>
	<div>
		<div v-if="user" id="app" :class="{'hide-menu': !isMenuVisible}">
      <Header title="Artigos de Tecnologia" />
      <Menu/>
      <Content />
      <Footer />
    </div>
    <div v-else class="login">
      <div v-if="loading" class="load-position">
           <img src="@/assets/load-boll.gif" alt="Loading">
      </div>
      <div v-else>
        <Auth />
      </div>
    </div>
	</div>
</template>

<script>
//compoenetes
import Header from "@/components/template/Header";
import Menu from "@/components/template/Menu";
import Content from "@/components/template/Content";
import Footer from "@/components/template/Footer";
import Auth from "@/components/auth/Auth";
//global js
import { baseApiUrl} from '@/global'
//objeto geral
import { mapState } from "vuex";
//cliente http
import axios from 'axios'

export default {
  name: "App",
  components: { Header, Menu, Content, Footer, Auth },
  computed: mapState(["isMenuVisible","user"]),
  data() {
    return {
      loading: false
    }
  },
   methods: {
    async logger() {
      this.loading = true; 
      const json = localStorage.getItem('userKey')
			const token = JSON.parse(json)
      this.$store.commit('SET_USER', null);
      if(!token) {
          this.$router.push({ name: "/" });
          this.loading = false; 
			 	  return;
      }
      
      const url = `${baseApiUrl}/user/logger.1.php`;
      axios.post(url, {token: token}).then(resp => {
         this.loading = false; 
         if (!resp.data.user[0]) {
           return;
         } else {
            this.$store.commit("SET_USER", resp.data.user[0]);
         }
      });
    }
  },
  mounted() {
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

.load-position {
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  z-index: 2;
}
</style>