export function datepickeroptions() {
        let options = {
        formatter: {
            date: 'DD MMM YYYY',
            month: 'MMM',
        },
        customShortcuts: [
            {
            label: "Неделя",
            atClick: () => {
                const date = new Date();
                return [new Date(date.getFullYear(), date.getMonth(), date.getDate() - 7), date];
            },
            },
            {
            label: "Предыдущая неделя",
            atClick: () => {
                const today = new Date();
                const date = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 7);
                return [new Date(date.getFullYear(), date.getMonth(), date.getDate() - 7), date];
            },
            },
        ]
    };
    return { options };
}