<template>
    <div class="flex flex-col items-center py-4">
        <NewPost></NewPost>

        <p v-if="newsStatus == 'loading'">Loading posts...</p>
        <Post v-else v-for="post in posts.data" :key="post.data.post_id" :post="post"></Post>
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
        this.$store.dispatch('fetchNewsPosts')
    },

    computed: {
        ...mapGetters({
            posts: 'newsPosts',
            newsStatus: 'newsPostsStatus'
        })
    }
}
</script>

<style scoped></style>
