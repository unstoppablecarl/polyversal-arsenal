<template>
    <div>
        <div class="tile-print">

            <tile-front-print-card>
                <template>
                    <tile-front-svg/>
                </template>
            </tile-front-print-card>

            <br>

            <tile-back-print-card>
                <template>
                    <tile-back-svg/>
                </template>
            </tile-back-print-card>
        </div>
        <br>
    </div>
</template>

<script>

    import WeaponGrid from './weapon-grid';
    import TileFrontSvg from './tile-print/tile-front-svg';
    import TileBackSvg from './tile-print/tile-back-svg';
    import TileFrontPrintCard from "./tile-print/tile-front-print-card";
    import TileBackPrintCard from "./tile-print/tile-back-print-card";

    export default {
        name: 'tile-print',
        components: {TileBackPrintCard, TileFrontPrintCard, TileBackSvg, TileFrontSvg, WeaponGrid},
    };

</script>
