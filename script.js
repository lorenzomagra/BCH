// Basket Club Hoenheim — interactions légères (menu mobile)
document.addEventListener("DOMContentLoaded", function () {
  var toggle = document.querySelector(".nav-toggle");
  var nav = document.querySelector(".main-nav");

  if (toggle && nav) {
    toggle.addEventListener("click", function () {
      var isOpen = nav.classList.toggle("open");
      toggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
    });
  }

  // Sur mobile, un clic sur "Le club" ouvre/ferme le sous-menu au lieu de suivre le lien
  var hasSub = document.querySelectorAll(".has-sub > a");
  hasSub.forEach(function (link) {
    link.addEventListener("click", function (e) {
      if (window.innerWidth <= 760) {
        e.preventDefault();
        link.parentElement.classList.toggle("open");
      }
    });
  });

  // Page "Le club" : un seul panneau (Histoire / Salle / Comité / Arbitrage)
  // visible à la fois, piloté par les boutons du jump-nav.
  var tabPanels = document.querySelectorAll(".tab-panel");
  var tabLinks = document.querySelectorAll(".jump-nav a[data-tab]");

  if (tabPanels.length && tabLinks.length) {
    var showTab = function (id) {
      tabPanels.forEach(function (panel) {
        panel.classList.toggle("active", panel.id === id);
      });
      tabLinks.forEach(function (link) {
        link.classList.toggle("active", link.getAttribute("data-tab") === id);
      });
    };

    tabLinks.forEach(function (link) {
      link.addEventListener("click", function (e) {
        var id = link.getAttribute("data-tab");
        e.preventDefault();
        showTab(id);
        history.replaceState(null, "", "#" + id);
      });
    });

    var startId = (window.location.hash || "").replace("#", "");
    if (!startId || !document.getElementById(startId)) {
      startId = "histoire";
    }
    showTab(startId);
  }
});
