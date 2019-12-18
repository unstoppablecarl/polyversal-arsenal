<template>
    <div>
        <tile-grid-pagination
            :loading="loading"
            :per-page="perPage"
            :current-page="currentPage"
            :from-record="fromRecord"
            :to-record="toRecord"
            :total-records="totalRecords"
            :last-page="lastPage"
            @change_page="loadItems"
        ></tile-grid-pagination>

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th v-for="heading in columns" @click="onHeaderClick(heading)">
                    {{heading.label}}
                    <span :class="['fa fa-fw', headerSortIcon(heading)]"></span>
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody>


            <tr v-for="row in rows">
                <td v-for="column in columns" :class="{'text-right': column.type == 'number'}">{{row[column.field]}}
                </td>
                <td v-html="row.buttons"></td>
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
            @changePage="loadItems"
        ></tile-grid-pagination>
    </div>
</template>

<script>

    import axios from 'axios';
    import TileGridPagination from './tile-grid-pagination';

    export default {
        name: 'app-tile-grid',
        components: {TileGridPagination},
        mounted() {
            this.loadItems();
        },
        data() {
            return {
                loading: false,
                sortColumn: 'id',
                sortDirection: 'asc',

                perPage: 3,
                currentPage: 1,

                fromRecord: 0,
                toRecord: 0,
                totalRecords: 10,
                lastPage: 0,
                rows: [],

                columns: [
                    {
                        field: 'id',
                        label: 'ID',
                    },
                    {
                        field: 'name',
                        label: 'Name',
                    },
                    {
                        field: 'tile_type',
                        label: 'Type',
                    },
                    {
                        field: 'tile_class',
                        label: 'Class',
                    },
                    {
                        field: 'tile_mobility',
                        label: 'Mobility',
                    },
                    {
                        field: 'tech_level',
                        label: 'Tech Level',
                    },
                    {
                        field: 'move',
                        label: 'Move',
                        type: 'number',
                    },
                    {
                        field: 'evasion',
                        label: 'Evasion',
                        type: 'number',

                    },
                    {
                        field: 'armor',
                        label: 'Armor',
                        type: 'number',

                    },
                    {
                        field: 'targeting',
                        label: 'Targeting',
                    },
                    {
                        field: 'assault',
                        label: 'Assault',
                    },
                    {
                        field: 'cached_cost',
                        label: 'Cost',
                        type: 'number',
                    },
                ],
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
                this.loadItems();
            },
            loadItems(page = null) {
                page = page || this.currentPage;

                let options  = {
                    params: {
                        sort_field: this.sortColumn,
                        sort_dir: this.sortDirection,
                        page: page,
                    },
                };
                this.loading = true;
                axios.get('/tiles-grid', options)
                    .then((response) => {
                        this.loading = false;
                        let result   = response.data;
                        console.log(result);

                        this.rows         = result.data;
                        this.currentPage  = result.meta.current_page;
                        this.perPage      = result.meta.per_page;
                        this.lastPage     = result.meta.last_page;
                        this.fromRecord   = result.meta.from;
                        this.toRecord     = result.meta.to;
                        this.totalRecords = result.meta.total;
                        this.links        = result.links;
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
