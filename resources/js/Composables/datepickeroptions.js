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
            {
                label: "Календарная неделя",
                atClick: () => {
                    const date = new Date();
                    const today = new Date();
                    return [new Date(date.setDate(date.getDate() - date.getDay() + 1)), today];
                },
                },
                {
                label: "Предыдущая календарная неделя",
                atClick: () => {
                    const today = new Date();
                    const startDate = new Date(new Date().setDate(today.getDate() - today.getDay() - 6));
                    const endDate = new Date(new Date().setDate(today.getDate() - today.getDay()));
                    return [startDate, endDate];
                },
                },
            {
                label: "Месяц",
                atClick: () => {
                    const date = new Date();
                    return [new Date(date.getFullYear(), date.getMonth() - 1, date.getDate()), date];
                },
                },
                {
                label: "Предыдущий месяц",
                atClick: () => {
                    const today = new Date();
                    const date = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
                    return [new Date(date.getFullYear(), date.getMonth() -1 , date.getDate()), date];
                },
                },
                {
                    label: "Календарный месяц",
                    atClick: () => {
                        const toDay = new Date();
                        const firstDay = new Date(toDay.getFullYear(), toDay.getMonth(), 1)
                        return [firstDay, toDay];
                    },
                    },
                    {
                    label: "Предыдущий календарный месяц",
                    atClick: () => {
                        const toDay = new Date();
                        const startDate = new Date(toDay.getFullYear(), toDay.getMonth() - 1, 1);
                        const endDate = new Date(toDay.getFullYear(), toDay.getMonth(), 0);
                        return [startDate, endDate];
                    },
                    },
        ]
    };
    return { options };
}