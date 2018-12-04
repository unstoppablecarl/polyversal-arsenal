<template>
    <div>
        <div class="float-left">
            <ul class="pagination">
                <li class="page-item disabled">
                    <span class="page-link">
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

        <div class="float-right">
            <ul class="pagination justify-content-end">
                <li :class="['page-item', {disabled: isFirst}]">
                    <a class="page-link" @click="clickFirst">
                        <span class="fa fa-fw fa-angle-double-left"></span>
                    </a>
                </li>
                <li :class="['page-item', {disabled: isFirst}]">
                    <a class="page-link" @click="clickPrev">
                        <span class="fa fa-fw fa-angle-left"></span>
                    </a>
                </li>
                <li class="page-item disabled">
                        <span class="page-link">
                            Page {{currentPage}} of {{totalPages}}
                        </span>
                </li>
                <li :class="['page-item', {disabled: isLast}]">
                    <a class="page-link" @click="clickNext">
                        <span class="fa fa-fw fa-angle-right"></span>
                    </a>
                </li>
                <li :class="['page-item', {disabled: isLast}]">
                    <a class="page-link" @click="clickLast">
                        <span class="fa fa-fw fa-angle-double-right"></span>
                    </a>
                </li>
            </ul>
        </div>

        <h4 class="text-center" v-show="loading">
            Loading Tiles
            <i class="fas fa-spin fa-cog"></i>
        </h4>
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
        },
        data() {
            return {};
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
            clickPrev() {
                if (!this.isFirst) {
                    this.changePage(this.currentPage - 1);
                }
            },
            clickNext() {
                if (!this.isLast) {
                    this.changePage(this.currentPage + 1);
                }
            },
            clickFirst() {
                if (!this.isFirst) {
                    this.changePage(1);
                }
            },
            clickLast() {
                if (!this.isLast) {
                    this.changePage(this.lastPage);
                }
            },
            changePage(page) {
                this.$emit('change_page', page);
            },
        },
    };

</script>
