<template>
    <div>
        <div class="tile-print">

            <tile-front-print-card/>

            <br>

            <tile-back-print-card/>
        </div>
        <br>
    </div>
</template>

<script>

import TileFrontPrintCard from "./tile-print/tile-front-print-card";
import TileBackPrintCard from "./tile-print/tile-back-print-card";

export default {
    name: 'tile-print',
    components: {TileBackPrintCard, TileFrontPrintCard},
};

</script>
