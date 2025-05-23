import axios from "axios";

const url = "tournaments";

export default {
    index: async function (params) {
        return await axios.get(url, {
            params,
        });
    },

    store: async function (data) {
        return await axios.post(url, data);
    },

    show: async function (id) {
        return await axios.get(`${url}/${id}`);
    },

    showPlayer: async function (tournamentId, playerId) {
        return await axios.get(`${url}/${tournamentId}/players/${playerId}`);
    },

    leaderboard: async function (id) {
        return await axios.get(`${url}/${id}/leaderboard`);
    },

    simpleSimulation: async function (id) {
        return await axios.post(`${url}/${id}/simple-simulate`);
    },
};
