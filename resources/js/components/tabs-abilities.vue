<template>
    <div>
        <div class="form-group row header-row">
            <label class="col-sm-2 col-form-label">Ability</label>
            <div class="col-sm-4">

            </div>
            <div class="col-sm-1 col-form-label number-cell">
                Cost
            </div>
            <div class="col-sm col-form-label">
                Notes
            </div>

        </div>

        <div class="tab-content-divider"></div>

        <tile-ability-select
            title="Stealth / Active Camo"
            v-model="tile_stealth"
            :options="stealthOptions"
            :is-defensive="true"
            :show-move="false"
            :show-evasion="false"
        />

        <tile-ability-select
            title="Anti-Missile Autoguns"
            v-model="tile_ama_id"
            :options="amaOptions"
            :is-defensive="true"
            :show-move="false"
            :show-evasion="false"
            :disabled="!hasAMAOption"
        />

        <tile-ability-list/>
    </div>

</template>

<script>
    import Number from './functional/number';
    import {amaOptions} from '../data/options';
    import {mapTileGetters, mapTileProperties} from '../data/mappers';
    import TileAbilitySelect from './tabs-abilities/tile-ability-select';
    import TileAbilityList from './tabs-abilities/tile-ability-list';

    export default {
        name: 'tabs-abilities',
        components: {
            TileAbilityList,
            TileAbilitySelect,
            Number,
        },
        data() {
            return {
                amaOptions: amaOptions,
            }
        },
        computed: {
            ...mapTileProperties({
                tile_type_id: 'tile_type_id',
                tile_stealth: 'stealth',
                tile_ama_id: 'anti_missile_system_id',
            }),
            ...mapTileGetters([
                'hasAMAOption',
                'stealthOptions',
            ]),
        },
    }

</script>
