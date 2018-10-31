
<template>
    <div class="user-admin">
      
      <b-form>
           <input id="user-id" type="hidden" v-model="user.id"/>
          <b-row>
              <b-col md="6" sm="12">
                  <b-form-group label="Nome:" label-for="user-name">
                  <b-form-input id="user-name" type="text" v-model="user.name" required
                  placeholder="Informe o nome do usuário" /> 
                  </b-form-group>
              </b-col>
              <b-col md="6" sm="12">
                  <b-form-group label="Email:" label-for="user-email">
                  <b-form-input id="user-email" type="text" v-model="user.email" required
                  placeholder="Informme o e-mail do usuário" /> 
                  </b-form-group>
              </b-col>
          </b-row>
           
          <b-form-checkbox id="user-admin" v-model="user.admin" class="mt-3 mb-3">
            Administrador?
          </b-form-checkbox>

          <b-row>
              <b-col md="6" sm="12">
                  <b-form-group label="Senha:" label-for="user-password">
                  <b-form-input id="user-password" type="password" v-model="user.password" required
                  placeholder="Informme a senha do usuário" /> 
                  </b-form-group>
              </b-col>
              <b-col md="6" sm="12">
                  <b-form-group label="Confirmação de Senha:" label-for="user-confirm-password">
                  <b-form-input id="user-confim-password" type="password" v-model="user.confirmPassword" required
                  placeholder="Confirme a senha do usuário" /> 
                  </b-form-group>
              </b-col>
          </b-row>
          <b-button variant="primary" @click="save">Salvar</b-button>
          <b-button class="ml-2" @click="reset">Cancelar</b-button>
      </b-form>
      <hr>
      <b-table hover striped :items="users" :fields="fields">
            <template slot="actions" slot-scope="data">
              <b-button variant="danger" @click="remove(data.item)">
                <i class="fa fa-trash"></i>
              </b-button>
            </template>
      </b-table>
    </div>    
</template>

<script>
import { baseApiUrl, showError } from "@/global";
import axios from "axios";

export default {
  name: "UserAdmin",
  data: function() {
    return {
      user: {},
      users: [],
      fields: [
        { key: "id", label: "Código", sortable: true },
        { key: "name", label: "Nome", sortable: true },
        { key: "email", label: "E-mail", sortable: true },
        {
          key: "admin",
          label: "Adminitrador",
          sortable: true,
          formatter: value => (value ? "Sim" : "Não")
        },
        { key: "actions", label: "Ações" }
      ]
    };
  },
  mounted() {
    this.loadUsers();
  },
  methods: {
    loadUsers() {
      const url = `${baseApiUrl}/user/listAllUsers.php`;
      axios.get(url).then(resp => {
        this.users = resp.data.users;
      });
    },

    reset() {
      this.user = {};
      this.loadUsers();
    },

    save() {
      if (!this.user.name) {
        showError("Nome Obrigatório!");
        return;
      }

      if (!this.user.email) {
        showError("E-mail Obrigatório!");
        return;
      }

      if (!this.user.password) {
        showError("Senha Obrigatório!");
        return;
      }

      if (!this.user.confirmPassword) {
        showError("Confirmação de Senha Obrigatório!");
        return;
      }

      const url = `${baseApiUrl}/user/save.php`;
      axios
        .post(url, this.user)
        .then(resp => {
          if (!resp.data.error) {
            // console.log(resp.data)
            this.$toasted.global.defaultSuccess({msg:resp.data.message});
            this.reset();
          } else {
            this.$toasted.global.defaultError({msg:resp.data.message});
          }
        })
        .catch(showError);
    },
    remove(item) {
      // console.log(item)
       const url = `${baseApiUrl}/user/remove.php`;
       axios.post(url, {id: item.id}).then(resp => {
          if(!resp.data.error) {
              this.$toasted.global.defaultSuccess({msg:resp.data.message});
              this.loadUsers();
          }
          else {
            this.$toasted.global.defaultError({msg:resp.data.message});
          }
       }).catch(showError)
    }
  }
};
</script>

<style>
</style>
