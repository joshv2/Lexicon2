(function () {
    function isIOS() {
        const ua = navigator.userAgent || '';
        const iOSDevice = /iPad|iPhone|iPod/.test(ua);
        const iPadOS13Plus = navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1;
        return iOSDevice || iPadOS13Plus;
    }

    if (!isIOS()) return;
    if (typeof window.jQuery === 'undefined') return;

    window.jQuery(function () {
        const $reading = window.jQuery('.readingSentence');
        if ($reading.length === 0) return;
        $reading.empty().append('Recording is not available on iOS at this time.');
    });
})();