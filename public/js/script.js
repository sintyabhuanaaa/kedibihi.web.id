/* ---------------------------
   Profil / password
----------------------------*/
const profileIcon = document.getElementById("profile-icon");
const profileDropdown = document.getElementById("profile-dropdown");

if (profileIcon) {
    profileIcon.addEventListener("click", (e) => {
        e.stopPropagation();
        if (profileDropdown) {
            profileDropdown.style.display =
                profileDropdown.style.display === "flex" ? "none" : "flex";
        }
    });
    window.addEventListener("click", (e) => {
        if (!e.target.closest(".header-right") && profileDropdown)
            profileDropdown.style.display = "none";
    });
}

// Modal ganti password
function bukaModalPassword() {
    const overlay = document.getElementById("password-overlay");
    if (overlay) overlay.style.display = "flex";
    if (profileDropdown) profileDropdown.style.display = "none";
}
function tutupModalPassword() {
    const overlay = document.getElementById("password-overlay");
    if (overlay) overlay.style.display = "none";
}

// Modal pengaturan akun
function bukaModalProfil() {
    const overlay = document.getElementById("profil-overlay");
    const admin = getAdmin();
    if (overlay) overlay.style.display = "flex";
    if (admin) {
        const u = document.getElementById("profil-username");
        const e = document.getElementById("profil-email");
        if (u) u.value = admin.username || "";
        if (e) e.value = admin.email || "";
    }
    if (profileDropdown) profileDropdown.style.display = "none";
}
function tutupModalProfil() {
    const overlay = document.getElementById("profil-overlay");
    if (overlay) overlay.style.display = "none";
}


// --- Sidebar Toggle (Universal, support multiple buttons) ---
const sidebar = document.getElementById("sidebar");
const sidebarToggles = document.querySelectorAll(".sidebar-toggle");
if (sidebar && sidebarToggles.length > 0) {
    sidebarToggles.forEach(btn => {
        btn.addEventListener("click", function(e) {
            e.stopPropagation();
            sidebar.classList.toggle("closed");
        });
    });
}


// --- Tutup Sidebar Otomatis kalau klik di luar (opsional) ---
document.addEventListener("click", (e) => {
    if (
        sidebar &&
        !sidebar.contains(e.target) &&
        !e.target.classList.contains("sidebar-toggle") &&
        !e.target.closest(".sidebar-toggle")
    ) {
        if (window.innerWidth <= 768) {
            // hanya berlaku di mobile/tablet
            sidebar.classList.add("closed");
        }
    }
});

// --- Biar sidebar default tertutup kalau di mobile ---
window.addEventListener("resize", () => {
    if (window.innerWidth <= 768) {
        sidebar.classList.add("closed");
    } else {
        sidebar.classList.remove("closed");
    }
});

// Jalankan sekali saat load
if (window.innerWidth <= 768) {
    sidebar.classList.add("closed");
}
