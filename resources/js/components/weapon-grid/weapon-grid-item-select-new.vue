<template>
    <tr class="weapon-grid-item list-item-sortable">
        <td :class="['col-name', {'is-indirect': is_indirect}]">
            <img src="/img/icon-indirect.svg" class="icon-indirect"/>
            {{display_name}}
        </td>
        <td class="col-range">{{range}}</td>
        <td :class="['col-ap', ap + '-bg']">{{ap | format}}</td>
        <td :class="['col-at', at + '-bg']">{{at | format}}</td>
        <td :class="['col-aa', aa + '-bg']">{{aa | format}}</td>
        <td>{{damage | format}}</td>
        <td :class="['col-cost', 'cost-left', ...costCssClasses('d4')]">
            {{cost_d4}}
        </td>
        <td :class="['col-cost', ...costCssClasses('d6')]">
            {{cost_d6}}
        </td>
        <td :class="['col-cost', ...costCssClasses('d8')]">
            {{cost_d8}}
        </td>
        <td :class="['col-cost', ...costCssClasses('d10')]">
            {{cost_d10}}
        </td>
        <td :class="['col-cost', 'cost-right', ...costCssClasses('d12')]">
            {{cost_d12}}
        </td>
        <td class="col-controls">
            <weapon-grid-add-buttons
                :weapon-id="id"
            />
        </td>
    </tr>
</template>

<script>
    import {
        NONE,
    } from '../../data/constants';

    import {mapTileGetters, mapTileWeaponGetters} from '../../data/mappers';
    import WeaponGridAddButtons from "./weapon-grid-add-buttons";

    export default {
        name: 'weapon-grid-item-select-new',
        components: {WeaponGridAddButtons},
        props: {
            id: {},
            range: Number,
            cost_d4: Number,
            cost_d6: Number,
            cost_d8: Number,
            cost_d10: Number,
            cost_d12: Number,
            display_name: String,
            ap: {},
            at: {},
            aa: {},
            damage: String,
            is_indirect: {},
        },
        data() {
            return {};
        },
        computed: {
            ...mapTileGetters([
                'isCurrentTargetingDie'
            ]),
            ...mapTileWeaponGetters({
                tile_weapons: 'tile_weapons',
                getWeapon: 'getWeapon'
            }),

        },
        methods: {
            costCssClasses(die) {
                let cssClass = die.toUpperCase() + '-bg';

                if (this.isCurrentTargetingDie(die)) {
                    cssClass += '-current';
                    return ['cost-current', cssClass]
                }

                return [cssClass];
            },
        },
        filters: {
            format(val) {
                if (val === NONE) {
                    return '-';
                }
                return val;
            },
        },
    };

</script>
