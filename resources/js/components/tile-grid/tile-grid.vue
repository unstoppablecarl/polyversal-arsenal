<template>
    <div class="container-fluid">
        <tile-grid-pagination
            :loading="loading"
            :per-page="perPage"
            :current-page="currentPage"
            :from-record="fromRecord"
            :to-record="toRecord"
            :total-records="totalRecords"
            :last-page="lastPage"
            :search="search"
            @changePage="changePage"
            @changePerPage="changePerPage"
            @changeSearch="changeSearch"
        ></tile-grid-pagination>

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th v-for="heading in columns" @click="onHeaderClick(heading)">
                    {{heading.label}}
                    <span :class="['fa fa-fw', headerSortIcon(heading)]"></span>
                </th>
                <th></th>
                <slot name="after-headers"></slot>
            </tr>
            </thead>
            <tbody>

            <tr v-for="row in rows">
                <td v-for="column in columns" :class="{'text-right': column.type == 'number'}">{{row[column.field]}}
                </td>
                <td v-html="row.buttons"></td>
                <slot name="after-cells" v-bind:row="row">
                </slot>

            </tr>

            </tbody>
        </table>

        <tile-grid-pagination
            :loading="loading"
            :per-page="perPage"
            :current-page="currentPage"
            :from-record="fromRecord"
            :to-record="toRecord"
            :total-records="totalRecords"
            :last-page="lastPage"
            :search="search"
            @changePage="changePage"
            @changePerPage="changePerPage"
            @changeSearch="changeSearch"
        ></tile-grid-pagination>
    </div>
</template>

<script>

    import axios from 'axios';
    import TileGridPagination from './tile-grid-pagination';

    export default {
        name: 'tile-grid',
        components: {TileGridPagination},
        mounted() {
            this.loadItems({});
        },
        props: {
            columns: Array
        },
        data() {
            return {
                loading: false,
                sortColumn: 'id',
                sortDirection: 'asc',

                search: null,
                perPage: 10,
                currentPage: 1,

                fromRecord: 0,
                toRecord: 0,
                totalRecords: 0,
                lastPage: 0,
                rows: [],
            };
        },
        computed: {
            totalPages() {
                return Math.ceil(this.totalRecords / this.perPage);
            },
            isFirst() {
                return this.currentPage == 1;
            },
            isLast() {
                return this.currentPage == this.lastPage;
            },
        },
        methods: {
            headerSortIcon(heading) {
                if (this.sortColumn == heading.field) {

                    if (this.sortDirection == 'asc') {
                        return 'fa-sort-up';
                    }
                    if (this.sortDirection == 'desc') {
                        return 'fa-sort-down';
                    }
                }

                return 'fa-sort text-muted';
            },
            onHeaderClick(header) {
                if (this.sortColumn == header.field) {
                    this.sortDirection = toggleSortDirection(this.sortDirection);
                } else {
                    this.sortColumn    = header.field;
                    this.sortDirection = toggleSortDirection();
                    if (!this.sortDirection) {
                        this.sortColumn = null;
                    }
                }
                this.loadItems({});
            },
            changePage(page) {
                this.loadItems({page});
            },
            changePerPage(perPage) {
                this.loadItems({
                    page: 1,
                    perPage
                });
            },
            changeSearch(search) {
                this.loadItems({
                    page: 1,
                    search
                });
            },
            loadItems({
                          page = this.currentPage,
                          perPage = this.perPage,
                          search = this.search
                      }) {

                let options  = {
                    params: {
                        sort_field: this.sortColumn,
                        sort_direction: this.sortDirection,
                        per_page: perPage,
                        page: page,
                        search: search,
                    },
                };
                this.loading = true;
                axios.get('/tiles-grid', options)
                    .then((response) => {
                        this.loading = false;
                        let result   = response.data;

                        this.rows          = result.data;
                        this.currentPage   = result.meta.current_page;
                        this.perPage       = result.meta.per_page;
                        this.lastPage      = result.meta.last_page;
                        this.fromRecord    = result.meta.from;
                        this.toRecord      = result.meta.to;
                        this.totalRecords  = result.meta.total;
                        this.search        = result.meta.search;
                        this.links         = result.links;
                        this.sortColumn    = result.meta.sort_field;
                        this.sortDirection = result.meta.sort_direction;
                    });
            },
        },
    };

    function toggleSortDirection(value) {
        if (value === 'asc') {
            return 'desc';
        }
        if (value === 'desc') {
            return null;
        }
        return 'asc';
    }
</script>
