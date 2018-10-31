
<template>
    <div class="category-admin">
      
      <b-form>
          <input id="category-id" type="hidden" v-model="category.id"/>
          <b-row>
              <b-col xs="12">
                  <b-form-group label="Nome:" label-for="category-name">
                  <b-form-input id="category-name" type="text" v-model="category.name" required
                  placeholder="Informe o nome da categoria" /> 
                  </b-form-group>
              </b-col>
              
          </b-row>
           <b-row>
              <b-col xs="12">
                  <b-form-group label="Categoria Pai:" label-for="category-parentId">
                     <b-form-select id="categpry-parantId" :options="categoriesParents" v-model="selectedParentId"/>
                  </b-form-group>
              </b-col>
              
          </b-row>
                   
          <b-button variant="primary" @click="save">Salvar</b-button>
          <b-button class="ml-2" @click="reset">Cancelar</b-button>
      </b-form>
      <hr>
      <b-table hover striped :items="categories" :fields="fields">
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
import { EventBus } from "@/eventBus";
import axios from "axios";

export default {
  name: "CategoryAdmin",
  data: function() {
    return {
      category: {},
      categories: [],
      categoriesParents: [],
      selectedParentId: null,
      fields: [
        { key: "id", label: "Código", sortable: true },
        { key: "name", label: "Nome", sortable: true },
        { key: "path", label: "Caminho", sortable: true },
        { key: "actions", label: "Ações" }
      ]
    };
  },
  mounted() {
    this.loadCategories();
    this.loadCategoriesParent();
  },
  methods: {
    loadCategories() {
      const url = `${baseApiUrl}/category/listAllCategories.php`;
      axios.get(url).then(resp => {
        this.categories = resp.data.categories;
        // console.log(resp.data);
      })
    },

    loadCategoriesParent() {
      const url = `${baseApiUrl}/category/listAllCategoriesParent.php`;
      axios.get(url).then(resp => {
        this.categoriesParents = resp.data.categories.map(category => {
          return { ...category, value: category.id, text: category.path };
        })
      })
    },

    reset() {
      this.selectedParentId = null;
      this.category = {};
      this.loadCategories();
      this.loadCategoriesParent();
    },

    save() {
      if (!this.category.name) {
        showError("Nome Obrigatório!");
        return;
      }

      const url = `${baseApiUrl}/category/save.php`;
      axios
        .post(url, { name: this.category.name, parentId: this.selectedParentId })
        .then(resp => {
          if (!resp.data.error) {
            // console.log(resp.data)
            this.$toasted.global.defaultSuccess({ msg: resp.data.message });
            this.reset();
            EventBus.$emit('loadCategory')
          } else {
            this.$toasted.global.defaultError({ msg: resp.data.message });
          }
        })
        .catch(showError);
    },
    remove(item) {
      // console.log(item)
      const url = `${baseApiUrl}/category/remove.php`;
      axios
        .post(url, { id: item.id })
        .then(resp => {
          if (!resp.data.error) {
            this.$toasted.global.defaultSuccess({ msg: resp.data.message });
            this.loadCategories();
             this.loadCategoriesParent();
          } else {
            this.$toasted.global.defaultError({ msg: resp.data.message });
          }
        })
        .catch(showError);
    }
  }
};
</script>

<style>
</style>

