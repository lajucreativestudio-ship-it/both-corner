// Both Corner - Web Platform Interactivity Logic (LumaBooth & fotoshare Cloud Style)

// ==========================================================================
// 1. LANDING PAGE INTERACTIONS (index.html)
// ==========================================================================

// Shrinking Header on Scroll
const header = document.getElementById('main-header');
if (header) {
  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  });
}

// Scroll Reveal Animations (Intersection Observer)
const revealElements = document.querySelectorAll('.reveal');
if (revealElements.length > 0) {
  const revealObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('active');
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.15,
    rootMargin: '0px 0px -50px 0px'
  });

  revealElements.forEach(el => revealObserver.observe(el));
}


// ==========================================================================
// 2. DASHBOARD INTERACTIONS (dashboard.html)
// ==========================================================================

// Mock Events matching fotoshare Cloud screenshot
let mockEvents = [
  { id: 1, title: 'Pernikahan Murdia & Muh Ali', date: 'January 8, 2026', img: 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=600&auto=format&fit=crop' },
  { id: 2, title: 'PCA Warehouse D12.12', date: 'December 12, 2025', img: 'https://images.unsplash.com/photo-1549417229-aa67d3263c09?q=80&w=600&auto=format&fit=crop' },
  { id: 3, title: 'PCA Head Office D12.12', date: 'December 11, 2025', img: 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?q=80&w=600&auto=format&fit=crop' },
  { id: 4, title: 'Fulfillment Country Workshop', date: 'April 25, 2025', img: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=600&auto=format&fit=crop' },
  { id: 5, title: 'Kate & Jack\'s Wedding', date: 'April 20, 2025', img: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=600&auto=format&fit=crop' },
  { id: 6, title: 'Wedding Baim & Fany 13 April 2025', date: 'April 12, 2025', img: 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=600&auto=format&fit=crop' }
];

// Switch Active Panel in Sidebar
function switchPanel(panelId) {
  // Hide all panels
  const panels = document.querySelectorAll('.db-panel');
  panels.forEach(p => p.classList.remove('active'));

  // Show selected panel
  const targetPanel = document.getElementById(`panel-${panelId}`);
  if (targetPanel) targetPanel.classList.add('active');

  // Update active status on sidebar items
  const menuItems = document.querySelectorAll('.sidebar-menu li');
  menuItems.forEach(item => item.classList.remove('active'));

  const activeMenuItem = document.getElementById(`menu-${panelId}`);
  if (activeMenuItem) activeMenuItem.classList.add('active');

  // Load events list on events tab
  if (panelId === 'events') {
    renderEvents(mockEvents);
  }
}

// Render Events Grid
function renderEvents(events) {
  const container = document.getElementById('events-grid-container');
  if (!container) return;

  container.innerHTML = '';

  if (events.length === 0) {
    container.innerHTML = `
      <div style="grid-column: 1/-1; text-align: center; padding: 4rem 2rem; color: var(--text-muted);">
        <p style="font-size: 2rem; margin-bottom: 0.5rem;">📅</p>
        <p>Tidak ada event yang cocok dengan pencarian Anda.</p>
      </div>
    `;
    return;
  }

  events.forEach(ev => {
    const card = document.createElement('div');
    card.className = 'event-card';
    card.onclick = () => showToast(`📂 Membuka konfigurasi remote untuk event: "${ev.title}"`);
    card.innerHTML = `
      <img src="${ev.img}" class="event-card-img" alt="${ev.title}" loading="lazy">
      <div class="event-card-title">${ev.title}</div>
      <div class="event-card-date">${ev.date}</div>
    `;
    container.appendChild(card);
  });
}

// Filter Events by Search input
function filterEvents() {
  const input = document.getElementById('event-search-input');
  if (!input) return;

  const query = input.value.toLowerCase();
  const filtered = mockEvents.filter(ev => ev.title.toLowerCase().includes(query));

  renderEvents(filtered);
}

// Save Settings Form
function saveSettings() {
  const businessName = document.getElementById('settings-business-name').value;
  showToast(`💾 Pengaturan "${businessName}" berhasil disimpan ke cloud database!`);
}

// Copy Referral link
function copyRefLink() {
  const refInput = document.getElementById('ref-link-input');
  if (refInput) {
    refInput.select();
    refInput.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(refInput.value).then(() => {
      showToast('📋 Link referral disalin ke clipboard!');
    }).catch(err => {
      showToast('✖ Gagal menyalin link', false);
    });
  }
}

// Toast notification helper
function showToast(message, isSuccess = true) {
  const toast = document.getElementById('toast-notif');
  const toastMsg = document.getElementById('toast-message');
  const toastIcon = document.getElementById('toast-icon');

  if (toast) {
    toastMsg.innerText = message;
    toastIcon.innerText = isSuccess ? '✓' : '✖';
    toastIcon.style.color = isSuccess ? 'var(--success)' : 'var(--danger)';
    
    toast.classList.add('active');

    setTimeout(() => {
      toast.classList.remove('active');
    }, 3500);
  }
}

// Page Load initialization
document.addEventListener('DOMContentLoaded', () => {
  renderEvents(mockEvents);
});
