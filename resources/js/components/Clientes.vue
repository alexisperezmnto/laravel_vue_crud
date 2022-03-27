<template>
    <v-app>
        <div class="row">
            <div class="col-md-12">
                <div style="margin-top: 100px; margin-bottom: 30px">
                <v-btn
                    color="primary"
                    class="mb-2"
                    @click="openDialog"
                    >
                    Nuevo Cliente
                </v-btn>
                </div>   

                <v-data-table
                    :headers="headers"
                    :items="clientes"
                    sort-by=""
                    class="elevation-1"
                    :search="search"
                >
                
                    <template v-slot:top>
                        <v-toolbar
                            flat
                        >
                            <v-toolbar-title>Clientes</v-toolbar-title>
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
                            <v-dialog
                                v-model="dialog"
                                max-width="500px"
                            >
                            
                            <v-card>
                                <v-form ref="form">
                                    <v-card-title>
                                        <span class="text-h5">{{ tituloForm }}</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-container>
                                            <v-row>
                                                <v-col
                                                    cols="12"
                                                >
                                                    <v-text-field
                                                        clearable
                                                        v-model="datosCliente.nombre"
                                                        label="Nombre"
                                                        :error-messages="errorMessage.nombre"
                                                        :rules="rules.nombre"
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                >
                                                    <img :src="imageUrl" height="150" v-if="imageUrl"/>

                                                    <v-text-field label="Seleccionar imagen" @click='seleccionarImagen' v-model='imageName' prepend-icon="mdi-file-image"></v-text-field>
                                                    
                                                    <input
                                                        type="file"
                                                        style="display: none"
                                                        ref="imagen"
                                                        accept="image/jpeg, image/jpg, image/png"
                                                        @change="imagenSeleccionada"
                                                    />
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                >
                                                    <v-text-field
                                                        clearable
                                                        v-model="datosCliente.cedula"
                                                        label="Cédula"
                                                        :error-messages="errorMessage.cedula"
                                                        :rules="rules.cedula"
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                >
                                                    <v-text-field
                                                        v-model="datosCliente.email"
                                                        :rules="rules.email"
                                                        :error-messages="errorMessage.email"
                                                        label="E-mail"
                                                        required
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                >
                                                    <v-text-field
                                                        v-model="datosCliente.telefono"
                                                        :rules="rules.telefono"
                                                        :error-messages="errorMessage.telefono"
                                                        label="Teléfono"
                                                        required
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                >
                                                    <v-textarea
                                                        clearable
                                                        no-resize
                                                        rows="3"
                                                        label="Observaciones"
                                                        v-model="datosCliente.observaciones"
                                                        :error-messages="errorMessage.observaciones"
                                                        :rules="rules.observaciones"
                                                    ></v-textarea>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                >
                                                    <v-select
                                                        clearable
                                                        v-model="datosCliente.servicios"
                                                        :items="servicios"
                                                        item-text="nombre"
                                                        item-value="id"
                                                        label="Servicio"
                                                        multiple
                                                        chips
                                                        :error-messages="errorMessage.servicios"
                                                        :rules="rules.servicios"
                                                    ></v-select>
                                                </v-col>
                                            </v-row>
                                        </v-container>
                                    </v-card-text>

                                    <div class="text-center text-danger my-5" style="color: #ff5252">
                                        {{ errorForm }}
                                    </div>
                                    
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            color="blue darken-1"
                                            text
                                            @click="closeDialog"
                                        >
                                            Cancelar
                                        </v-btn>
                                        <v-btn
                                            color="blue darken-1"
                                            text
                                            @click="crear"
                                        >
                                            Guardar
                                        </v-btn>
                                    </v-card-actions>
                                </v-form>
                            </v-card>
                            </v-dialog>
                        </v-toolbar>
                    </template>
                    
                    <template v-slot:item.imagen="{item}">
                        <v-img
                            max-height="60"
                            max-width="60"
                            :src="'/images/customers/'+ item.imagen"
                        ></v-img>
                    </template>
                    
                    <template v-slot:item.actions="{ item }">
                        <router-link :to="{path: `/cliente/${item.id}`}">
                            <v-icon
                                small
                            >
                                mdi-eye 
                            </v-icon>
                        </router-link>
                        <v-icon
                            small
                            @click="editar(item)"
                        >
                            mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click="eliminar(item)"
                        >
                            mdi-delete
                        </v-icon>
                    </template>
                    
                    <template v-slot:item.observaciones="{ item }">
                        <span class="observaciones">{{item.observaciones}}</span>
                    </template>
                </v-data-table>

            </div>
        </div>
    </v-app>
