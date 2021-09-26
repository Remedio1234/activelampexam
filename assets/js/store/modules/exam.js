import axios from "axios";
//state
export const state = {
    result      : null,
    is_submit   : false,
    is_error    : []
}
//getters
export const getters = {
    result       : state => state.result,
    is_submit    : state => state.is_submit,
    is_error     : state => state.is_error
}
// mutations
export const mutations = {
    SET_RESULT(state, result) {
        state.result = result;
    },
    SET_SUBMIT(state, is_submit) {
        state.is_submit = is_submit;
    },
    SET_ERROR(state, is_error) {
        state.is_error = is_error;
    }
}
//actions
export const actions = {
    isFormSubmit ({ commit }, payload) {
        commit('SET_SUBMIT', payload)
    },
    isErrorSet ({ commit }, payload) {
        commit('SET_ERROR', payload)
    },
    getResult ({ commit }, payload) {
        commit('SET_RESULT', payload)
    },
    async urlForm({ dispatch }, form){
        return await new Promise((resolve, reject) => {
            dispatch('isFormSubmit', true)
            dispatch('isErrorSet', [])
            axios.post('/create', form)
                .then(res => {
                    if( res && res.data != undefined ){
                        resolve(res)
                        return res
                    }
                })
                .catch(function (error) {
                    if(error && error.response.status == 422)
                        dispatch('isErrorSet', [{error : error.response.status, msg: error.response.data}])
                    
                    dispatch('isFormSubmit', false)
                    reject(error);
            });
        })
    },
}