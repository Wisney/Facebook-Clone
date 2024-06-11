const state = {
    newsPosts: { data: null },
    newsPostsStatus: null,
    postMessage: "",
};
const getters = {
    newsPosts: (state) => {
        return state.newsPosts;
    },
    newsPostsStatus: (state) => {
        return state.newsPostsStatus;
    },
    postMessage: (state) => {
        return state.postMessage;
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
    postMessage({ commit, state }) {
        axios
            .post("/api/posts", { body: state.postMessage })
            .then((res) => {
                commit("pushPost", res.data);
                commit("updateMessage", "");
            })
            .catch((err) => {
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
    updateMessage(state, message) {
        state.postMessage = message;
    },
    pushPost(state, post) {
        state.newsPosts.data.unshift(post);
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
