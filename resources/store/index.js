import Vue from 'vue'   
import Vuex from 'vuex'

Vue.use(Vuex);

const state = {
	user: null
}

const mutations = {
	setUser(state, user){
        state.user = user
    },

    logout(state, user){
        state.user = user
    },
}

const actions = {
	login({commit}, user) {
        commit('setUser', user)
    },

    logout({commit}) {
        const user = null
        commit('logout', user)
    },
}

const getters = {
    isAuthenticated: state => !!state.user
};

export default new Vuex.Store({
	state,
    getters,
	actions,
	mutations,
})