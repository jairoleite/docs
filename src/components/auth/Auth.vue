<template>
    <div class="auth-content">
        <div class="auth-modal">
            <img src="@/assets/login.png" width="200" alt="Logo" />
            <hr>
            <div class="auth-title">{{ showSignup ? 'Cadastro' : 'Login' }}</div>

            <input v-if="showSignup" v-model="user.name" type="text" placeholder="Nome">
            <input v-model="user.email" name="email" type="text" placeholder="E-mail">
            <input v-model="user.password" name="password" type="password" placeholder="Senha">
            <input v-if="showSignup" v-model="user.confirmPassword"
                type="password" placeholder="Confirme a Senha">

            <button v-if="showSignup" style="cursor: pointer" @click="signup">Registrar</button>
            <button v-else style="cursor: pointer" @click="signin">Entrar</button>

            <a href @click.prevent="showSignup = !showSignup">
                <span v-if="showSignup">Já tem cadastro? Acesse o Login!</span>
                <span v-else>Não tem cadastro? Registre-se aqui!</span>
            </a>
        </div>
    </div>
</template>

<script>
import { baseApiUrl, showError} from '@/global'
import axios from 'axios'

export default {
    name: 'Auth',
    data: function() {
        return {
            showSignup: false,
            user: {}
        }
    },
    methods: {
        signin() {
            if(!this.user.email) {
                this.$toasted.global.defaultError({msg: "Digite o email"})
                return
            }
            if(!this.user.password) {
                this.$toasted.global.defaultError({msg: "Digite a senha"})
                return
            }
            
            axios.post(`${baseApiUrl}/user/logger.php`, {email: this.user.email, password: this.user.password})
                .then(resp => {
                    if(resp.data.error) {
                       this.$toasted.global.defaultError({msg: "Usuário ou senha inválido"})
                       return;
                    } 
                    else {
                       //console.log(resp.data.user[0].token)
                       this.$store.commit('setUser', resp.data.user[0])
                       localStorage.setItem('userKey', JSON.stringify(resp.data.user[0].token))
                       this.$router.push({ path: '/home' })
                    }

                })
                .catch(showError)
        },
        signup() {

            if(!this.user.name) {
                this.$toasted.global.defaultError({msg: "O nome é obrigatório"})
                return
            }
            if(!this.user.email) {
                this.$toasted.global.defaultError({msg: "O email é obrigatório"})
                return
            }
            if(!this.user.password) {
                this.$toasted.global.defaultError({msg: "A senha é obrigatória"})
                return
            }
            if(!this.user.confirmPassword) {
                this.$toasted.global.defaultError({msg: "A confirmação de senha é obrigatória"})
                return
            }
            if(this.user.password != this.user.confirmPassword) {
                this.$toasted.global.defaultError({msg: "As senhas são diferentes!"})
                return
            }


            axios.post(`${baseApiUrl}/user/formLogin.php`, this.user)
                .then(resp => {
                    if(resp.data.error) {
                        this.$toasted.global.defaultError({msg: resp.data.message})
                        return
                    }
                    else {
                        this.$toasted.global.defaultSuccess({msg: resp.data.message})
                        this.user = {}
                        this.showSignup = false
                    }
                })
                .catch(showError)
        }
    }
}
</script>

<style>
    .auth-content {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .auth-modal {
        background-color: #FFF;
        width: 350px;
        padding: 35px;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.15);

        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .auth-title {
        font-size: 1.2rem;
        font-weight: 100;
        margin-top: 10px;
        margin-bottom: 15px;
    }

    .auth-modal input {
        border: 1px solid #BBB;
        width: 100%;
        margin-bottom: 15px;
        padding: 3px 8px;
        outline: none;
    }

    .auth-modal button {
        align-self: flex-end;
        background-color: #2460ae;
        color: #FFF;
        padding: 5px 15px;
    }

    .auth-modal a {
        margin-top: 35px;
    }

    .auth-modal hr {
        border: 0;
        width: 100%;
        height: 1px;
        background-image: linear-gradient(to right,
            rgba(120, 120, 120, 0),
            rgba(120, 120, 120, 0.75),
            rgba(120, 120, 120, 0));
    }
</style>