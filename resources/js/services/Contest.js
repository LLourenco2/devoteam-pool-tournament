import axios from "axios";

const url = "contests";

export default {
    index: async function (params) {
        return await axios.get(url, {
            params,
        });
    },

    show: async function (id) {
        return await axios.get(`${url}/${id}`);
    },
};
