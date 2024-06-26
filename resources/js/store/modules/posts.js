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
    likePost({ commit, state }, data) {
        axios
            .post(`/api/posts/${data.postId}/like`)
            .then((res) => {
                commit("pushLikes", { likes: res.data, postKey: data.postKey });
            })
            .catch((err) => {
                console.log(err);
            });
    },
    commentPost({ commit, state }, data) {
        axios
            .post(`/api/posts/${data.postId}/comment`, { body: data.body })
            .then((res) => {
                commit("pushComments", {
                    comments: res.data,
                    postKey: data.postKey,
                });
            })
            .catch((err) => {
                console.log(err);
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
    pushLikes(state, data) {
        state.newsPosts.data[data.postKey].data.attributes.likes = data.likes;
    },
    pushComments(state, data) {
        state.newsPosts.data[data.postKey].data.attributes.comments =
            data.comments;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
