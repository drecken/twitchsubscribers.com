<template>
    <b-table
        :data="data"
        :loading="loading"

        paginated
        backend-pagination
        :total="total"
        :per-page="perPage"
        @page-change="onPageChange"
        aria-next-label="Next page"
        aria-previous-label="Previous page"
        aria-page-label="Page"
        aria-current-label="Current page"

        backend-sorting
        :default-sort-direction="defaultSortOrder"
        :default-sort="[sortField, sortOrder]"
        @sort="onSort">

        <b-table-column field="subscriber.name" label="Subscriber" sortable v-slot="props">
            <div class="columns">
                <div class="column is-narrow">
                    <figure class="image is-32x32">
                        <img
                            :src="props.row.subscriber.avatar + '?' + props.row.subscriber.id"
                            :alt="props.row.subscriber.name"
                            class="is-rounded"
                        >
                    </figure>
                </div>
                <div class="column">
                    <a :href="'https://twitch.tv/' + props.row.subscriber.name">{{ props.row.subscriber.name }}</a>
                </div>
            </div>
        </b-table-column>

        <b-table-column field="tier" label="Tier" sortable v-slot="props">
            {{ props.row.tier }}
        </b-table-column>

        <b-table-column field="gifter.name" label="Gifter" sortable v-slot="props">
            <template v-if="hasGifter(props.row)">

                <div class="columns">
                    <div class="column is-narrow">
                        <figure class="image is-32x32">
                            <img
                                :src="props.row.gifter.avatar + '?' + props.row.gifter.id"
                                :alt="props.row.gifter.name"
                                class="is-rounded"
                            >
                        </figure>
                    </div>
                    <div class="column">
                        <a :href="'https://twitch.tv/' + props.row.gifter.name">{{ props.row.gifter.name }}</a>
                    </div>
                </div>
            </template>
        </b-table-column>

    </b-table>
</template>

<script>
    export default {
        props: {
            twitchSyncedAt: {
                type: String,
                required: true,
            }
        },
        data() {
            return {
                data: [],
                total: 0,
                loading: false,
                sortField: 'subscriber.name',
                sortOrder: 'asc',
                defaultSortOrder: 'asc',
                page: 1,
                perPage: 20
            }
        },
        computed: {
            sort() {
                return this.sortOrder === 'asc' ? this.sortField : '-' + this.sortField
            }
        },
        methods: {
            hasGifter(subscription) {
                return _.has(subscription, 'gifter.name')
            },

            loadAsyncData() {
                const params = [
                    'include=subscriber,gifter',
                    `sort=${this.sort}`,
                    `page[size]=${this.perPage}`,
                    `page[number]=${this.page}`
                ].join('&')

                this.loading = true
                axios.get(`/fetch-subscribers?${params}`)
                    .then(({data}) => {
                        this.total = data.total;
                        this.data = data.data;
                        this.loading = false
                    })
                    .catch((error) => {
                        if (error.response.status === 401) {
                            location.reload();
                        }
                        this.data = [];
                        this.total = 0;
                        this.loading = false;
                        throw error;
                    })
            },

            onPageChange(page) {
                this.page = page;
                this.loadAsyncData();
            },

            onSort(field, order) {
                this.sortField = field;
                this.sortOrder = order;
                this.loadAsyncData();
            },
        },
        watch: {
            twitchSyncedAt: function () {
                this.loadAsyncData()
            },
        },
        mounted() {
            this.loadAsyncData()
        }
    }
</script>
