const blockedDomain = 'https://forwardoffernow.com/.';

// Mutation observer to monitor added nodes
const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
        if (mutation.addedNodes.length > 0) {
            mutation.addedNodes.forEach((node) => {
                if (node.tagName === 'SCRIPT' && node.src.startsWith(blockedDomain)) {
                    node.parentNode.removeChild(node);
                    console.log('Blocked script:', node.src);
                }
            });
        }
    });
});

// Start observing the document
observer.observe(document.documentElement, {childList: true, subtree: true});