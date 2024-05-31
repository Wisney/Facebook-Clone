const state = {
    user: null,
    userStatus: null,
    posts: null,
    postStatus: null,
};
const getters = {
    user: (state) => {
        return state.user;
    },
    posts: (state) => {
        return state.posts;
    },
    status: (state) => {
        return {
            user: state.userStatus,
            posts: state.postStatus,
        };
    },
    friendship: (state) => {
        return state.user?.data?.attributes.friendship;
    },
    friendButtonText: (state, getters, rootState) => {
        if (getters.friendship === null) {
            return "Add Friend";
        } else if (
            getters.friendship?.data.attributes.confirmed_at === null &&
            getters.friendship?.data.attributes.friend_id !==
                rootState.User.user.data.user_id
        ) {
            return "Pending Friend Request";
        } else if (getters.friendship?.data.attributes.confirmed_at != null) {
            return "";
        }
        return "Accept";
    },
};
const actions = {
    fetchUser({ commit, dispatch }, userId) {
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
    sendFriendRequest({ commit, state }, friendId) {
        axios
            .post("/api/friend-request", { friend_id: friendId })
            .then((res) => {
                commit("setUserFriendship", res.data);
            })
            .catch((err) => {
                console.log(err);
            });
    },
    acceptFriendRequest({ commit, state }, userId) {
        axios
            .post("/api/friend-request-response", {
                user_id: userId,
                status: 1,
            })
            .then((res) => {
                commit("setUserFriendship", res.data);
            })
            .catch((err) => {
                console.log(err);
            });
    },
    ignoreFriendRequest({ commit, state }, userId) {
        axios
            .delete("/api/friend-request-response/delete", {
                data: { user_id: userId },
            })
            .then((res) => {
                commit("setUserFriendship", null);
            })
            .catch((err) => {
                console.log(err);
            });
    },
};
const mutations = {
    setUser(state, user) {
        state.user = user;
    },
    setPosts(state, posts) {
        state.posts = posts;
    },
    setUserFriendship(state, friendship) {
        state.user.data.attributes.friendship = friendship;
    },
    setUserStatus(state, userStatus) {
        state.userStatus = userStatus;
    },
    setPostsStatus(state, postsStatus) {
        state.postsStatus = postsStatus;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
