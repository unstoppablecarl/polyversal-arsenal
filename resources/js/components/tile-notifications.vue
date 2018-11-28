<template>


        <notifications
            position="center top"
            :width="600"
            classes="app-notification"
        >
            <template slot="body" slot-scope="props">
                <div
                    :class="[props.class, props.item.type, 'app-notification']"
                    @click="props.close"
                >
                    <a class="close" @click="props.close">
                        &times;
                    </a>
                    <div
                        v-if="props.item.title"
                        class="notification-title"
                        v-html="props.item.title"
                    >
                    </div>
                    <div
                        class="notification-content"
                        v-html="props.item.text"
                    >

                    </div>
                </div>
            </template>
        </notifications>

</template>

<script>

    export default {
        name: 'tile-notifications',
    };

</script>
