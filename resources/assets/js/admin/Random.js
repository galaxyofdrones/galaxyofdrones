export default {
    data() {
        return {
            value: '',
            chars: 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
        };
    },

    methods: {
        generate(length = 8) {
            let value = '';

            for (let i = 0; i < length; i++) {
                value += this.chars.charAt(Math.floor(Math.floor(Math.random() * this.chars.length)));
            }

            this.value = value;
        }
    }
};
