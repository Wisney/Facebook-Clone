<template>
    <div class="bg-white rounded shadow w-2/3 mt-6 overflow-hidden">
        <div class="flex flex-col p-4">
            <div class="flex items-center">
                <div class="w-8">
                    <img src="https://img.freepik.com/free-photo/young-bearded-man-with-striped-shirt_273609-5677.jpg?size=626&ext=jpg&ga=GA1.1.1224184972.1715040000&semt=sph"
                        alt="User Profile Image" class="w-8 h-8 object-cover rounded-full">
                </div>
                <div class="ml-3">
                    <div class="text-sm font-bold">{{ post.data.attributes.posted_by.data.attributes.name }}</div>
                    <div class="text-sm text-gray-500">{{ post.data.attributes.posted_at }}</div>
                </div>
            </div>
            <div class="mt-4">
                <p>{{ post.data.attributes.body }}</p>
            </div>
        </div>
        <div class="w-full" v-if="post.data.attributes.image">
            <img class="w-full" :src="post.data.attributes.image" alt="post image">
        </div>

        <div class="px-4 pt-2 flex justify-between text-gray-700 text-sm">
            <div class="flex">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="fill-current w-5 h-5">
                    <path
                        d="M20.8 15.6c.4-.5.6-1.1.6-1.7 0-.6-.3-1.1-.5-1.4.3-.7.4-1.7-.5-2.6-.7-.6-1.8-.9-3.4-.8-1.1.1-2 .3-2.1.3-.2 0-.4.1-.7.1 0-.3 0-.9.5-2.4.6-1.8.6-3.1-.1-4.1-.7-1-1.8-1-2.1-1-.3 0-.6.1-.8.4-.5.5-.4 1.5-.4 2-.4 1.5-2 5.1-3.3 6.1l-.1.1c-.4.4-.6.8-.8 1.2-.2-.1-.5-.2-.8-.2H3.7c-1 0-1.7.8-1.7 1.7v6.8c0 1 .8 1.7 1.7 1.7h2.5c.4 0 .7-.1 1-.3l1 .1c.2 0 2.8.4 5.6.3.5 0 1 .1 1.4.1.7 0 1.4-.1 1.9-.2 1.3-.3 2.2-.8 2.6-1.6.3-.6.3-1.2.3-1.6.8-.8 1-1.6.9-2.2.1-.3 0-.6-.1-.8zM3.7 20.7c-.3 0-.6-.3-.6-.6v-6.8c0-.3.3-.6.6-.6h2.5c.3 0 .6.3.6.6v6.8c0 .3-.3.6-.6.6H3.7zm16.1-5.6c-.2.2-.2.5-.1.7 0 0 .2.3.2.7 0 .5-.2 1-.8 1.4-.2.2-.3.4-.2.6 0 0 .2.6-.1 1.1-.3.5-.9.9-1.8 1.1-.8.2-1.8.2-3 .1h-.1c-2.7.1-5.4-.3-5.4-.3H8v-7.2c0-.2 0-.4-.1-.5.1-.3.3-.9.8-1.4 1.9-1.5 3.7-6.5 3.8-6.7v-.3c-.1-.5 0-1 .1-1.2.2 0 .8.1 1.2.6.4.6.4 1.6-.1 3-.7 2.1-.7 3.2-.2 3.7.3.2.6.3.9.2.3-.1.5-.1.7-.1h.1c1.3-.3 3.6-.5 4.4.3.7.6.2 1.4.1 1.5-.2.2-.1.5.1.7 0 0 .4.4.5 1 0 .3-.2.6-.5 1z" />
                </svg>
                <p class="ml-1">{{ post.data.attributes.likes.like_count }} likes</p>
            </div>

            <div>
                <p>{{ post.data.attributes.comments.comment_count }} comments</p>
            </div>
        </div>

        <div class="flex justify-between border-1 border-gray-400 m-4">
            <button class="flex justify-center py-2 rounded-lg text-sm w-full focus:outline-none"
                :class="[post.data.attributes.likes.user_likes_post ? 'bg-blue-600 text-white' : '']"
                @click="$store.dispatch('likePost', { postId: post.data.post_id, postKey: $vnode.key })">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="fill-current w-5 h-5">
                    <path
                        d="M20.8 15.6c.4-.5.6-1.1.6-1.7 0-.6-.3-1.1-.5-1.4.3-.7.4-1.7-.5-2.6-.7-.6-1.8-.9-3.4-.8-1.1.1-2 .3-2.1.3-.2 0-.4.1-.7.1 0-.3 0-.9.5-2.4.6-1.8.6-3.1-.1-4.1-.7-1-1.8-1-2.1-1-.3 0-.6.1-.8.4-.5.5-.4 1.5-.4 2-.4 1.5-2 5.1-3.3 6.1l-.1.1c-.4.4-.6.8-.8 1.2-.2-.1-.5-.2-.8-.2H3.7c-1 0-1.7.8-1.7 1.7v6.8c0 1 .8 1.7 1.7 1.7h2.5c.4 0 .7-.1 1-.3l1 .1c.2 0 2.8.4 5.6.3.5 0 1 .1 1.4.1.7 0 1.4-.1 1.9-.2 1.3-.3 2.2-.8 2.6-1.6.3-.6.3-1.2.3-1.6.8-.8 1-1.6.9-2.2.1-.3 0-.6-.1-.8zM3.7 20.7c-.3 0-.6-.3-.6-.6v-6.8c0-.3.3-.6.6-.6h2.5c.3 0 .6.3.6.6v6.8c0 .3-.3.6-.6.6H3.7zm16.1-5.6c-.2.2-.2.5-.1.7 0 0 .2.3.2.7 0 .5-.2 1-.8 1.4-.2.2-.3.4-.2.6 0 0 .2.6-.1 1.1-.3.5-.9.9-1.8 1.1-.8.2-1.8.2-3 .1h-.1c-2.7.1-5.4-.3-5.4-.3H8v-7.2c0-.2 0-.4-.1-.5.1-.3.3-.9.8-1.4 1.9-1.5 3.7-6.5 3.8-6.7v-.3c-.1-.5 0-1 .1-1.2.2 0 .8.1 1.2.6.4.6.4 1.6-.1 3-.7 2.1-.7 3.2-.2 3.7.3.2.6.3.9.2.3-.1.5-.1.7-.1h.1c1.3-.3 3.6-.5 4.4.3.7.6.2 1.4.1 1.5-.2.2-.1.5.1.7 0 0 .4.4.5 1 0 .3-.2.6-.5 1z" />
                </svg>
                <p class="ml-2">Like</p>
            </button>
            <button
                class="flex justify-center py-2 rounded-lg text-sm text-gray-700 w-full hover:bg-gray-200 focus:outline-none"
                @click="comments = !comments">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="fill-current w-5 h-5">
                    <path
                        d="M20.3 2H3.7C2 2 .6 3.4.6 5.2v10.1c0 1.7 1.4 3.1 3.1 3.1V23l6.6-4.6h9.9c1.7 0 3.1-1.4 3.1-3.1V5.2c.1-1.8-1.3-3.2-3-3.2zm1.8 13.3c0 1-.8 1.8-1.8 1.8H9.9L5 20.4V17H3.7c-1 0-1.8-.8-1.8-1.8v-10c0-1 .8-1.8 1.8-1.8h16.5c1 0 1.8.8 1.8 1.8v10.1zM6.7 6.7h10.6V8H6.7V6.7zm0 2.9h10.6v1.3H6.7V9.6zm0 2.8h10.6v1.3H6.7v-1.3z" />
                </svg>
                <p class="ml-2">Comment</p>
            </button>
        </div>

        <div v-if="comments" class="border-t border-gray-400 p-4 pt-2">
            <div class="flex">
                <input v-model="commentBody" type="text" name="comment"
                    class="w-full pl-4 h-8 bg-gray-200 rounded-lg focus:outline-none" placeholder="Write your comment">
                <button v-if="commentBody"
                    @click="$store.dispatch('commentPost', { body: commentBody, postId: post.data.post_id, postKey: $vnode.key }); commentBody = ''"
                    class="bg-gray-200 ml-2 px-2 py-1 rounded-lg focus:outline-none">
                    Post
                </button>
            </div>
        </div>

        <div class="flex my-4 items-center" v-for="comment in post.data.attributes.comments.data"
            :key="comment.data.comment_id">
            <div class="w-8">
                <img src="https://img.freepik.com/free-photo/young-bearded-man-with-striped-shirt_273609-5677.jpg?size=626&ext=jpg&ga=GA1.1.1224184972.1715040000&semt=sph"
                    alt="User Profile Image" class="w-8 h-8 object-cover rounded-full">
            </div>
            <div class="ml-4 flex-1">
                <div class="bg-gray-200 rounded-lg p-2 text-sm">
                    <a :href="'/users/' + comment.data.attributes.commented_by.data.user_id"
                        class="font-bold text-blue-700">
                        {{ comment.data.attributes.commented_by.data.attributes.name }}:
                    </a>
                    <p class="inline">
                        {{ comment.data.attributes.body }}
                    </p>
                </div>
                <div class="text-xs pl-2">
                    <p>
                        {{ comment.data.attributes.commented_at }}
                    </p>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: "Post",

    props: [
        "post"
    ],

    data: () => {
        return {
            comments: false,
            commentBody: ''
        }
    }

}
</script>

<style scoped></style>
