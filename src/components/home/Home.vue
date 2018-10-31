
<template>
<div class="home">
    <PageTitle icon="fa fa-home" main="Dashboard" sub="Artigos de Programação" />
    <div class="stats">
        <Stat title="Categorias" :value="countCategory" icon="fa fa-folder" color="#d54d50" />
        <Stat title="Artigos" :value="countArticle" icon="fa fa-file" color="#3bc480" />
        <Stat title="Usuários" :value="countUser" icon="fa fa-user" color="#3282cd" />
    </div>

</div>
</template>

<script>

import PageTitle from '../template/PageTitle'
import Stat from './Stat'
import axios from 'axios'
import { baseApiUrl } from '@/global'

export default {
    name: 'Home',
    components: { PageTitle, Stat },
    data: function() {
        return {
            countCategory: null,
            countArticle: null,
            countUser: null
        }
    },
    methods: {
        getStats() {
            axios.get(`${baseApiUrl}/statcs/stats.php`).then(resp => {
                this.countArticle = resp.data.stats[0].articles
                this.countCategory = resp.data.stats[1].categories
                this.countUser = resp.data.stats[2].users
            })
        }
    },
    mounted() {
        this.getStats()
    }
}
</script>

<style>
.stats {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}
</style>
