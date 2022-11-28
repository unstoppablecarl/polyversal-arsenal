<template>
    <div class="card">

        <div class="card-header">
            <div class="float-right">
                <tile-print-card-save
                    store-namespace="images/back"
                />
            </div>
            <h3 class="card-title">Title Back</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <tile-back-print-card-body/>
                </div>
                <div class="col-sm-6">
                    <tile-back-svg/>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import TilePrintCardSave from './tile-print-card-save';
import TileBackPrintCardBody from './tile-back-print-card-body';
import TileBackSvg from './tile-back-svg'

export default {
    name: 'tile-back-print-card',
    components: {
        TileBackSvg,
        TilePrintCardSave,
        TileBackPrintCardBody
    },
};

</script>

