export const getContestStatusColor = (contest) => {
    switch (contest.status) {
        case "scheduled":
            return "success";

        case "ongoing":
            return "info";

        case "ended":
            return "danger";

        default:
            return null;
    }
};

export const getTournamentStatusColor = (tournament) => {
    switch (tournament.status) {
        case "open":
            return "success";

        case "ongoing":
            return "info";

        case "ended":
            return "danger";

        default:
            return null;
    }
};
