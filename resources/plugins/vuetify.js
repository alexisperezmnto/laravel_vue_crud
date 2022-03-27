import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

import es from 'vuetify/lib/locale/es'

Vue.use(Vuetify)

const opts = {
    lang: {
        locales: { es },
        current: 'es',
    },
}

export default new Vuetify(opts)