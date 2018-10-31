<template>
    <div class="article-admin">
        <b-form>
            <input id="article-id" type="hidden" v-model="article.id" />
           
            <b-form-group label="Nome:" label-for="article-name">
                <b-form-input id="article-name" type="text"
                    v-model="article.name" required
                    :readonly="mode === 'remove'"
                    placeholder="Informe o Nome do Artigo..." />
            </b-form-group>
           
            <b-form-group label="Descrição" label-for="article-description">
                <b-form-input id="article-description" type="text"
                    v-model="article.description" required
                    :readonly="mode === 'remove'"
                    placeholder="Informe o Nome do Artigo..." />
            </b-form-group>
           
            <b-form-group v-if="mode === 'save'"
                label="Imagem (URL):" label-for="article-imageUrl">
                <b-form-input id="article-imageUrl" type="text"
                    v-model="article.imageUrl" required
                    :readonly="mode === 'remove'"
                    placeholder="Informe a URL da Imagem..." />
            </b-form-group>
           
            <b-form-group v-if="mode === 'save'" 
                label="Categoria:" label-for="article-categoryId">
                <b-form-select id="article-categoryId"
                    :options="categories" v-model="article.categoryId" />
            </b-form-group>
           
            <b-form-group v-if="mode === 'save'"
                label="Conteúdo" label-for="article-content">
                <VueEditor v-model="article.content"
                    placeholder="Informe o Conteúdo do Artigo..." />
            </b-form-group>
           
            <b-button variant="primary" v-if="mode === 'save'"
                @click="save">Salvar</b-button>
            <b-button variant="danger" v-if="mode === 'remove'"
                @click="remove">Excluir</b-button>
            <b-button class="ml-2" @click="reset">Cancelar</b-button>
        </b-form>
        <hr>
        
        <b-table hover striped :items="articles" :fields="fields">
            <template slot="actions" slot-scope="data">
                <b-button variant="warning" @click="editArticle(data.item)" class="mr-2">
                    <i class="fa fa-pencil"></i>
                </b-button>
                <b-button variant="danger" @click="removeArticle(data.item)">
                    <i class="fa fa-trash"></i>
                </b-button>
            </template>
        </b-table>
        <b-pagination size="md" v-model="page" :total-rows="count" :per-page="limit" />
    </div>
</template>

<script>
import { VueEditor } from "vue2-editor";
import { baseApiUrl, showError } from "@/global";
import { EventBus } from "@/eventBus";
import axios from "axios";

export default {
  name: "ArticleAdmin",
  components: { VueEditor },
  data: function() {
    return {
      mode: "save",
      article: {},
      articles: [],
      categories: [],
      page: 1,
      limit: 0,
      count: 0,
      fields: [
        { key: "id", label: "Código", sortable: true },
        { key: "name", label: "Nome", sortable: true },
        { key: "description", label: "Descrição", sortable: true },
        { key: "actions", label: "Ações" }
      ]
    };
  },

  methods: {
    
    loadArticles() {
      const url = `${baseApiUrl}/article/listArticles.php?page=${this.page}`;
      axios.get(url).then(res => {
        this.articles = res.data.articles;
        this.count = Number(res.data.count[0]);
        this.limit = Number(res.data.limit[0]);
      });
    },

    editArticle(article) {
      //console.log(article)
      this.mode = "save";
      const url = `${baseApiUrl}/article/getArticle.php?id=${article.id}`;
      axios.get(url).then(resp => {
        //console.log(resp.data)
        this.article = resp.data.article[0];
      });
    },

    removeArticle(article) {
      const url = `${baseApiUrl}/article/remove.php`;

      axios
        .post(url, { id: article.id })
        .then(resp => {
          if (!resp.data.error) {
            this.$toasted.global.defaultInfo({ msg: resp.data.message });
            this.reset();
          } else {
            showError({ msg: resp.data.message });
          }
        })
        .catch(showError);
    },

    loadCategories() {
      const url = `${baseApiUrl}/category/listAllCategoriesParent.php`;
      axios.get(url).then(resp => {
        this.categories = resp.data.categories.map(category => {
          return { ...category, value: category.id, text: category.path };
        });
      });
    },

    reset() {
      this.mode = "save";
      this.article = {};
      this.loadArticles();
    },

    save() {
      if (this.article.name == null || this.article.name == "") {
        this.$toasted.global.defaultError({ msg: "O nome é obrigatório" });
        return;
      }
      if (this.article.description == null || this.article.description == "") {
        this.$toasted.global.defaultError({ msg: "A descrição é obrigatório" });
        return;
      }
      if (this.article.categoryId == null || this.article.categoryId == "") {
        this.$toasted.global.defaultError({ msg: "A categoria é obrigatório" });
        return;
      }
      if (this.article.content == null || this.article.content == "") {
        this.$toasted.global.defaultError({ msg: "O conteúdo é obrigatório" });
        return;
      }
      this.article.userId = 1;

      if (this.article.id == null || this.article.id == "") {
        this.article.id = 0;
      }

      const url = `${baseApiUrl}/article/save.php`;
      
      axios
        .post(url, this.article)
        .then(resp => {
          if (!resp.data.error) {
            this.$toasted.global.defaultSuccess({ msg: resp.data.message });
            this.reset();
          } else {
            showError({ msg: resp.data.message });
          }
        })
        .catch(showError);
    }
  },

  watch: {
    page() {
      this.loadArticles();
    }
  },

  mounted() {
    this.loadCategories();
    this.loadArticles();
    
    EventBus.$on('loadCategory', () => {
        this.loadCategories();
    })
  }
};
</script>

<style>
</style>