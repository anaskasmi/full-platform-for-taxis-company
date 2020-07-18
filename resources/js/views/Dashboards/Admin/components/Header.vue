<template>
    <div class="wrapper_header mt-10">
        <Title class="title_header text-uppercase" ></Title>
        <Button class="button_header" ></Button>
    </div>
</template>


<script>

//imports
import Title from '@/js/views/Dashboards/Admin/components/header/Title.vue';
import Button from '@/js/views/Dashboards/Admin/components/header/Button.vue';

export default {
    components:{
        Title,
        Button
    }
}
</script>



<style scoped>
    .wrapper_header{
        display: grid;
        grid-template-columns: 10fr 2fr;
        grid-auto-rows: minmax(50px,auto);
        align-items: center;
        /* background-color: rgb(255, 153, 0); */

    }
    .title_header{
        justify-self: start;
        font-family: 'roboto';
        font-size: 3em;
        color:rgb(124, 124, 124);

    }
    .button_header{
        justify-self: end;
    }
    a {
        text-decoration: none;
    }
</style>