<template>
    <div>
    <Drawer></Drawer>
    Driver dashboard   
    </div>
</template>


<script>
import Drawer from '@/js/components/drawers/Driver.vue';

export default {
    created() {
        this.$store.commit('showNavbarDriver_on');
    },
    beforeDestroy()
    {
        this.$store.commit('showNavbarDriver_off');
    },
    components:{
        Drawer,
    }
}
</script>