<template>
    <div class="flex flex-col items-center py-4">
        <NewPost></NewPost>

        <p v-if="newsStatus == 'loading'">Loading posts...</p>
        <Post v-else v-for="(post, postKey) in posts.data" :key="postKey" :post="post"></Post>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import NewPost from '../components/NewPost';
import Post from '../components/Post';

export default {
    name: "NewsFeed",

    components: {
        NewPost,
        Post
    },

    mounted() {
        this.$store.dispatch('fetchPosts')
    },

    computed: {
        ...mapGetters({
            posts: 'posts',
            newsStatus: 'postsStatus'
        })
    }
}
</script>

<style scoped></style>
