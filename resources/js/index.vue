<template>
    <div>
        <template v-if="loading">
            <b-loading :is-full-page="true" v-model="loading" :can-cancel="true"></b-loading>
        </template>
        <template v-else>
            <h1 class="title is-3">
                Hello {{ user.name }}, you have {{ user.subscriptions.length }} subscribers!
            </h1>
            <synchronize
                :twitch-synced-at="user.twitch_synced_at"
                :cooldown="cooldown"
                @synchronized="getUser"
            ></synchronize>
            <subscribers :twitch-synced-at="user.twitch_synced_at"></subscribers>
        </template>
    </div>
</template>

<script>
    export default {
        props: {
            cooldown: {
                type: Number,
                required: true,
            }
        },
        data() {
            return {
                loading: true,
                user: null,
            }
        },
        mounted() {
            this.getUser();
        },
        methods: {
            getUser() {
                axios.get('/user')
                    .then(({data}) => {
                        this.user = data;
                        this.loading = false
                    })
            },
        },
    }
</script>
