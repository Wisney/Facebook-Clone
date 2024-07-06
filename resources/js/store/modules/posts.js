const state = {
    posts: { data: null },
    postsStatus: null,
    postMessage: "",
};
const getters = {
    posts: (state) => {
        return state.posts;
    },
    postsStatus: (state) => {
        return state.postsStatus;
    },
    postMessage: (state) => {
        return state.postMessage;
    },
};
const actions = {
    fetchPosts({ commit, state }) {
        commit("setPostsStatus", "loading");

        axios
            .get("/api/posts")
            .then((res) => {
                commit("setPosts", res.data);
                commit("setPostsStatus", "success");
            })
            .catch((err) => {
                commit("setPostsStatus", "error");
                console.log("Unable to fetch posts");
            });
    },
    fetchUserPosts({ commit, state }, userId) {
        commit("setPostsStatus", "loading");
        axios
            .get("/api/users/" + userId + "/posts")
            .then((res) => {
                commit("setPosts", res.data);
                commit("setPostsStatus", "success");
            })
            .catch((err) => {
                commit("setPostsStatus", "error");
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
        state.posts = posts;
    },
    setPostsStatus(state, status) {
        state.postsStatus = status;
    },
    updateMessage(state, message) {
        state.postMessage = message;
    },
    pushPost(state, post) {
        state.posts.data.unshift(post);
    },
    pushLikes(state, data) {
        state.posts.data[data.postKey].data.attributes.likes = data.likes;
    },
    pushComments(state, data) {
        state.posts.data[data.postKey].data.attributes.comments = data.comments;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
