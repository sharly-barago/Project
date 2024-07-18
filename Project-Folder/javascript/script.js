var sideBarisOpen = true;
var toggleBtn = document.getElementById('toggleBtn');
var logoWhite = document.getElementById('logoWhite');
var dashboard_sidebar = document.getElementById('dashboard_sidebar');
var dashboard_content_container = document.getElementById('dashboard_content_container');
var logoImage = document.getElementById('logoImage');

function toggleSidebar(event) {
    event.preventDefault();

    dashboard_sidebar.classList.toggle('closed');
    sideBarisOpen = !sideBarisOpen;

    if (!sideBarisOpen) {
        dashboard_sidebar.style.width = '8%';
        logoImage.style.padding = '20px 10px'; // Reduce padding when sidebar is closed

        var menuText = document.getElementsByClassName('menuText');
        for (var i = 0; i < menuText.length; i++) {
            menuText[i].style.display = 'none';
        }

        document.getElementsByClassName('dashboard_menu_lists')[0].style.textAlign = 'center';
    } else {
        dashboard_sidebar.style.width = '20%';
        logoImage.style.padding = '38px 40px 40px 28px'; // Restore original padding

        var menuText = document.getElementsByClassName('menuText');
        for (var i = 0; i < menuText.length; i++) {
            menuText[i].style.display = 'inline-block';
        }

        document.getElementsByClassName('dashboard_menu_lists')[0].style.textAlign = 'left';
    }

    dashboard_content_container.style.width = '100%';
    logoImage.style.width = '100%';
}

toggleBtn.addEventListener('click', toggleSidebar);
logoWhite.addEventListener('click', toggleSidebar);

document.addEventListener('DOMContentLoaded', function () {
    function handleDropdowns() {
        var dropdowns = document.querySelectorAll('.dashboard_sidebar .nav-item.dropdown');

        dropdowns.forEach(function (dropdown) {
            dropdown.addEventListener('mouseenter', function () {
                if (dashboard_sidebar.classList.contains('closed')) {
                    this.querySelector('.dropdown-menu').classList.add('show');
                }
            });
            dropdown.addEventListener('mouseleave', function () {
                this.querySelector('.dropdown-menu').classList.remove('show');
            });
        });
    }

    handleDropdowns();
});