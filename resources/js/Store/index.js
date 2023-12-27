import { createStore } from 'vuex'
 
export default new createStore({
    state: {
        classes: [],
        wishes: [],
    },
    getters: {
        GET_CLASSES: state => {
            return state.classes
        },
        GET_WISHES: state => {
            return state.wishes
        },
    },
    mutations: {
        SET_CLASSES: (state, payload) => {
            state.classes = payload
        },
        ADD_WISHES: (state, payload) => {
            state.wishes.push(payload)
        },
        ADD_CLASSES: (state, payload) => {
            state.classes.push(payload)
        },
        DEL_WISHES: (state, payload) => {
            state.wishes = state.wishes.filter(el => el.id !== payload.id)
        },
        DEL_CLASSES: (state, payload) => {
            state.wishes = state.wishes.filter(el => el.id !== payload.id)
        },
        DEL_CLASSES_IDS: (state, payload) => {
            state.classesIds = state.classesIds.filter(el => el !== payload.id)
        },
        SET_WISHES: (state, payload) => {
            state.wishes = payload
        }
    },
    actions: {
        INIT_LOAD: async (context, input) => {
            let url = '';
            if (input === null || typeof input === 'undefined') {
                url = '/api/client/wishes';
            } else {
                url = '/api/client/' + input + '/wishes';
            }
            await Axios.get(url)
                .then(response => {
                        context.commit('SET_WISHES', response.data.wishes);
                        context.commit('SET_CLASSES', response.data.classes);
                    }
                )
                .catch(e => {
                    console.log(e)
                })
        },
        GET_WISHES: async (context, payload) => {
            await Axios.get('/api/product/category/' + payload + '/attribute/assigned')
                .then(response => {
                    let catAttrs = response.data.catAttr;
                    context.commit('SET_ASSIGNED_WISHES', catAttrs);
                    let attrIds = catAttrs.map(function (item) {
                        return item.id
                    });
                    context.commit('SET_WISHES_IDS', attrIds)
                })
                .catch(e => {
                    console.log(e)
                })
        },
        GET_CLASSES: async (context) => {
            console.log(context.wishesIds);
            await Axios.post('/api/product/category/attribute/available', {'ids': context.wishesIds})
                .then(response => {
                    context.commit('SET_AVAILABLE_WISHES', response.data.attr)
                })
                .catch(e => {
                    console.log(e)
                })
        },
        ADD_WISHES: (context, payload) => {
            context.commit('ADD_WISHES', payload);
            context.commit('ADD_CLASSES_IDS', payload)
        },
        DEL_WISHES: (context, payload) => {
            context.commit('DEL_WISHES', payload);
            context.commit('DEL_CLASSES_IDS', payload)
        }
    }
});
