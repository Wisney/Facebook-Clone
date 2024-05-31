<template>
    <div class="flex flex-col items-center" v-if="status.user == 'success' && user">
        <div class="relative mb-8">
            <div class="w-100 h-64 overflow-hidden z-10">
                <img src="https://images.pexels.com/photos/355465/pexels-photo-355465.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
                    alt="user background image" class="object-cover w-full">
            </div>
            <div class="absolute flex items-center bottom-0 left-0 -mb-8 ml-12 z-20">
                <div class="w-32">
                    <img src="https://img.freepik.com/free-photo/young-bearded-man-with-striped-shirt_273609-5677.jpg?size=626&ext=jpg&ga=GA1.1.1224184972.1715040000&semt=sph"
                        alt="User Profile Image" class="w-32 h-32 object-cover rounded-full border-gray-200 shadow-lg">
                </div>
                <p v-if="user" class="ml-4 text-2xl text-gray-100">{{ user.data.attributes.name }}</p>
            </div>

            <div class="absolute flex items-center bottom-0 right-0 mb-4 mr-12 z-20">
                <button v-if="friendButtonText && friendButtonText != 'Accept'" class="py-1 px-3 bg-gray-400 rounded-md"
                    @click="$store.dispatch('sendFriendRequest', $route.params.userId)">
                    {{ friendButtonText }}
                </button>
                <button v-if="friendButtonText && friendButtonText == 'Accept'"
                    class="mr-2 py-1 px-3 bg-blue-500 rounded-md"
                    @click="$store.dispatch('acceptFriendRequest', $route.params.userId)">
                    Accept
                </button>
                <button v-if="friendButtonText && friendButtonText == 'Accept'" class="py-1 px-3 bg-gray-400 rounded-md"
                    @click="$store.dispatch('ignoreFriendRequest', $route.params.userId)">
                    Ignore
                </button>
            </div>
        </div>

        <div v-if="!posts || status.posts == 'loading'">Loading posts...</div>
        <div v-else-if="posts && posts.length < 1">
            You don't have posts. Post something! :)
        </div>
        <Post v-else v-for="post in posts.data" :key="post.data.post_id" :post="post"></Post>

    </div>
</template>

<script>
import axios from 'axios';
import Post from '../../components/Post';
import { mapGetters } from 'vuex';

export default {
    name: "Show",

    components: {
        Post
    },
    mounted() {
        this.$store.dispatch('fetchUser', this.$route.params.userId);
        this.$store.dispatch('fetchUserPosts', this.$route.params.userId);
    },
    computed: {
        ...mapGetters({
            user: 'user',
            posts: 'posts',
            status: 'status',
            friendButtonText: 'friendButtonText'
        })
    },
}
</script>

<style scoped></style>
