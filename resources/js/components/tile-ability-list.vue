<template>
    <div>

        <div :class="['form-group row', {'ability-disabled': !item.valid}]" v-for="item in options"
             :key="item.id" v-if="item.valid">
            <label class="col-sm-2 col-form-label">

                {{item.display_name}}

            </label>
            <div class="col-sm-4">
                <template v-if="item.valid">
                    <button class="btn btn-light btn-block" @click="toggle(item)">
                        <template v-if="item.active">
                            Remove
                        </template>
                        <template v-else>
                            Add
                        </template>
                    </button>

                </template>
            </div>
            <div class="col-sm-1 col-form-label number-cell">

                <template v-if="item.cost">
                <span :class="['ability-cost', {'ability-selected': item.active}]">
                        +{{item.cost}}
                    </span>
                </template>
            </div>

            <div class="col-sm col-form-label">
                <span class="text-primary" data-tooltip :title.once="defensiveSystemTooltip" v-if="item.is_defensive">
                    Defensive System
                </span>
                <span class="text-primary" data-tooltip :title.once="jumpJetSystemTooltip"
                      v-if="item.is_jumpjet">
                    Jump System
                </span>
            </div>
        </div>
    </div>
</template>

<script>
    import Number from './number';
    import {mapAbilityGetters, mapTileProperties} from '../data/mappers';
    import {defensiveSystemTooltip, jumpJetSystemTooltip} from '../content/tooltips';

    export default {
        name: 'tile-ability-list',
        components: {Number},
        props: {},
        data() {
            return {
                defensiveSystemTooltip,
                jumpJetSystemTooltip
            };
        },
        computed: {
            ...mapTileProperties({
                tile_type_id: 'tile_type_id',
                tile_class_id: 'class_id',
            }),
            ...mapAbilityGetters([
                'options',
            ]),
        },
        methods: {
            add(abilityId) {
                this.$store.dispatch('abilities/add', abilityId);
            },
            remove(abilityId) {
                this.$store.dispatch('abilities/remove', abilityId);
            },
            toggle(item) {
                if (item.active) {
                    this.remove(item.id);
                } else {
                    this.add(item.id);
                }
            },
        },
    };
</script>
