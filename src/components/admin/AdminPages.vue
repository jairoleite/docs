<template>
  <div class="admin-pages">
    <PageTitle icon="fa fa-cogs" main="Administração do Sistema" sub="Cadastros"/>
    <div class="admin-pages-tabs">
      <div v-if="user.admin == 1">
        <b-card no-body>
          <b-tabs card>
            <b-tab title="Artigos" active>
              <ArticleAdmin/>
            </b-tab>
            <b-tab title="Categorias">
              <CategoryAdmin/>
            </b-tab>
            <!-- <b-tab title="Usuários">
                            <UserAdmin />
            </b-tab>-->
          </b-tabs>
        </b-card>
      </div>
      <div v-else>
        <h3>
          <span>Você não tem permissão para cessar essa tela</span>
        </h3>
      </div>
    </div>
  </div>
</template>

<script>
import PageTitle from '../template/PageTitle'
import ArticleAdmin from './ArticleAdmin'
import CategoryAdmin from './CategoryAdmin'
import UserAdmin from './UserAdmin'
import { mapState } from "vuex";

export default {
    name: 'AdminPages',
    components: { PageTitle, ArticleAdmin, CategoryAdmin, UserAdmin },
    computed: mapState(["user"]),
}
</script>

<style>
</style>