function PageUrl() {
}
PageUrl.prototype = {
    parseQuery: function (query) {
        const start = query.indexOf('?');
        if (start >= 0) {
            query = query.slice(start + 1);
        }
        const args = {};
        const arr = query.split('&');
        for (let m = 0; m < arr.length; m++) {
            const v = arr[m];
            const tmp = v.split('=');
            args[tmp[0]] = decodeURIComponent(tmp[1]);
        }
        return args;
    },
    parseArgs: function (args) {
        let k, v;
        const arr = [];
        for (k in args) {
            //! js 会把'0'当作false args[k] => args[k] !== ''
            if (args.hasOwnProperty(k) && args[k] !== '') {
                v = encodeURIComponent(args[k]);
                arr.push(k + '=' + v);
            }
        }
        return arr.join('&');
    },
};

const pageUrl = new PageUrl();
export {pageUrl}