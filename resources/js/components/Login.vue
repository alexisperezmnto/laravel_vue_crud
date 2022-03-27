<template>
  <v-app>
    <div class="row">
        <div style="width: 500px; margin: 0 auto">

          <h1 class="text-center" style="margin-top: 100px">Iniciar Sesión</h1>      
            
          <v-form
            ref="form"
            lazy-validation
          >

            <v-text-field
              v-model="form.email"
              :rules="rules.email"
              :error-messages="errorMessage.email"
              label="Email"
              required
            ></v-text-field>

            <v-text-field
              v-model="form.password"
              :rules="rules.password"
              :error-messages="errorMessage.password"
              label="Contraseña"
              type="password"
              required
            ></v-text-field>
            
            <div class="text-center text-danger my-5" style="color: #ff5252">
              {{ errorForm }}
            </div>

            <div class="text-center">
              <v-btn
                color="primary"
                class=""
                @click="login"
              >
                Iniciar Sesión
              </v-btn>
            </div>
            
          </v-form>

        </div>
    </div>
  </v-app>
</template>

<script>
  export default {
    data: () => ({
      form: {
        email: '',
        password: '',
      }, 
      rules: {
        email: [
          v => !!v || 'La dirección de correo electrónico es obligatoria',
          v => /.+@.+\..+/.test(v) || 'El correo electrónico no tiene un formato válido',
        ],
        password: [
          v => !!v || 'El campo password es obligatorio'
        ],
      },
      errorMessage: {
        email: '',
        password: '',
      },
      errorForm: ''
    }),

    created() {
      if(this.$store.state.user) {
        this.$router.push({path: '/clientes'})
      }
    },

    methods: {
      async login () {
        this.errorForm = ''

        if(!this.$refs.form.validate()) return
        
        try { 
          const resp = await axios.post('/api/login', this.form)
          
          this.$store.dispatch('login', resp.data.user)
          localStorage.setItem('usuario', JSON.stringify(resp.data))

          this.$router.push({path: '/clientes'})

        } catch (error) {
          if(error.response.data.error) {
            this.errorForm = error.response.data.error
          } else {
            this.errorMessage = error.response.data.errors
          }
        }
      }
    },
  }
</script>