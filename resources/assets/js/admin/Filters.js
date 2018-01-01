import Moment from 'moment';

export default {
    date(value) {
        const date = Moment(value, 'YYYY-MM-DD');

        if (!date.isValid()) {
            return value;
        }

        return date.format('LL');
    },

    datetime(value) {
        const datetime = Moment(value, 'YYYY-MM-DD HH:mm:ss');

        if (!datetime.isValid()) {
            return value;
        }

        return datetime.format('LLL');
    },

    fromNow(value) {
        const datetime = Moment(value, 'YYYY-MM-DD HH:mm:ss');

        if (!datetime.isValid()) {
            return value;
        }

        return datetime.fromNow();
    }
};
