# Kadirov Inc CRM Loyihasi (Vue.js 3 + Symfony 6.4)

Ushbu loyiha Figma dizayni (`Kadirov Inc`) asosida ishlab chiqilgan to'liq funksional CRM tizimi bo'lib, **Vue.js 3** (Frontend) va **Symfony 6.4 + Doctrine ORM** (Backend) qismlaridan iborat.

## Funksional imkoniyatlar
1. **Foydalanuvchilar (Users)**:
   - Foydalanuvchi qo'shish, o'zgartirish va o'chirish (`Rostan ham o'chirilsinmi ? Ha / Yo'q` tasdiqlash oynasi bilan).
   - Yaratilgan har qanday foydalanuvchi o'zining Email va Paroli orqali tizimga (Login) kira oladi.
   - 4 ta ustun bo'yicha jonli qidiruv: `Foydalanuvchi qidirish`, `Email qidirish`, `Parol qidirish`, `So'ngi faollikni qidirish`.
2. **Kompaniyalar (Companies)**:
   - Kompaniya qo'shish, o'zgartirish va o'chirish.
   - 4 ta ustun bo'yicha jonli qidiruv: `Kompaniya qidirish`, `Email qidirish`, `Parol qidirish`, `So'ngi faollikni qidirish`.
3. **Mijozlar (Customers)**:
   - Mijoz qo'shish, o'zgartirish va o'chirish.
   - Mijoz qo'shayotganda yoki o'zgartirayotganda **Ishlash joyi (Kompaniya)** ni maxsus ochiluvchi ro'yxatdan (`Kompaniya 1`, `Kompaniya 2` va h.k.) tanlash imkoniyati.
   - 5 ta ustun bo'yicha jonli qidiruv: `Mijozlar qidirish`, `Email qidirish`, `Parol qidirish`, `Ishlash joyini qidirish`, `So'ngi faollikni qidirish`.
4. **Maxsus UI elementlar (Figma bo'yicha)**:
   - Yashil bildirishnoma (`Ma'lumotlat muaffaqiyatli o'zgartirildi`).
   - Chap menyuni yig'ish va ochish (`Menyuni yopish`).
   - Tizimdan chiqish va yangi yaratilgan foydalanuvchi bilan tizimga kirish.

## Ishga tushirish (Local Server)

### 1. Backend (Symfony 6.4)
```bash
cd backend-symfony
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
symfony server:start -d
```

### 2. Frontend (Vue 3 + Vite)
```bash
cd frontend-vue
npm install
npm run dev
```
