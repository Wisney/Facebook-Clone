const state = {
    user: null,
    userStatus: null,
};
const getters = {
    authUser: (state) => {
        return state.user;
    },
};
const actions = {
    fetchAuthUser({ commit, state }) {
        axios
            .get("/api/auth-user")
            .then((res) => {
                commit("setAuthUser", res.data);
            })
            .catch((err) => {
                console.log("Unable to fetch auth user");
            });
    },
};
const mutations = {
    setAuthUser(state, user) {
        state.user = user;
    },
    setUserStatus(state, userStatus) {
        state.userStatus = userStatus;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
