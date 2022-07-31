<template>
  <div class="hero__container">
    <div class="circles_bg">
      <svg viewBox="0 0 1920 1920">
        <g transform="translate(945.7095158176297 885.3586503909253)">
          <path
            d="M293.2 -357.6C445.4 -293.1 679.2 -294.9 714 -223.5C748.8 -152.2 584.6 -7.8 489.5 120.2C394.5 248.3 368.6 360 298.5 485.2C228.5 610.4 114.2 749.2 17.2 725.5C-79.7 701.8 -159.5 515.5 -229.7 390.3C-299.9 265.1 -360.6 201 -462.5 100.1C-564.3 -0.8 -707.3 -138.5 -688.5 -232.1C-669.7 -325.8 -489.1 -375.2 -347.4 -443.2C-205.7 -511.2 -102.9 -597.6 -16.2 -575.3C70.5 -553.1 141.1 -422.2 293.2 -357.6"
            fill="#0088ff"
            id="circle1"
          ></path>
        </g>
        <g transform="translate(1015.3501532309103 897.1259976822465)" style="display: none !important;">
            <path d="M399.7 -481.2C522.4 -461 629.3 -350.1 617.9 -236C606.5 -121.9 476.8 -4.5 448.2 164.8C419.5 334.1 491.9 555.4 434 600.9C376.2 646.4 188.1 516.2 19.1 489.9C-149.9 463.6 -299.8 541.3 -382.8 503.9C-465.9 466.6 -482.1 314.2 -557.4 162.3C-632.8 10.3 -767.2 -141.3 -719.4 -216.9C-671.6 -292.4 -441.6 -291.8 -291.3 -303C-141.1 -314.2 -70.5 -337.1 34 -383.9C138.5 -430.7 277 -501.3 399.7 -481.2" 
            fill="#0088ff"
            id="circle2"></path>
        </g>
      </svg>
    </div>
    <div class="hero__front__layer">
      <div class="hero__container">
        <div data-aos="zoom-out-right" data-aos-delay="200" data-aos-duration="1000" class="hero__tiles__left hero__tiles">

        </div>
        <div data-aos="fade-in" data-aos-delay="700" data-aos-duration="1500" class="hero__text">
          <h1>
            No matter<br/>where you are<br/>We <span>remind you</span><br/>your tasks!
          </h1>
        </div>
        <div data-aos="zoom-out-up" data-aos-delay="300" data-aos-duration="1000" class="hero__tiles__bottom hero__tiles">

        </div>
        <div data-aos="zoom-out-left" data-aos-delay="400" data-aos-duration="1000" class="hero__tiles__right hero__tiles">

        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
    import { onMounted } from "@vue/runtime-core";
    import KUTE from "kute.js";
    import 'aos/dist/aos.css';
    import AOS from "aos";

    defineProps({
    auth: Boolean,
    });

    const tween = () => {
        KUTE.allFromTo(
            '#circle1',
            {path: '#circle1'},
            {path: '#circle2'},
            {repeat: 2000, duration: 2000, yoyo: true}
        ).start()
    }

    onMounted(() => {
        tween()
        AOS.init()
    });
</script>
<style scoped>
    @import "/css/HomeHero.css";
</style>