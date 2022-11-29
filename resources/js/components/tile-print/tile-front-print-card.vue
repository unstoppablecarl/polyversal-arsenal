<template>
    <div class="card">
        <div class="card-header">
            <div class="float-right">
                <tile-print-card-save
                    store-namespace="images/front"
                />
            </div>
            <h3 class="card-title">Title Front</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <tile-front-print-card-body/>
                </div>
                <div class="col-sm-6">
                    <tile-front-svg/>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import TilePrintCardSave from './tile-print-card-save';
import TileFrontPrintCardBody from './tile-front-print-card-body';
import TileFrontSvg from './tile-front-svg'

export default {
    name: 'tile-front-print-card',
    components: {
        TileFrontSvg,
        TileFrontPrintCardBody,
        TilePrintCardSave
    },
};

</script>

