<template>
    <div
            v-text="title.text"
    >

    </div>
</template>


<script>
export default {
    computed :{
        title : function()
        {
            return this.$store.state.adminDashboard.title;
        } 
    }
}
</script>

