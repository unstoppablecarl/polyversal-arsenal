<template>
    <div class="row no-gutters">
        <div class="flex-sm-fill">
            <ul class="pagination">
                <li class="page-item disabled">
                    <span class="page-link" v-show="!loading">
                        Viewing Tiles
                        <strong>{{fromRecord}}</strong>
                        -
                        <strong>{{toRecord}}</strong>
                        of
                        <strong>{{totalRecords}}</strong>
                    </span>
                </li>
            </ul>
        </div>

        <div class="px-2 flex-sm-fill">
            <h4 class="text-center" v-if="loading">
                Loading Tiles
                <i class="fas fa-spin fa-cog"></i>
            </h4>

            <ul class="pagination justify-content-center" v-else>
                <li :class="['page-item', {disabled: hasFirstPage}]">
                    <a class="page-link" href="#" @click="clickFirst">
                        <span class="fa fa-fw fa-angle-double-left"></span>
                    </a>
                </li>
                <template v-if="!hasFirstPage">
                    <li class="page-item disabled">
                        <a class="page-link">
                            ...
                        </a>
                    </li>
                </template>
                <li
                    v-for="pageNumber in pageNumbers"
                    :key="pageNumber"
                    :class="['page-item', {'active': pageNumber === currentPage}]"
                >
                    <a class="page-link" href="#" @click="changePage(pageNumber)">
                        {{pageNumber}}
                    </a>
                </li>
                <template v-if="!hasLastPage">
                    <li class="page-item disabled">
                        <a class="page-link">
                            ...
                        </a>
                    </li>
                </template>
                <li :class="['page-item', {disabled: hasLastPage}]">
                    <a class="page-link" href="#" @click="clickLast">
                        <span class="fa fa-fw fa-angle-double-right"></span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="flex-sm-fill">
            <div class="form-inline float-right mb-3">
                <label class="mr-2" for="tiles-per-page">Per Page</label>

                <select id="tiles-per-page"
                        class="custom-select mr-sm-2"
                        v-model="selectedPerPage"
                        @change="changePerPage"
                >
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>

                <div class="input-group mr-sm-2">
                    <input type="text"
                           class="form-control"
                           placeholder="search tile name"
                           v-model="inputSearch"
                           v-on:keyup.enter="changeSearch"
                    >
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" @click="changeSearch">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'tile-grid-pagination',
        props: {
            loading: null,
            fromRecord: Number,
            toRecord: Number,
            totalRecords: Number,
            perPage: Number,
            currentPage: Number,
            lastPage: Number,
            search: String,
        },
        data() {
            return {
                selectedPerPage: this.perPage,
                inputSearch: this.search,
            };
        },
        computed: {
            totalPages() {
                return Math.ceil(this.totalRecords / this.perPage);
            },
            hasFirstPage() {
                return this.pageNumbers.indexOf(1) !== -1;
            },
            hasLastPage() {
                return this.pageNumbers.indexOf(this.lastPage) !== -1;
            },
            pageNumbers() {
                let {startPage, endPage} = getPages({
                    currentPage: this.currentPage,
                    lastPage: this.lastPage
                });

                let pageNumbers = [];

                for (let i = startPage; i <= endPage; i++) {
                    pageNumbers.push(i)
                }

                return pageNumbers
            },
        },
        methods: {
            clickFirst() {
                this.changePage(1);
            },
            clickLast() {
                this.changePage(this.lastPage);
            },
            changePage(page) {
                this.$emit('changePage', page);
            },
            changePerPage() {
                this.$emit('changePerPage', this.selectedPerPage);
            },
            changeSearch() {
                this.$emit('changeSearch', this.inputSearch);
            }
        }
    };

    function getPages({currentPage, lastPage}) {
        const linkLimit = 13;
        let linkCount   = linkLimit;
        let startPage   = 1;

        if (lastPage <= linkLimit) {
            linkCount = lastPage
        } else if (lastPage - currentPage + 2 < linkLimit) {
            startPage = lastPage - linkCount + 1
        } else {
            startPage = currentPage - Math.floor(linkCount / 2)
        }

        // Sanity checks
        if (startPage < 1) {
            startPage = 1
        } else if (startPage > lastPage - linkCount) {
            startPage = lastPage - linkCount + 1
        }

        let endPage = Math.min(startPage + linkCount - 1, lastPage);

        return {startPage, endPage}
    }
</script>
<style lang="scss">
    .no-gutters {
        margin-right: 0;
        margin-left: 0;

        > .col,
        > [class*="col-"] {
            padding-right: 0;
            padding-left: 0;
        }
    }
</style>
