const state = {
    newsPosts: { data: null },
    newsPostsStatus: null,
};
const getters = {
    newsPosts: (state) => {
        return state.newsPosts;
    },
    newsPostsStatus: (state) => {
        return state.newsPostsStatus;
    },
};
const actions = {
    fetchNewsPosts({ commit, state }) {
        commit("setNewsPostsStatus", "loading");

        axios
            .get("/api/posts")
            .then((res) => {
                commit("setPosts", res.data);
                commit("setNewsPostsStatus", "success");
            })
            .catch((err) => {
                commit("setNewsPostsStatus", "error");
                console.log("Unable to fetch posts");
            });
    },
};
const mutations = {
    setPosts(state, posts) {
        state.newsPosts = posts;
    },
    setNewsPostsStatus(state, status) {
        state.newsPostsStatus = status;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
