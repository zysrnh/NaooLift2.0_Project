---
trigger: always_on
---

# NaooLift — Design System & UI Guidelines

Dokumentasi ini memuat panduan gaya visual (style guide), tipografi, palet warna, dan aturan responsivitas untuk pengembangan antarmuka NaooLift.

## Core Philosophy: "Utilitarian Swiss Brutalism"
Desain NaooLift menganut prinsip fungsionalitas murni. Antarmuka harus terasa seperti jurnal cetak atau manual alat berat: Tegas, Kasar, dan Terbaca Jelas.
*   **NO AI Slop:** Dilarang menggunakan soft drop-shadows, efek glassmorphism (blur), atau gradien warna.
*   **NO Curves:** Dilarang menggunakan sudut melengkung. Semua elemen harus bersiku tajam 90 derajat (`border-radius: 0`).
*   **Data First:** Ruang kosong (whitespace) dan garis pembatas tebal digunakan untuk memisahkan informasi, bukan sekadar dekorasi.

---

## Color Palette

Kami menggunakan konfigurasi warna khusus di Tailwind (`tailwind.config.js`). Jangan gunakan warna bawaan Tailwind (seperti `blue-500` atau `gray-100`) selain palet di bawah ini:

| Warna | Hex Code | Class Tailwind | Penggunaan Utama |
| :--- | :--- | :--- | :--- |
| **Canvas** | `#EAE6E0` | `bg-canvas`, `text-canvas` | Warna latar belakang utama aplikasi (kertas jurnal). |
| **Charcoal** | `#1C1C1C` | `bg-charcoal`, `text-charcoal`| Warna teks utama, garis grid pembatas, dan blok gelap. |
| **Ember** | `#9A4A2E` | `bg-ember`, `text-ember` | Warna aksen (CTAs, Highlight, Data penting, Indikator LIVE). |
| **Slate** | `#535366` | `bg-slate`, `text-slate` | Teks sekunder, deskripsi detail, elemen non-fokus. |
| **Light** | `#D8D3CA` | `bg-light`, `text-light` | Latar blok alternatif untuk kontras visual terhadap Canvas. |

---

## Typography

Sistem tipografi menggunakan dua jenis font utama dari Google Fonts. 

### 1. Inter (Sans-serif)
Digunakan untuk headings, paragraf, deskripsi, dan elemen UI umum.
*   **Weights:** Regular (400), SemiBold (600), Bold (700), Black (900).
*   **Class:** `font-sans` (Default body).
*   **Karakteristik:** Teks besar sering ditulis dengan `uppercase`, `tracking-widest` (spasi huruf lebar) untuk subtitle, atau `tracking-tighter` (spasi huruf rapat) untuk Headline raksasa.

### 2. Space Mono (Monospace)
Digunakan KHUSUS untuk angka data, timer, kode referensi, dan indikator sistem.
*   **Weights:** Regular (400), Bold (700).
*   **Class:** `font-mono`.
*   **Karakteristik:** Memperkuat kesan industrial dan dashboard teknis. Selalu gunakan font ini jika menampilkan data angka (misal: `01:12:04`, `18 DAYS`, `4,200 KG`).

---

## UI Components & Rules

### Borders & Grids
Tata letak mengandalkan garis pembatas yang tegas.
*   Ketebalan standar garis adalah **3px** dengan warna **Charcoal**.
*   **Class Utama:** 
    *   `.border-grid` (Border semua sisi)
    *   `.border-b-grid`, `.border-r-grid`, `.border-t-grid`, `.border-l-grid`
*   *Rule of thumb:* Jika ada komponen bersebelahan, mereka harus dipisahkan oleh garis grid tebal, seolah-olah membentuk tabel raksasa.

### Hover States & Transitions
*   Transisi harus terasa "kasar" dan cepat, tidak bouncy atau terlalu halus.
*   **Class Kustom:** Gunakan `.hover-invert` atau `.hover-ember` yang memiliki kecepatan transisi `0.1s ease`.
*   *Interaction:* Tombol aksi utama (seperti Force Sync) merespons tekanan klik dengan translasi fisik menggunakan class `active:translate-y-1`.

### Strict "Don'ts"
Untuk menjaga konsistensi tema, hindari hal-hal berikut:
1.  **Dilarang pakai Emoji**: Dilarang menyisipkan emoji pada teks, judul, tombol, atau bagian UI mana pun. Antarmuka harus tetap murni tipografis agar auranya tetap serius, teknis, dan raw.
2.  **Dilarang pakai `rounded-*`**: Tambahkan CSS global `* { border-radius: 0 !important; }` sebagai jaring pengaman.
3.  **Dilarang pakai `shadow-*`**: Tambahkan CSS global `* { box-shadow: none !important; }`. 

---

## Responsive Guidelines

Pembangunan antarmuka harus dilakukan dengan pendekatan Mobile-First.

### Padding & Spacing
*   **Mobile (< 640px):** Gunakan `p-4` atau `p-6`. Konten padat namun tidak menempel ke tepi layar.
*   **Tablet (sm: 640px - md: 768px):** Gunakan `sm:p-8` atau `sm:p-10`.
*   **Desktop (lg: 1024px+):** Gunakan `lg:p-12` hingga `lg:p-20` pada Hero section untuk menonjolkan whitespace.

### Layout Shifts
*   **Kolom ke Baris:** Pada mobile, gunakan `flex-col`. Di atas breakpoint Tablet/Desktop, gunakan `md:flex-row` atau CSS Grid (`md:grid-cols-3`).
*   **Border Penyesuaian:** Saat orientasi berubah dari kolom ke baris, pastikan border disesuaikan.
    *   *Contoh:* Pada mobile item memiliki `.border-b-grid`. Pada desktop hilangkan garis bawah dan ganti menjadi garis kanan: `md:border-b-0 md:border-r-grid`.

### Mobile Navigation Drawer
*   Menu tersembunyi pada Mobile dan di-toggle menggunakan JavaScript via class `.is-open`.
*   Animasi buka-tutup menu menggunakan CSS transitions (memanipulasi `max-height`, `opacity`, dan `transform`) secara presisi (`cubic-bezier`).
*   Pada Desktop (`md:flex`), Drawer dinonaktifkan sepenuhnya secara paksa melalui CSS media query.

---

## Animations

Animasi diizinkan HANYA jika memiliki nilai fungsional atau merepresentasikan aktivitas mesin. Tidak untuk dekorasi berlebihan.
1.  **`.animate-blink`**: (Durasi 1.2s, tidak sampai opacity 0). Digunakan khusus pada lampu indikator rekaman (REC) agar terlihat seperti LED perangkat keras.
2.  **`.animate-pulse`**: Dapat digunakan pada indikator status OPERATIONAL.
3.  **Ticker/Marquee**: Digunakan untuk elemen teks baris peringatan bergerak (jika diperlukan di versi mendatang).
