<template>
    <div>
     dispatcher dashboard   
    </div>
</template>

<script>
export default {
    created() {
        this.$store.commit('showNavbarDispatcher_on');
    },
    beforeDestroy()
    {
        this.$store.commit('showNavbarDispatcher_off');
    }
}
</script>