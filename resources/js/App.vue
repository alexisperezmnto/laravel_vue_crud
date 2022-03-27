<template>
    <div>
        <v-app-bar fixed>
            <v-row v-if="isLoggedIn">
                <v-col cols="2">
                    <router-link to='/'><h2>CRUD</h2></router-link>
                </v-col>
                <v-col cols="2">
                    <router-link to='/clientes'><h4>Clientes</h4></router-link>
                </v-col>
                <v-col cols="8" style="text-align:right">
                    <span style="margin-right: 50px; font-weight: bold">{{ this.$store.state.user.nombre }}</span>
                    <span @click="logout" class="logout">Logout</span>
                </v-col>
            </v-row>
            <v-row v-else>
                <v-col cols="2">
                    <router-link to='/'><h2>CRUD</h2></router-link>
                </v-col>
                <v-col cols="10" style="text-align:right">
                    <span style="margin-right: 50px;">
                        <router-link to='/'>Login</router-link>
                    </span>
                    
                    <router-link to='/register'>Register</router-link>
                </v-col>
            </v-row>
        </v-app-bar>

        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                usuario: null
            }
        },
        created() {
            this.usuario = localStorage.getItem('usuario') ? JSON.parse(localStorage.getItem('usuario')) : null
            
            if(this.usuario) {
                this.$store.dispatch('login', this.usuario.user)
            }
        },
        computed : {
            isLoggedIn : function(){ 
                if(this.$store.getters.isAuthenticated) {
                    this.usuario = localStorage.getItem('usuario') ? JSON.parse(localStorage.getItem('usuario')) : null
                }
                
                return this.$store.getters.isAuthenticated
            }
        },
        methods: {
            async logout() {
                
                try {
                    await axios.post('/api/logout', { }, {
                        headers: {
                            Authorization: `Bearer ${this.usuario.token}`
                        }
                    })

                    this.$store.dispatch('logout')
                    localStorage.removeItem('usuario')
                    
                    this.$router.push({path: '/'})
                } catch(error) {
                    console.log(error.response.data)
                }
            }
        }
    }
</script>

<style>
    .logout {
        cursor: pointer;
    }
</style>