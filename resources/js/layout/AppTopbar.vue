<script setup>
import { useLayout } from '@/layout/composables/layout';
import AppConfigurator from './AppConfigurator.vue';

const { toggleDarkMode, isDarkTheme } = useLayout();
</script>

<template>
    <div class="layout-topbar">
        <div class="layout-topbar-logo-container">
            <router-link to="/" class="layout-topbar-logo">
                <svg viewBox="0 0 78.436 78.436" xmlns="http://www.w3.org/2000/svg" fill="#000000">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <g id="billiard_ball" data-name="billiard ball" transform="translate(-1468.01 -619.623)">
                            <path id="Path_87" data-name="Path 87"
                                d="M1546.44,658.846a39.215,39.215,0,1,1-39.212-39.223A39.211,39.211,0,0,1,1546.44,658.846Z"
                                fill="var(--primary-color)"></path>
                            <path id="Path_88" data-name="Path 88"
                                d="M1533.961,658.841a26.734,26.734,0,1,1-26.733-26.731A26.736,26.736,0,0,1,1533.961,658.841Z"
                                fill="#f4f4f4"></path>
                            <g id="Group_30" data-name="Group 30">
                                <g id="Group_29" data-name="Group 29">
                                    <path id="Path_89" data-name="Path 89"
                                        d="M1511.472,657.567a7.82,7.82,0,0,1,3.588,2.7,6.347,6.347,0,0,1,1.315,3.764,8.339,8.339,0,0,1-.619,3.356,6.626,6.626,0,0,1-1.777,2.411,11.811,11.811,0,0,1-13.509,0,6.957,6.957,0,0,1-2.391-5.518,6.7,6.7,0,0,1,1.31-4.013,7.862,7.862,0,0,1,3.594-2.7,6.526,6.526,0,0,1-2.589-1.772,5.3,5.3,0,0,1-1.315-3.593,5.859,5.859,0,0,1,2.086-4.671,11.228,11.228,0,0,1,12.118,0,5.841,5.841,0,0,1,2.087,4.671,5.19,5.19,0,0,1-2.584,4.67A6.389,6.389,0,0,1,1511.472,657.567Zm-4.244,11.58a7.259,7.259,0,0,0,4.573-1.31,4.319,4.319,0,0,0,1.676-3.594,4.782,4.782,0,0,0-1.853-3.9,7.849,7.849,0,0,0-8.8,0,4.733,4.733,0,0,0-1.852,3.84,4.38,4.38,0,0,0,1.674,3.65A7.272,7.272,0,0,0,1507.228,669.147Zm0-12.811a6.446,6.446,0,0,0,3.512-.929,3.428,3.428,0,0,0,1.732-3.013,3.252,3.252,0,0,0-1.463-2.876,7.774,7.774,0,0,0-7.564,0,3.5,3.5,0,0,0,.132,5.793A6.634,6.634,0,0,0,1507.228,656.336Z"
                                        fill="var(--primary-color)"></path>
                                </g>
                            </g>
                            <path id="Path_90" data-name="Path 90"
                                d="M1512.6,632.651a12,12,0,0,0-1.773,6.228,12.113,12.113,0,0,0,20.6,8.662A26.757,26.757,0,0,0,1512.6,632.651Z"
                                fill="#ffffff"></path>
                            <path id="Path_91" data-name="Path 91"
                                d="M1522.96,626.756a12.082,12.082,0,0,0-10.356,5.9,26.757,26.757,0,0,1,18.824,14.89,12.114,12.114,0,0,0-8.468-20.785Z"
                                fill="var(--primary-color)"></path>
                        </g>
                    </g>
                </svg>
                <span>Pool Tournament</span>
            </router-link>
        </div>

        <div class="layout-topbar-actions">
            <div class="layout-config-menu">
                <button type="button" class="layout-topbar-action" @click="toggleDarkMode">
                    <i :class="['pi', { 'pi-moon': isDarkTheme, 'pi-sun': !isDarkTheme }]"></i>
                </button>
                <div class="relative">
                    <button
                        v-styleclass="{ selector: '@next', enterFromClass: 'hidden', enterActiveClass: 'animate-scalein', leaveToClass: 'hidden', leaveActiveClass: 'animate-fadeout', hideOnOutsideClick: true }"
                        type="button" class="layout-topbar-action layout-topbar-action-highlight">
                        <i class="pi pi-palette"></i>
                    </button>
                    <AppConfigurator />
                </div>
            </div>
        </div>
    </div>
</template>
