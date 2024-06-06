// List of all free Font Awesome icons
const icons = [
    "fa-home", "fa-user", "fa-envelope", "fa-search", "fa-cog", "fa-heart", 
    "fa-star", "fa-camera", "fa-car", "fa-tree", "fa-music", "fa-book",
    "fa-check", "fa-plus", "fa-minus", "fa-asterisk",
    // Add all free Font Awesome icon classes here
];

// Function to load icons into the modal
function loadIcons() {
    const iconsContainer = document.getElementById('iconsContainer');
    iconsContainer.innerHTML = ''; // Clear previous icons if any
    icons.forEach(iconClass => {
        const col = document.createElement('div');
        col.className = 'col-3 text-center mb-4 icon-item';
        col.innerHTML = `<i class="fas ${iconClass} fa-2x" data-icon-class="${iconClass}"></i><p>${iconClass}</p>`;
        iconsContainer.appendChild(col);
    });

    // Add click event listener to each icon
    document.querySelectorAll('.icon-item').forEach(item => {
        item.addEventListener('click', function() {
            const iconClass = this.querySelector('i').getAttribute('data-icon-class');
            document.getElementById('iconClassInput').value = iconClass;
            document.getElementById('iconClassshow').classList.add(iconClass);
            console.log(iconClass);
            $('#iconsModal').modal('hide');
        });
    });
}

// Event listener for button click
document.getElementById('showIconsBtn').addEventListener('click', () => {
    loadIcons();
    $('#iconsModal').modal('show');
});