const state = {
    user: null,
    userStatus: null,
};
const getters = {
    user: (state) => {
        return state.user;
    },
};
const actions = {
    fetchUser({ commit, state }, userId) {
        commit("setUserStatus", "loading");

        axios
            .get("/api/users/" + userId)
            .then((res) => {
                commit("setUser", res.data);
                commit("setUserStatus", "success");
            })
            .catch((err) => {
                commit("setUserStatus", "error");
                console.log("Unable to fetch the user from server.");
            });
    },
};
const mutations = {
    setUser(state, user) {
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
