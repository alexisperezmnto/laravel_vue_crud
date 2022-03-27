<template>
    <v-app>
        <div class="row background">
            <div class="col-md-12">
                
                <div style="margin-top: 100px;">
                    <h2>{{cliente.nombre}}</h2>
                </div>
                
                <v-data-table
                    :headers="headers"
                    :items="servicios"
                    class="elevation-1"
                    :search="search"
                >
                    <template v-slot:top>
                    <v-toolbar
                        flat
                    >
                        <v-toolbar-title>Servicios</v-toolbar-title>

                        <v-spacer></v-spacer>
                        <v-text-field
                          v-model="search"
                          append-icon="mdi-magnify"
                          label="Buscar"
                          single-line
                          hide-details
                          class="search"
                          clearable
                        ></v-text-field>
                    </v-toolbar>
                    </template>
                    
                    <template v-slot:item.imagen="{item}">
                        <v-img
                            max-height="60"
                            max-width="60"
                            :src="'/images/customers/'+ item.imagen"
                        ></v-img>
                    </template>
                    
                </v-data-table>
            </div>
        </div>
    </v-app>
</template>


<script>
  export default {
    data: () => ({
        usuario: null,
        search: '',
        headers: [
            { text: 'Id', value: 'id' },
            { text: 'Nombre', value: 'nombre' },
            { text: 'Imagen', value: 'imagen' },
            { text: 'Observaciones', value: 'observaciones' },
        ],

        servicios: [],

        cliente: {
            id: '',
            nombre: '',
            imagen: '',
            observaciones: '',
        },
    }),

    created() {
        //Proteger ruta
        if(!this.$store.state.user) {
            return this.$router.push({path: '/'})
        }

        this.usuario = localStorage.getItem('usuario') ? JSON.parse(localStorage.getItem('usuario')) : null
        if(this.usuario) this.cargar()
    },
    
    methods: {
        async cargar () {
            const resp = await axios.get(`/api/clientes/${this.$route.params.id}`, {
                headers: {
                    Authorization: `Bearer ${this.usuario.token}`
                }
            })
            
            this.cliente = resp.data
            this.servicios = resp.data.services
        },
    }
  }
</script>

<style>
    .description {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        overflow: hidden;
    }
    
</style>