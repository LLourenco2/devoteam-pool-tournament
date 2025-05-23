import AppLayout from "@/layout/AppLayout.vue";
import { createRouter, createWebHistory } from "vue-router";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            component: AppLayout,
            children: [
                {
                    path: "/",
                    name: "tournaments",
                    component: () => import("@/views/tournament/Main.vue"),
                },
                {
                    path: "/tournaments/:id",
                    name: "tournaments.show",
                    component: () => import("@/views/tournament/Show.vue"),
                },
                {
                    path: "/contests/:id",
                    name: "contests.show",
                    component: () => import("@/views/contest/Show.vue"),
                },
                {
                    path: "/tournaments/:tournamentId/player/:playerId",
                    name: "tournaments.playerShow",
                    component: () =>
                        import("@/views/tournament/PlayerShow.vue"),
                },
            ],
        },
        {
            path: "/:pathMatch(.*)*",
            name: "NotFound",
            component: () => import("@/views/NotFound.vue"),
        },
    ],
});

export default router;
