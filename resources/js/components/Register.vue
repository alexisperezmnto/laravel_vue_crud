<template>
  <v-app>
    <div class="row">
        <div style="width: 500px; margin: 0 auto">

          <h1 class="text-center" style="margin-top: 100px">Registrarme</h1>      
            
          <v-form
            ref="form"
            lazy-validation
          >
            <v-text-field
              v-model="form.nombre"
              :rules="rules.nombre"
              :error-messages="errorMessage.nombre"
              label="Nombre"
              required
            ></v-text-field>

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

            <div class="text-center">
              <v-btn
                color="primary"
                class=""
                @click="login"
              >
                Registrarme
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
        nombre: '',
        email: '',
        password: '',
      }, 
      rules: {
        nombre: [
          v => !!v || 'El campo nombre es obligatorio'
        ],
        email: [
          v => !!v || 'La dirección de correo electrónico es obligatoria',
          v => /.+@.+\..+/.test(v) || 'El correo electrónico no tiene un formato válido',
        ],
        password: [
          v => !!v || 'El campo password es obligatorio'
        ],
      },
      errorMessage: {
        nombre: '',
        email: '',
        password: '',
      }
    }),

    created() {
      if(this.$store.state.user) {
        this.$router.push({path: '/clientes'})
      }
    },

    methods: {
      async login () {
        if(!this.$refs.form.validate()) return
        
        try { 
          const resp = await axios.post('/api/register', this.form)
          this.$router.push({path: '/login'})
        } catch (error) {
          this.errorMessage = error.response.data.errors
        }
      }
    },
  }
</script>