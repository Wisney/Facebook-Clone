<template>
    <div class="flex flex-col items-center">
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
                <p class="ml-4 text-2xl text-gray-100">{{ user.data.attributes.name }}</p>
            </div>
        </div>

        <p v-if="postLoading">Loading posts...</p>
        <Post v-else v-for="post in posts.data" :key="post.data.post_id" :post="post"></Post>

        <p v-if="!postLoading && posts.data.length == 0">You don't have posts. Post something! :)</p>
    </div>
</template>

<script>
import axios from 'axios';
import Post from '../../components/Post';

export default {
    name: "Show",

    components: {
        Post
    },

    data: () => {
        return {
            user: { data: { attributes: { name: '' } } },
            posts: {},
            userLoading: true,
            postLoading: true,
        }
    },

    mounted() {
        axios.get('/api/users/' + this.$route.params.userId).then(res => {
            this.user = res.data;
        }).catch(err => {
            console.log('Unable to fetch the user from server.');
        }).finally(() => {
            this.userLoading = false;
        });

        axios.get('/api/users/' + this.$route.params.userId + '/posts').then(res => {
            this.posts = res.data;
        }).catch(err => {
            console.log('Unable to fetch posts');
        }).finally(() => {
            this.postLoading = false;
        });
    },
}
</script>

<style scoped></style>
