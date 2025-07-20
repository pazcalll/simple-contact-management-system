const capitalizeFirstWords = function (text: string): string {
    return text
        .replace(/[\s\-\_\+\*\/%]+/g, ' ')
        .split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
        .join(' ');
};

export { capitalizeFirstWords };