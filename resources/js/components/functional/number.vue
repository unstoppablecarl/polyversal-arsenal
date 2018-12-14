<script>
    export default {
        name: 'number',
        functional: true,
        props: {
            val: Number,
            invert: Boolean,
        },
        render(createElement, {props}) {
            let val = props.val;

            let html = displayNumber(val);

            if (props.invert) {
                val = val * -1;
            }

            let attrClass = numberType(val);

            return createElement('span', {
                attrs: {
                    class: attrClass,
                },
                domProps: {
                    innerHTML: html,
                },
            });
        },
    };

    function displayNumber(val) {
        if (val > 0) {
            val = '+' + val;
        }
        return val;
    }

    function numberType(val) {
        let type;
        if (val === 0) {
            type = 'zero';
        } else if (val > 0) {
            type = 'positive';
        } else {
            type = 'negative';
        }
        return 'number-' + type;
    }
</script>