</template>

<script>
    export default {
        data() {
            return {
                usuario: null,
                search: '',
                dialog: false,

                headers: [
                    { text: 'Id', value: 'id' },
                    { text: 'Nombre', value: 'nombre', },
                    { text: 'Imagen', value: 'imagen' },
                    { text: 'Cédula', value: 'cedula' },
                    { text: 'E-mail', value: 'email' },
                    { text: 'Teléfono', value: 'telefono' },
                    { text: 'Observaciones', value: 'observaciones' },
                    { text: 'Acciones', value: 'actions', width: '100px', sortable: false },
                ],

                clientes: [],
                servicios: [],
                editarIndice: -1,

                datosCliente: {
                    nombre: '',
                    imagen: '',
                    cedula: '',
                    email: '',
                    telefono: '',
                    observaciones: '',
                    servicios: [],
                },

                cliente: {
                    nombre: '',
                    imagen: '',
                    cedula: '',
                    email: '',
                    telefono: '',
                    observaciones: '',
                    servicios: [],
                },
                
                rules: {
                    nombre: [
                        v => !!v || 'El campo nombre es obligatorio',
                        v => (v && v.length >= 2) || 'El campo nombre debe tener mínimo 2 caracteres',
                        v => (v && v.length <= 50) || 'El campo nombre debe tener máximo 50 caracteres',
                    ],
                    cedula: [
                        v => !!v || 'El campo cédula es obligatorio',
                        v => (v && v.length >= 5) || 'El campo cédula debe tener mínimo 5 dígitos',
                        v => (v && v.length <= 15) || 'El campo cédula debe tener máximo 15 dígitos',
                    ],
                    email: [
                        v => !!v || 'La dirección de correo electrónico es obligatoria',
                        v => /.+@.+\..+/.test(v) || 'El correo electrónico no tiene un formato válido',
                    ],
                    telefono: [
                        v => !!v || 'El campo teléfono es obligatorio',
                        v => (v && v.length >= 5) || 'El campo teléfono debe tener mínimo 5 dígitos',
                        v => (v && v.length <= 20) || 'El campo teléfono debe tener máximo 20 dígitos',
                    ],
                    observaciones: [
                        v => !!v || 'El campo observaciones es obligatorio',
                        v => (v && v.length >= 10) || 'El campo observaciones debe tener mínimo 10 caracteres',
                        v => (v && v.length <= 500) || 'El campo observaciones debe tener máximo 500 caracteres',
                    ],
                    servicios: [
                        v => !!v || 'El campo servicio es obligatorio',
                        v => v && v.length > 0 || 'El campo servicio es obligatorio'
                    ]
                },

                errorMessage: {
                    nombre: '',
                    imagen: '',
                    cedula: '',
                    email: '',
                    telefono: '',
                    observaciones: '',
                    servicios: ''
                },
                
                errorForm: '',

                imageUrl: '',
                imageFile: null,
                imageName: '',
            }
        },

        created() {
            //Proteger ruta
            if(!this.$store.state.user) {
                return this.$router.push({path: '/'})
            }

            this.usuario = localStorage.getItem('usuario') ? JSON.parse(localStorage.getItem('usuario')) : null
            if(this.usuario) this.cargar()
        },

        computed: {
            tituloForm() {
                return this.editarIndice === -1 ? 'Nuevo Cliente' : 'Actualizar Cliente'
            },
        },
        
        watch: {
            dialog(val) {
                val || this.closeDialog()
            },
        },

        methods: {
            openDialog () {
                this.dialog = true
            },

            async cargar() {
                try {
                    const resp = await axios.get(`/api/clientes`, {
                        headers: {
                            Authorization: `Bearer ${this.usuario.token}`
                        }
                    })
                    this.clientes = resp.data
                    
                    const resp2 = await axios.get(`/api/servicios`, {
                        headers: {
                            Authorization: `Bearer ${this.usuario.token}`
                        }
                    })
                    this.servicios = resp2.data.map( servicio => {
                        return {
                            id: servicio.id,
                            nombre: servicio.nombre
                        }
                    })
                } catch(error) {
                    console.log(error.response)
                }
            },

            editar(cliente) {
                this.editarIndice = this.clientes.indexOf(cliente)
                this.datosCliente = Object.assign({}, cliente)
                this.datosCliente.servicios = this.datosCliente.services.map(servicio => servicio.id)
                this.imageUrl = `/images/customers/${this.datosCliente.imagen}`
                this.dialog = true
            },

            eliminar(cliente) {
                this.editarIndice = this.clientes.indexOf(cliente)
                this.datosCliente = Object.assign({}, cliente)
                
                this.$swal({
                    title: '¿Está seguro de eliminar el cliente?',
                    showCancelButton: true,
                    confirmButtonText: 'Ok',
                    cancelButtonText: `Cancelar`,
                    background: '#161D31',
                    icon: 'question'
                }).then(async (result) => {
                    if(result.isConfirmed) {
                        try {
                            await axios.delete(`/api/clientes/${this.datosCliente.id}`, {
                                headers: {
                                    Authorization: `Bearer ${this.usuario.token}`
                                }
                            })
                            this.clientes.splice(this.editarIndice, 1)
                            this.$swal({
                                title: 'Cliente eliminado correctamente',
                                background: '#161D31',
                                icon: 'success'
                            })
                        } catch(error) {
                            console.log(error.response.data)
                            this.$swal({
                                title: 'Hubo un error',
                                background: '#161D31',
                                icon: 'error'
                            })
                        }
                    }
                    
                    this.closeDialog()
                
                })

            },

            closeDialog () {
                this.dialog = false
                this.$nextTick(() => {
                    this.datosCliente = Object.assign({}, this.cliente)
                    this.editarIndice = -1

                    if (this.$refs['form']) {
                        this.$refs.form.reset();
                    }

                    this.errorMessage = {
                        nombre: '',
                        imagen: '',
                        cedula: '',
                        email: '',
                        telefono: '',
                        observaciones: '',
                        servicios: ''
                    }

                    this.imageName = ''
                    this.imageFile = ''
                    this.imageUrl = ''
                })
            },

            async crear () {
                if(!this.$refs.form.validate()) return
                
                this.$swal({
                    title: `¿Está seguro de ${this.editarIndice > -1 ? 'actuliazar' : 'crear'} el cliente?`,
                    showCancelButton: true,
                    confirmButtonText: 'Ok',
                    cancelButtonText: `Cancelar`,
                    background: '#161D31',
                    icon: 'question'
                }).then(async (result) => {
                    if(result.isConfirmed) {

                        if(this.editarIndice > -1) {
                        
                            //Actualizar cliente
                            try {

                                let data = new FormData()
                                
                                data.append('_method', 'PATCH')

                                _.each(this.datosCliente, (value, key) => {
                                    data.append(key, value)
                                })

                                const resp = await axios.post(`/api/clientes/${this.datosCliente.id}`, data, {
                                    headers: {
                                        Authorization: `Bearer ${this.usuario.token}`
                                    }
                                })

                                Object.assign(this.clientes[this.editarIndice], resp.data)

                                this.$swal({
                                    title: 'Cliente actualizado correctamente',
                                    background: '#161D31',
                                    icon: 'success'
                                })
                                
                                this.closeDialog()
                            } catch(error) {
                                
                                if(error.response.data.error) {
                                    this.errorForm = error.response.data.error
                                } else {
                                    this.errorMessage = error.response.data.errors
                                }
                                
                                this.$swal({
                                    title: 'Hubo un error',
                                    background: '#161D31',
                                    icon: 'error'
                                })
                            }
                        } else {

                            //Nuevo cliente
                            try {

                                let data = new FormData()

                                _.each(this.datosCliente, (value, key) => {
                                    data.append(key, value)
                                })

                                const resp = await axios.post(`/api/clientes`, data, {
                                    headers: {
                                        Authorization: `Bearer ${this.usuario.token}`
                                    }
                                })

                                this.clientes.unshift(resp.data)
                                
                                this.$swal({
                                    title: 'Producto creado correctamente',
                                    background: '#161D31',
                                    icon: 'success'
                                })
                                this.closeDialog()
                            } catch(error) {
                                if(error.response.data.error) {
                                    this.errorForm = error.response.data.error
                                } else {
                                    this.errorMessage = error.response.data.errors
                                }
                            }
                        }
                    }
                })
            },

            seleccionarImagen() {
                this.$refs.imagen.click()
            },

            imagenSeleccionada(e) {
                const files = e.target.files
                if(files[0] !== undefined) {
                    this.imageName = files[0].name
                    if (this.imageName.lastIndexOf('.') <= 0) {
                        return
                    }
                    const fr = new FileReader()
                    fr.readAsDataURL(files[0])
                    fr.addEventListener('load', () => {
                        this.imageUrl = fr.result
                        this.imageFile = files[0]
                        this.datosCliente.imagen = this.imageFile
                    })
                } else {
                    this.imageName = ''
                    this.imageFile = ''
                    this.imageUrl = ''
                }
            },
        }
    }
</script>

<style>

  .observaciones {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    overflow: hidden;
  }
  
</style>