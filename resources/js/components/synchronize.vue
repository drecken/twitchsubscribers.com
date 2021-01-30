<template>
    <div>
        <b-button
            type="is-primary"
            icon-right="twitch"
            :loading="loading"
            @click="sync"
            :disabled="!canSync"
        >
            Synchronize with Twitch
        </b-button>
        <p class="is-size-7 has-text-grey">
            <template v-if="canSync">
                Synchronized {{ lastSync.local().calendar() }}
            </template>
            <template v-else>
                Available {{ nextSync }}
            </template>
        </p>
    </div>
</template>

<script>
    export default {
        props: {
            twitchSyncedAt: {
                type: String,
                required: true,
            },
            cooldown: {
                type: Number,
                required: true,
            }
        },
        data() {
            return {
                loading: false,
                lastSync: null,
                now: null,
                plus5: null,
            }
        },
        created() {
            this.lastSync = moment.utc(this.twitchSyncedAt);
            this.now = moment.utc();
            let vm = this;
            setInterval(function () {
                vm.now = moment.utc()
            }, 1000)
        },
        methods: {
            sync() {
                this.loading = true;
                axios.get(`/synchronize-subscribers`)
                    .then(({data}) => {
                        this.lastSync = moment.utc(data.twitch_synced_at);
                        this.$emit('synchronized');
                        this.loading = false
                    })
                    .catch((error) => {
                        if (error.response.status === 401) {
                            location.reload();
                        }
                        this.loading = false
                    })
            },
        },
        computed: {
            nextSync() {
                return this.lastSync.clone().add(this.cooldown, 'seconds').local().calendar()
            },
            canSync() {
                return this.now.diff(this.lastSync, 'seconds') >= this.cooldown
            },
        },
    }
</script>
