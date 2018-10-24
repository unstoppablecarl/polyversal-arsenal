<template>
    <div class="container">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">AA</label>
            <div class="col-sm-10">
                <select class="form-control" v-model="aa">
                    <option v-for="(label, key) in weaponOptions" v-bind:value="key">
                        {{ label }}
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">AT</label>
            <div class="col-sm-10">
                <select class="form-control" v-model="at">
                    <option v-for="(label, key) in weaponOptions" v-bind:value="key">
                        {{ label }}
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">AP</label>
            <div class="col-sm-10">
                <select class="form-control" v-model="ap">
                    <option v-for="(label, key) in weaponOptions" v-bind:value="key">
                        {{ label }}
                    </option>
                </select>
            </div>
        </div>

    </div>
</template>

<script>
    import {
        ARC_DIRECTIONS,
        ARC_SIZES,
        ATK_VALUES,
        DMG_VALUES,
        ARC_90,
        ARC_UP,
        DMG_ML,
        NONE,
    } from '../data/constants';

    export default {
        name: 'weapon',
        props: {
            name: String,
            range: Number,
            quantity: Number,
            arcSize: {
                default: ARC_90,
                validator: validatorContains(ARC_SIZES),
            },
            arcDirection: {
                default: ARC_UP,
                validator: validatorContains(ARC_DIRECTIONS),
            },
            aa: {
                default: NONE,
                validator: validatorContains(ATK_VALUES),
            },
            at: {
                default: NONE,
                validator: validatorContains(ATK_VALUES),
            },
            ap: {
                default: NONE,
                validator: validatorContains(ATK_VALUES),
            },
            damage: {
                default: DMG_ML,
                validator: validatorContains(DMG_VALUES),
            },
        },
        data() {
            return {
                weaponOptions: ATK_VALUES,
                data: {},
            };
        },

        mounted() {
            console.log('Component mounted.');
        },
    };

    function validatorContains(arr) {
        let values = Object.keys(arr);
        return function (value) {
            return arrayContains(values, value);
        };
    }

    function arrayContains(arr, value) {
        return arr.indexOf(value) !== -1;
    }
</script>
