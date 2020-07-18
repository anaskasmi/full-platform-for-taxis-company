<template>
    <div>
     Director dashboard   
    </div>
</template>

<script>
export default {
    created() {
        this.$store.commit('showNavbarDirector_on');
    },
    beforeDestroy()
    {
        this.$store.commit('showNavbarDirector_off');
    }
}
</script>