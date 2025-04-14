<template>

    <g>
        <text :x="268.3 * 0.5" y="20" :class="['title', {'text-invert': invert}]">
            <tspan v-for="(row, index) in tileNameArray" :x="268.3 * 0.5" :y="20 + (index) * nameLineHeight"
                   v-html="row"></tspan>
        </text>
        <text :x="268.3 * 0.5" :y="32 + ((tileNameArray.length - 1) * nameLineHeight)"
              :class="['subtitle', {'text-invert': invert}]">{{ subtitle }}
        </text>
    </g>

</template>

<script>

import textWrap from 'svg-text-wrap';

export default {
    name: 'tile-title-svg',
    props: {
        title: null,
        subtitle: null,
        inverted: null,
    },
    data() {
        return {
            invert: false,
            nameLineHeight: 12,
        };
    },
    computed: {
        tileNameArray() {
            console.log(this)
            if (this.title) {

                let result = [];
                this.title.split('\n')
                    .map((str) => {
                        return textWrap(str + '', 140);
                    })
                    .forEach((arr) => {
                        result = result.concat(arr)
                    })

                return result;
            }
            return '';
        },
    },
};

</script>
