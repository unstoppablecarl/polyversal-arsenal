<template>

    <div class="tile">
        <tile-notifications/>

        <div class="row no-print">
            <div class="col-sm-8">
                <h1 class="h4 admin-form-title">
                    <span class="text-muted">Edit</span>
                    <strong>
                        Tile:
                    </strong>
                    {{tile_name}}
                </h1>

            </div>
            <div class="col-sm-4">
                <div class="pull-right text-right">
                    <div class="btn-group" v-if="tile_id">

                        <a :href="viewURL" class="btn btn-light">
                            View
                        </a>
                        <a :href="editURL" class="btn btn-light">
                            Edit
                        </a>
                        <a :href="copyURL" class="btn btn-light">
                            Copy
                        </a>
                        <a :href="deleteURL" class="btn btn-danger">
                            Delete
                        </a>

                    </div>
                </div>
            </div>
        </div>
        <hr>
        <template v-if="notFound">
            <h1>Tile Not Found</h1>

            <router-link :to="{name: 'tile-stats', params: {id: 'create'}}" class="btn btn-primary">
                Create New Tile
            </router-link>
        </template>
        <template v-else-if="fetching">
            <h1 class="text-center">Loading Tile
                <i class="fas fa-spin fa-cog"></i>
            </h1>
        </template>
        <template v-else>

            <div class="no-print">
                <div class="float-right">
                    <btn-save/>
                </div>

                <h4>{{subTitle}}</h4>

                <div class="tabs-container">
                    <tabs/>
                    <div class="tab-content">
                        <router-view/>
                    </div>
                </div>
            </div>
            <tile-print/>
        </template>
    </div>

</template>

<script>

    import {mapGetters} from 'vuex';
    import {mapTileGetters, mapTileProperties} from '../data/mappers';
    import {TILE_TYPE_VEHICLE_ID} from '../data/constants';
    import Tabs from './tabs';
    import TilePrint from './tile-print';
    import TileNotifications from './tile-notifications';
    import FileUpload from './tabs-info/file-upload';
    import BtnSave from './btn-save';

    export default {
        name: 'tile',
        components: {
            BtnSave,
            FileUpload,
            TileNotifications,
            TilePrint,
            Tabs,
        },
        props: {
            name: String,
        },
        data() {
            return {
                notFound: false,
            };
        },
        mounted() {
            this.fetchIfIdChanged();
        },
        computed: {
            ...mapTileGetters([
                'subTitle',
            ]),
            ...mapGetters([
                'fetching',
                'viewURL',
                'editURL',
                'copyURL',
                'deleteURL',
            ]),
            ...mapTileProperties({
                tile_id: 'id',
                tile_type_id: 'tile_type_id',
                tile_class_id: 'tile_class_id',
                tile_armor: 'armor',
                tile_tech_level_id: 'tech_level_id',
                tile_mobility_id: 'mobility_id',
                tile_targeting_id: 'targeting_id',
                tile_assault_id: 'assault_id',
                tile_stealth: 'stealth',
                tile_ama_id: 'anti_missile_system_id',
                tile_name: 'name',
            }),
            hasAMAOption() {
                return this.tile_type_id == TILE_TYPE_VEHICLE_ID;
            },
        },
        watch: {
            // not sure why this is here so commenting for now
            // causes duplicate route navigation
            // tile_id() {
            //     this.setRoute();
            // },
            '$route'(to) {
                this.fetchIfIdChanged();
            },
        },
        methods: {
            fetchIfIdChanged() {
                const routeId = this.$route.params.id;
                if (routeId == 'create') {
                    this.notFound = false;
                    return;
                }
                if (this.tile_id != routeId) {
                    this.$store.dispatch('fetch', routeId)
                        .then((result) => {
                            if (result) {
                                if (result.not_found || result.unauthorized) {
                                    this.notFound = true;
                                }
                            }
                        });
                }
            },
            // not sure why this is here so commenting for now
            // causes duplicate route navigation
            // setRoute() {
            //     this.$router.push({
            //         name: this.$route.name,
            //         params: {id: this.tile_id},
            //     });
            // },
        },
    };

</script>
